<?php

namespace IndianIra\Http\Controllers\Admin\Products;

use IndianIra\Product;
use Illuminate\Http\Request;
use IndianIra\Utilities\Directories;
use Intervention\Image\Facades\Image;
use IndianIra\Http\Controllers\Controller;

class ProductsController extends Controller
{
    use Directories;

    /**
     * Display all the products.
     *
     * @return  \Illuminate\View\View
     */
    public function index()
    {
        $products = $this->getAllProducts();

        return view('admin.products.index', compact('products'));
    }

    /**
     * Store the product data.
     *
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'code'              => 'bail|required|alpha_dash|unique:products,code|max:100',
            'name'              => 'bail|required|string|max:100',
            'gst_percent'       => 'bail|nullable|regex:/^\d+(\.(\d{0,2}))?$/',
            'number_of_options' => 'bail|nullable|integer|min:0',
        ]);

        $product = Product::create($request->all());

        $products = $this->getAllProducts();

        return response([
            'status'   => 'success',
            'title'    => 'Success !',
            'delay'    => 3000,
            'message'  => 'Product added successfully! Redirecting to edit...',
            'location' => route('admin.products.edit', $product->id) . '?general'
        ]);
    }

    /**
     * Display the edit form of the given product id.
     *
     * @param   integer  $id
     * @return  \Illuminate\View\View
     */
    public function edit($id)
    {
        $product = $this->getAllProducts()->where('id', $id)->first();
        if (! $product) {
            abort(404);
        }

        $selectedCategories = $product->categories->map(function ($category) {
            return $category->id;
        });
        $selectedCategories = $selectedCategories->implode(',');

        $categories = \IndianIra\Category::whereDisplay('Enabled')->get();

        $tags = \IndianIra\Tag::all();
        $selectedTags = $product->tags->pluck('id')->implode(',');

        return view('admin.products.edit', compact(
            'product', 'categories', 'selectedCategories', 'tags', 'selectedTags'
        ));
    }

    /**
     * Update the general details of the given product id.
     *
     * @param   integer  $id
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function updateGeneral($id, Request $request)
    {
        $this->validate($request, [
            'code'              => 'required|alpha_dash|unique:products,code,'.$id.'|max:100',
            'name'              => 'required|max:100',
            'display'           => 'required|in:Enabled,Disabled',
            'gst_percent'       => 'nullable|regex:/^\d+(\.(\d{0,2}))?$/',
            'number_of_options' => 'required|integer|between:0,2'
        ], [
            'code.required' => 'The product code field is required.',
            'code.unique' => 'The product code has already been taken.',
            'code.alpha_dash' => 'The product code may only contain letters, numbers, dashes and underscores.',
            'name.required' => 'The product name field is required.',
            'name.max' => 'The product name should be less than equal to 100 characters.',
            'display.required' => 'The product display field is required.',
            'display.in' => 'The product display should either be Enabled or Disabled.',
            'gst_percent.regex' => 'The product gst percent should contain only numeric values upto 2 precisions only.',
            'number_of_options.required' => 'The product number of options field is required.',
            'number_of_options.integer' => 'The product number of options should be an integer.',
            'number_of_options.between' => 'The product number of options should be between 0 and 2.',
        ]);

        $product = $this->getAllProducts()->where('id', $id)->first();
        if (! $product) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'delay'   => 3000,
                'message' => 'Product with that id could not be found!',
            ]);
        }

        $request['gst_percent'] = $request->gst_percent != null ? $request->gst_percent : 0.0;
        $product->update($request->all());

        $product->categories()->sync($request->category_id);

        $product->tags()->sync($request->tag_id);

        return response([
            'status'  => 'success',
            'title'   => 'Success !',
            'delay'   => 3000,
            'message' => 'Product general details updated successfully!',
        ]);
    }

    /**
     * Update the detailed information of the given product id.
     *
     * @param   integer  $id
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function updateDetailedInformation($id, Request $request)
    {
        $this->validate($request, [
            'description'      => 'required|max:3000',
            'additional_notes' => 'nullable|max:3000',
            'terms'            => 'nullable|max:3000',
        ]);

        $product = $this->getAllProducts()->where('id', $id)->first();
        if (! $product) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'delay'   => 3000,
                'message' => 'Product with that id could not be found!',
            ]);
        }

        $request['terms']            = htmlspecialchars($request->terms);
        $request['description']      = htmlspecialchars($request->description);
        $request['additional_notes'] = htmlspecialchars($request->additional_notes);
        $product->update($request->all());

        return response([
            'status'  => 'success',
            'title'   => 'Success !',
            'delay'   => 3000,
            'message' => 'Product detailed information updated successfully!',
        ]);
    }

    /**
     * Update the detailed information of the given product id.
     *
     * @param   integer  $id
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function updateMetaInformation($id, Request $request)
    {
        $this->validate($request, [
            'meta_title'       => 'required|max:60',
            'meta_description' => 'required|max:160',
            'meta_keywords'    => 'nullable|max:150',
        ]);

        $product = $this->getAllProducts()->where('id', $id)->first();
        if (! $product) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'delay'   => 3000,
                'message' => 'Product with that id could not be found!',
            ]);
        }

        $product->update($request->all());

        return response([
            'status'  => 'success',
            'title'   => 'Success !',
            'delay'   => 3000,
            'message' => 'Product meta information updated successfully!',
        ]);
    }

    /**
     * Update the images of the given product id.
     *
     * @param   integer  $id
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function updateImage($id, Request $request)
    {
        $dimensions = 'min_width:500,max_width=1280,min_height:500,max_height=1280';
        $this->validate($request, [
            'image' => 'bail|required|image|max:600|mimes:JPG,JPEG,PNG,jpg,jpeg,png|dimensions:'.$dimensions
        ], [
            'image.image'      => 'The uploaded file should be an image.',
            'image.max'        => 'The uploaded image file may not be greater than 600 kilobytes.',
            'image.dimensions' => 'The uploaded image file should be between 500px and 1280px in width and height.',
            'image.mimes'      => 'The uploaded image file must be a file of type: JPG, JPEG, PNG, jpg, jpeg, png.',
        ]);

        $product = $this->getAllProducts()->where('id', $id)->first();
        if ($product == null) {
            return response([
                'status' => 'failed',
                'title'  => 'Failed !',
                'delay'  => 3000,
                'message' => 'Product with that id could not be found!'
            ]);
        }

        $file = $this->processUploadedFile($request->image);

        $product->update(['images' => implode('; ', $file)]);

        return response([
            'status'  => 'success',
            'title'   => 'Success !',
            'delay'   => 3000,
            'message' => 'Product image updated successfully!'
        ]);
    }

    /**
     * Temporarily delete the product data of the given id.
     *
     * @param   integer  $id
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function delete($id)
    {
        $product = Product::find($id);

        if (! $product) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'message' => 'Product with that id cannot be found.'
            ]);
        }

        $product->delete();

        $products = $this->getAllProducts();

        return response([
            'status'     => 'success',
            'title'      => 'Success !',
            'delay'      => 3000,
            'message'    => 'Product deleted temporarily!',
            'location'   => route('admin.products'),
            'htmlResult' => view('admin.products.table', compact('products'))->render(),
        ]);
    }

    /**
     * Restore the temporarily deleted product data of the given id.
     *
     * @param   integer  $id
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function restore($id)
    {
        $product = Product::onlyTrashed()->find($id);

        if (! $product) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'message' => 'Product with that id cannot be found.'
            ]);
        }

        $product->restore();

        $products = $this->getAllProducts();

        return response([
            'status'     => 'success',
            'title'      => 'Success !',
            'delay'      => 3000,
            'message'    => 'Product restored successfully!',
            'location'   => route('admin.products'),
            'htmlResult' => view('admin.products.table', compact('products'))->render(),
        ]);
    }

    /**
     * Permanently delete the product data of the given id.
     *
     * @param   integer  $id
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function destroy($id)
    {
        $product = Product::onlyTrashed()->find($id);

        if (! $product) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'message' => 'Product with that id cannot be found.'
            ]);
        }

        $images = explode('; ', $product->images);
        foreach ($images as $image) {
            \Illuminate\Support\Facades\File::delete(public_path($image));
        }

        $product->categories()->sync([]);

        $product->tags()->sync([]);

        $product->forceDelete();

        $products = $this->getAllProducts();

        return response([
            'status'     => 'success',
            'title'      => 'Success !',
            'delay'      => 3000,
            'message'    => 'Product destroyed successfully!',
            'location'   => route('admin.products'),
            'htmlResult' => view('admin.products.table', compact('products'))->render(),
        ]);
    }

    /**
     * Get all the products.
     *
     * @return  Illuminate\Database\Eloquent\Collection
     */
    protected function getAllProducts()
    {
        return Product::withTrashed()->orderBy('id', 'DESC')->get();
    }

    /**
     * Store the uploaded file.
     *
     * @param   \Illuminate\Http\UploadedFile  $file
     * @return  array
     */
    protected function processUploadedFile($file)
    {
        $imageRelativePath = 'images-products/';
        $path = $this->createDirectoryIfNotExists($imageRelativePath);

        $uploadedFileNames = [];

        $cart    = $file;
        $catalog = $file;
        $zoomed  = $file;

        $image = Image::make($file);
        $height = $width = 75;
        if ($image->height() >= 1281) {
            $width = null;
        }

        if ($image->width() >= 1281) {
            $height = null;
        }

        $image = $image->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        $image->resizeCanvas(75, 75);
        $fileName = $this->getFileName($file, 'cart');
        $uploadedFileNames[] = '/images-products/'.$fileName;
        $image->save($path . $fileName, 100);
        $image->destroy();

        $image = Image::make($file);
        $height = $width = 300;
        if ($image->height() >= 1281) {
            $width = null;
        }

        if ($image->width() >= 1281) {
            $height = null;
        }

        $image = $image->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        $image->resizeCanvas(300, 300);
        $fileName = $this->getFileName($file, 'catalog');
        $uploadedFileNames[] = '/images-products/'.$fileName;
        $image->save($path . $fileName, 100);
        $image->destroy();

        $zoomedImage = Image::make($file);
        $img = $zoomedImage->resizeCanvas(1280, 1280);
        $fileName = $this->getFileName($file, 'zoomed');
        $uploadedFileNames[] = '/images-products/'.$fileName;
        $zoomedImage->save($path . $fileName, 100);
        $zoomedImage->destroy();

        return $uploadedFileNames;
    }

    /**
     * Get the new file name of the uploaded file.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string  $imageType
     */
    protected function getFileName($file, $imageType)
    {
        $originalName = $file->getClientOriginalName();
        $originalExt = $file->getClientOriginalExtension();

        $filename = explode('.', $originalName);

        return str_slug($filename[0]) . "-{$imageType}." . str_slug($originalExt);
    }
}
