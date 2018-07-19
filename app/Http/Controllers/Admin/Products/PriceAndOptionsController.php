<?php

namespace IndianIra\Http\Controllers\Admin\Products;

use IndianIra\Product;
use Illuminate\Http\Request;
use IndianIra\ProductPriceAndOption;
use IndianIra\Utilities\Directories;
use Intervention\Image\Facades\Image;
use IndianIra\Http\Controllers\Controller;

class PriceAndOptionsController extends Controller
{
    use Directories;

    /**
     * Display all the products.
     *
     * @return  \Illuminate\View\View
     */
    public function index($id)
    {
        $product = $this->getAllProducts()->where('id', $id)->first();
        if (! $product) {
            abort(404);
        }

        $pricesAndOptions = $this->getAllPricesAndOptions($id);

        return view('admin.products-price-and-options.index', compact('product', 'pricesAndOptions'));
    }

    /**
     * Store the product prices and options data of the given product id.
     *
     * @param   integer  $productId
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function store($productId, Request $request)
    {
        $dimensions = 'min_width:500,max_width=1280,min_height:500,max_height=1280';
        $this->validate($request, [
            'option_code'          => 'required|alpha_dash|max:100|unique:product_price_and_options,option_code',
            'option_1_heading'     => 'nullable|max:100',
            'option_1_value'       => 'nullable|max:100',
            'option_2_heading'     => 'nullable|max:100',
            'option_2_value'       => 'nullable|max:100',
            'selling_price'        => 'required|regex:/^\d+(\.(\d{0,2}))?$/',
            'discount_price'       => 'regex:/^\d+(\.(\d{0,2}))?$/',
            'stock'                => 'required|integer|min:0',
            'sort_number'          => 'required|integer|min:0',
            'weight'               => 'required|regex:/^\d+(\.(\d{0,2}))?$/',
            'display'              => 'required|in:Enabled,Disabled',
            'image_file'           => 'bail|nullable|image|max:600|mimes:JPG,JPEG,PNG,jpg,jpeg,png|dimensions:'.$dimensions,
            'gallery_image_file_1' => 'bail|nullable|image|max:600|mimes:JPG,JPEG,PNG,jpg,jpeg,png|dimensions:'.$dimensions,
            'gallery_image_file_2' => 'bail|nullable|image|max:600|mimes:JPG,JPEG,PNG,jpg,jpeg,png|dimensions:'.$dimensions,
            'gallery_image_file_3' => 'bail|nullable|image|max:600|mimes:JPG,JPEG,PNG,jpg,jpeg,png|dimensions:'.$dimensions,
        ], [
            'selling_price.regex'   => 'The selling price should contain only numbers upto 2 precisions.',
            'discount_price.regex'  => 'The discount price should contain only numbers upto 2 precisions.',
            'weight.regex'          => 'The weight should contain only numbers upto 2 precisions.',
            'display.in'            => 'The display should eiter be Enabled or Disabled.',
            'image_file.image'      => 'The uploaded file should be an image.',
            'image_file.max'        => 'The uploaded image file may not be greater than 600 kilobytes.',
            'image_file.dimensions' => 'The uploaded image file should be between 500px and 1280px in width and height.',
            'image_file.mimes'      => 'The uploaded image file must be a file of type: JPG, JPEG, PNG, jpg, jpeg, png.',
            'gallery_image_file_1.image'      => 'The uploaded gallery image file 1 should be an image.',
            'gallery_image_file_1.max'        => 'The uploaded gallery image file 1 may not be greater than 600 kilobytes.',
            'gallery_image_file_1.dimensions' => 'The uploaded gallery image file 1 should be between 500px and 1280px in width and height.',
            'gallery_image_file_1.mimes'      => 'The uploaded gallery image file 1 must be a file of type: JPG, JPEG, PNG, jpg, jpeg, png.',
            'gallery_image_file_2.image'      => 'The uploaded gallery image file 2 should be an image.',
            'gallery_image_file_2.max'        => 'The uploaded gallery image file 2 may not be greater than 600 kilobytes.',
            'gallery_image_file_2.dimensions' => 'The uploaded gallery image file 2 should be between 500px and 1280px in width and height.',
            'gallery_image_file_2.mimes'      => 'The uploaded gallery image file 2 must be a file of type: JPG, JPEG, PNG, jpg, jpeg, png.',
            'gallery_image_file_3.image'      => 'The uploaded gallery image file 3 should be an image.',
            'gallery_image_file_3.max'        => 'The uploaded gallery image file 3 may not be greater than 600 kilobytes.',
            'gallery_image_file_3.dimensions' => 'The uploaded gallery image file 3 should be between 500px and 1280px in width and height.',
            'gallery_image_file_3.mimes'      => 'The uploaded gallery image file 3 must be a file of type: JPG, JPEG, PNG, jpg, jpeg, png.',
        ]);

        $product = $this->getAllProducts()->where('id', $productId)->first();
        if (! $product) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'delay'   => 3000,
                'message' => 'Product with that id could not be found',
            ]);
        }

        $request['product_id'] = $productId;

        if ($request->image_file != null) {
            $file = $this->processUploadedFile($request->image_file);
            $request['image'] = implode('; ', $file);
        } else {
            $request['image'] = $product->images;
        }

        if ($request->gallery_image_file_1 != null) {
            $file = $this->processUploadedFileForGallery($request->gallery_image_file_1);
            $request['gallery_image_1'] = implode('; ', $file);
        } else {
            $request['gallery_image_1'] = null;
        }

        if ($request->gallery_image_file_2 != null) {
            $file = $this->processUploadedFileForGallery($request->gallery_image_file_2);
            $request['gallery_image_2'] = implode('; ', $file);
        } else {
            $request['gallery_image_2'] = null;
        }

        if ($request->gallery_image_file_3 != null) {
            $file = $this->processUploadedFileForGallery($request->gallery_image_file_3);
            $request['gallery_image_3'] = implode('; ', $file);
        } else {
            $request['gallery_image_3'] = null;
        }

        ProductPriceAndOption::create($request->all());

        $pricesAndOptions = $this->getAllPricesAndOptions($productId);

        return response([
            'status'   => 'success',
            'title'    => 'Success !',
            'delay'    => 3000,
            'message'  => 'Product prices and options added successfully!',
            'location' => route('admin.products.priceAndOptions', $product->id)
        ]);
    }

    /**
     * Update the price and option details of the given product id and option id.
     *
     * @param   integer  $productId
     * @param   integer  $optionId
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function update($productId, $optionId, Request $request)
    {
        $product = $this->getAllProducts()->where('id', $productId)->first();
        if (! $product) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'delay'   => 3000,
                'message' => 'Product with that id could not be found!',
            ]);
        }

        $option = ProductPriceAndOption::whereProductId($productId)->find($optionId);
        if (! $option) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'delay'   => 3000,
                'message' => 'Product price and option with that id could not be found!',
            ]);
        }

        $dimensions = 'min_width:500,max_width=1280,min_height:500,max_height=1280';
        $this->validate($request, [
            'option_code'          => 'required|alpha_dash|max:100|unique:product_price_and_options,option_code,'.$option->id,
            'option_1_heading'     => 'nullable',
            'option_1_value'       => 'nullable',
            'option_2_heading'     => 'nullable',
            'option_2_value'       => 'nullable',
            'selling_price'        => 'required|regex:/^\d+(\.(\d{0,2}))?$/',
            'discount_price'       => 'regex:/^\d+(\.(\d{0,2}))?$/',
            'stock'                => 'required|integer|min:0',
            'sort_number'          => 'required|integer|min:0',
            'weight'               => 'required|regex:/^\d+(\.(\d{0,2}))?$/',
            'display'              => 'required|in:Enabled,Disabled',
            'image_file'           => 'bail|nullable|image|max:600|mimes:JPG,JPEG,PNG,jpg,jpeg,png|dimensions:'.$dimensions,
            'gallery_image_file_1' => 'bail|nullable|image|max:600|mimes:JPG,JPEG,PNG,jpg,jpeg,png|dimensions:'.$dimensions,
            'gallery_image_file_2' => 'bail|nullable|image|max:600|mimes:JPG,JPEG,PNG,jpg,jpeg,png|dimensions:'.$dimensions,
            'gallery_image_file_3' => 'bail|nullable|image|max:600|mimes:JPG,JPEG,PNG,jpg,jpeg,png|dimensions:'.$dimensions,
        ], [
            'selling_price.regex'  => 'The selling price should contain only numbers upto 2 precisions.',
            'discount_price.regex'  => 'The discount price should contain only numbers upto 2 precisions.',
            'weight.regex'          => 'The weight should contain only numbers upto 2 precisions.',
            'display.in'            => 'The display should eiter be Enabled or Disabled.',
            'image_file.image'      => 'The uploaded file should be an image.',
            'image_file.max'        => 'The uploaded image file may not be greater than 600 kilobytes.',
            'image_file.dimensions' => 'The uploaded image file should be between 500px and 1280px in width and height.',
            'image_file.mimes'      => 'The uploaded image file must be a file of type: JPG, JPEG, PNG, jpg, jpeg, png.',
        ]);

        $request['product_id'] = $productId;

        if ($request->image_file) {
            $file = $this->processUploadedFile($request->image_file);
            $request['image'] = implode('; ', $file);
        }

        if ($request->gallery_image_file_1 != null) {
            $file = $this->processUploadedFileForGallery($request->gallery_image_file_1);
            $request['gallery_image_1'] = implode('; ', $file);
        }

        if ($request->gallery_image_file_2 != null) {
            $file = $this->processUploadedFileForGallery($request->gallery_image_file_2);
            $request['gallery_image_2'] = implode('; ', $file);
        }

        if ($request->gallery_image_file_3 != null) {
            $file = $this->processUploadedFileForGallery($request->gallery_image_file_3);
            $request['gallery_image_3'] = implode('; ', $file);
        }

        $option->update($request->all());

        $pricesAndOptions = $this->getAllPricesAndOptions($productId);

        return response([
            'status'     => 'success',
            'title'      => 'Success !',
            'delay'      => 3000,
            'message'    => 'Product price and option updated successfully!',
            'htmlResult' => view('admin.products-price-and-options.table', compact('product', 'pricesAndOptions'))
                                ->render()
        ]);
    }

    /**
     * Permanently delete the product data of the given id.
     *
     * @param   integer  $productId
     * @param   integer  $optionId
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function destroy($productId, $optionId)
    {
        $product = $this->getAllProducts()->where('id', $productId)->first();
        if (! $product) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'delay'   => 3000,
                'message' => 'Product with that id could not be found!',
            ]);
        }

        $option = ProductPriceAndOption::whereProductId($productId)->find($optionId);
        if (! $option) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'delay'   => 3000,
                'message' => 'Product price and option with that id could not be found!',
            ]);
        }

        $option->delete();

        $pricesAndOptions = $this->getAllPricesAndOptions($productId);

        return response([
            'status'     => 'success',
            'title'      => 'Success !',
            'delay'      => 3000,
            'message'    => 'Product price and option destroyed successfully!',
            'location'   => route('admin.products.priceAndOptions', $productId),
            'htmlResult' => view('admin.products-price-and-options.table', compact('pricesAndOptions'))
                                ->render()
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
     * Get all the product's price and option data of the given product id.
     *
     * @return  Illuminate\Database\Eloquent\Collection
     */
    protected function getAllPricesAndOptions($productId)
    {
        return ProductPriceAndOption::whereProductId($productId)->orderBy('id', 'DESC')->get();
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
     * Store the uploaded file.
     *
     * @param   \Illuminate\Http\UploadedFile  $file
     * @return  array
     */
    protected function processUploadedFileForGallery($file)
    {
        $imageRelativePath = 'images-products/';
        $path = $this->createDirectoryIfNotExists($imageRelativePath);

        $uploadedFileNames = [];

        $cart    = $file;
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

        // $image = Image::make($file);
        // $height = $width = 300;
        // if ($image->height() >= 1281) {
        //     $width = null;
        // }

        // if ($image->width() >= 1281) {
        //     $height = null;
        // }

        // $image = $image->resize($width, $height, function ($constraint) {
        //     $constraint->aspectRatio();
        // });
        // $image->resizeCanvas(300, 300);
        // $fileName = $this->getFileName($file, 'catalog');
        // $uploadedFileNames[] = '/images-products/'.$fileName;
        // $image->save($path . $fileName, 100);
        // $image->destroy();

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
