<?php

namespace IndianIra\Http\Controllers\Admin\Products;

use IndianIra\Tag;
use IndianIra\Product;
use IndianIra\Category;
use Illuminate\Support\Facades\DB;
use IndianIra\ProductPriceAndOption;
use Maatwebsite\Excel\Facades\Excel;
use IndianIra\Http\Controllers\Controller;

class ImportController extends Controller
{
    /**
     * Import the products data in xlsx file
     *
     * @return  void
     */
    public function import()
    {
        $request = request();

        if ($request->excel_file === null) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'delay'   => 3000,
                'message' => 'Please attach a file'
            ]);
        }

        $request['extension'] = strtolower(($request->excel_file->getClientOriginalExtension()));

        $this->validate($request, [
            'excel_file' => 'required',
            'extension'    => 'required|in:xlsx,xls'
        ]);

        $success = $this->canAddCategories($request->excel_file);
        if (! $success) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'delay'   => 3000,
                'message' => 'It seems like you have added more than 3 levels of categories. Please check.'
            ]);
        }

        $sheetNames = Excel::load($request->excel_file)->getSheetNames();

        foreach($sheetNames as $sheet) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            $this->chunkAndInsert($request->excel_file, $sheet, 400);

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        return response([
            'status'  => 'success',
            'title'   => 'Success !',
            'delay'   => 3000,
            'message' => 'Product Data Imported Successfully. Reloading',
            'location' => route('admin.products')
        ]);
    }

    protected function canAddCategories($file)
    {
        $cannotAddCategories = false;

        $categories = collect(Excel::selectSheets('products')
                                   ->load($file)
                                   ->select(['categories'])
                                   ->get());

        foreach ($categories as $category) {
            $list = explode('; ', $category['categories']);

            foreach ($list as $neCat) {
                $ca = explode(' > ', $neCat);

                if (count($ca) >= 4) {
                    $cannotAddCategories = true;

                    break;
                }
            }

            if ($cannotAddCategories == true) {
                break;
            }
        }

        if ($cannotAddCategories == true) {
            return false;
        }

        return true;
    }

    /**
     * Chunk the file into the chunkCount numbers of records.
     *
     * @param   \Illuminate\Http\UploadedFile  $file
     * @param   string  $sheetName
     * @param   integer $chunkCount
     * @return  void
     */
    protected function chunkAndInsert($file, $sheetName, $chunkCount = 100)
    {
        Excel::filter('chunk')
               ->selectSheets($sheetName)
               ->load($file)
               ->chunk($chunkCount, function ($reader) use ($sheetName) {
                    foreach ($reader->toArray() as $sheet) {
                        if ($sheetName == 'products') {
                            if (Product::find($sheet['id']) == null) {
                                $product = $this->insertInProductsTable($sheet);
                            } else {
                                $product = $this->updateProductsTable($sheet);
                            }

                            $product->categories()->sync(
                                $this->syncCategories($sheet)
                            );

                            $product->tags()->sync(
                                $this->syncTags($sheet)
                            );
                        }

                        if ($sheetName == 'options') {
                            if (ProductPriceAndOption::find($sheet['id']) == null) {
                                ProductPriceAndOption::create($sheet);
                            } else {
                                ProductPriceAndOption::where('id', $sheet['id'])->update($sheet);
                            }
                        }
                    }
                }, false);
    }

    /**
     * Update the product from the given sheet's data.
     *
     * @param   array  $data
     * @return  \IndianIra\Product
     */
    protected function updateProductsTable($data)
    {
        $data = $this->getOnlyProductsData($data);

        $product = Product::find($data['id']);

        $product->update($data);

        return $product->fresh();
    }

    /**
     * Create a new product from the given sheet's data.
     *
     * @param   array  $data
     * @return  \IndianIra\Product
     */
    protected function insertInProductsTable($data)
    {
        $data = $this->getOnlyProductsData($data);

        return Product::create($data);
    }

    /**
     * Get only the products data from the uploaded excel file sheet's data.
     *
     * @param   array  $data
     * @return  array
     */
    protected function getOnlyProductsData($data)
    {
        return [
            'id'                => $data['id'],
            'code'              => $data['code'],
            'name'              => $data['name'],
            'number_of_options' => $data['number_of_options'],
            'gst_percent'       => $data['gst_percent'],
            'description'       => $data['description'],
            'additional_notes'  => $data['additional_notes'],
            'terms'             => $data['terms'],
            'meta_title'        => $data['meta_title'],
            'meta_description'  => $data['meta_description'],
            'meta_keywords'     => $data['meta_keywords'],
            'display'           => $data['display'],
            'images'            => $data['images'],
            'created_at'        => $data['created_at'],
            'updated_at'        => $data['updated_at'],
            'deleted_at'        => $data['deleted_at'],
        ];
    }

    /**
     * Synchronize the categories with the products.
     * Create the categories if not found, but added in the sheet.
     *
     * @param   array  $sheet
     * @return  array
     */
    protected function syncCategories($sheet)
    {
        $categoryNames = explode('; ', $sheet['categories']);

        $categoryList = [];

        foreach($categoryNames as $names) {
            $subCategory = explode(' > ', $names);

            $category = null;

            if (count($subCategory) == 1) {
                $parentCategory = Category::whereName($subCategory[0])->first();
                if ($parentCategory == null) {
                    $parentCategory = $category = Category::create($this->insertCategory($subCategory[0]));
                } else {
                    $category = $parentCategory;
                }

                array_push($categoryList, $parentCategory->id);
            }

            if (count($subCategory) == 2) {
                $parentCategory = Category::whereName($subCategory[0])->whereParentId(0)->first();
                if ($parentCategory == null) {
                    $parentCategory = Category::create($this->insertCategory($subCategory[0]));
                }

                $subCategoryExists = Category::whereName($subCategory[1])->whereParentId($parentCategory->id)->first();
                if ($subCategoryExists == null) {
                    $subCategoryExists = $category = Category::create($this->insertSubCategory($subCategory[1], $parentCategory));
                } else {
                    $category = $subCategoryExists;
                }

                array_push($categoryList, $subCategoryExists->id);
            }

            if (count($subCategory) == 3) {
                $parentCategory = Category::whereName($subCategory[0])->whereParentId(0)->first();
                if ($parentCategory == null) {
                    $parentCategory = Category::create($this->insertCategory($subCategory[0]));
                }

                $levelTwoCategory = Category::whereName($subCategory[1])->whereParentId($parentCategory->id)->first();
                if ($levelTwoCategory == null) {
                    $levelTwoCategory = Category::create($this->insertSubCategory($subCategory[1], $parentCategory));
                }

                $subCategoryExists = Category::whereName($subCategory[2])->whereParentId($levelTwoCategory->id)->first();
                if ($subCategoryExists == null) {
                    $subCategoryExists = $category = Category::create($this->insertSubCategory($subCategory[2], $levelTwoCategory));
                } else {
                    $category = $subCategoryExists;
                }

                array_push($categoryList, $subCategoryExists->id);
            }
        }

        return $categoryList;
    }

    /**
     * Insert the new Category.
     *
     * @param   string  $name
     * @return  array
     */
    protected function insertCategory($name)
    {
        return [
            'name'             => $name,
            'slug'             => str_slug($name),
            'parent_id'        => 0,
            'display_text'     => $name,
            'page_url'         => '/'.str_slug($name),
            'meta_title'       => null,
            'meta_description' => null,
            'meta_keywords'    => null,
            'display'          => 'Enabled',
        ];
    }

    /**
     * Insert the new sub Category.
     *
     * @param   string  $name
     * @param   \IndianIra\Category  $parentCategory
     * @return  array
     */
    protected function insertSubCategory($name, $parentCategory)
    {
        return [
            'name'             => $name,
            'slug'             => $parentCategory->slug . '-' .str_slug($name),
            'parent_id'        => $parentCategory->id,
            'display_text'     => $parentCategory->display_text .' > '. $name,
            'page_url'         => '/'.str_slug($parentCategory->slug) .'-'. str_slug($name),
            'meta_title'       => null,
            'meta_description' => null,
            'meta_keywords'    => null,
            'display'          => 'Enabled',
        ];
    }

    protected function syncTags($sheet)
    {
        $tagsName = explode('; ', $sheet['tags']);

        $tags = Tag::whereIn('name', $tagsName)->get();

        $tagsList = [];

        if (count($tagsName) >= 1) {
            foreach($tagsName as $name) {
                $tag = Tag::where('name', $name)->first();

                if ($tag == null && $name != '') {
                    $tag = Tag::create($this->insertTag($name));
                }

                $tagsList[] = $tag->id;
            }
        }

        return $tagsList;
    }

    protected function insertTag($name)
    {
        return [
            'name'               => $name,
            'slug'               => str_slug($name),
            'short_description'  => null,
            'meta_title'         => null,
            'meta_description'   => null,
            'meta_keywords'      => null,
        ];
    }
}
