<?php

namespace IndianIra\Http\Controllers\Admin\Products;

use IndianIra\Product;
use Illuminate\Http\Request;
use IndianIra\ProductPriceAndOption;
use IndianIra\Http\Controllers\Controller;

class PriceAndStockController extends Controller
{
    /**
     * Display all the products prices and stock positions.
     *
     * @return  \Illuminate\View\View
     */
    public function index()
    {
        $products = $this->getAllProducts();

        return view('admin.products-price-and-stock.index', compact('products'));
    }

    /**
     * Update the price and option details of the given product id and option id.
     *
     * @param   integer  $productId
     * @param   integer  $optionId
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function update($productCode, $optionCode, Request $request)
    {
        $option = $this->getAllProducts()->where('option_code', $optionCode)->first();
        if (! $option) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'delay'   => 3000,
                'message' => 'Product price and option with that id could not be found!',
            ]);
        }

        if (! $option->product) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'delay'   => 3000,
                'message' => 'Product with that id could not be found!',
            ]);
        }

        $this->validate($request, [
            'selling_price'  => 'required|regex:/^\d+(\.(\d{0,2}))?$/',
            'discount_price' => 'regex:/^\d+(\.(\d{0,2}))?$/',
            'stock'          => 'required|integer|min:0',
            'gst_percent'    => 'regex:/^\d+(\.(\d{0,2}))?$/',
        ], [
            'selling_price.regex'  => 'The selling price should contain only numbers upto 2 precisions.',
            'discount_price.regex'  => 'The discount price should contain only numbers upto 2 precisions.',
            'gst_percent.regex'     => 'The gst percent should contain only numbers upto 2 precisions.',
        ]);

        $option->product->update(['gst_percent' => $request->gst_percent]);

        $option->update($request->all());

        $products = $this->getAllProducts();

        return response([
            'status'     => 'success',
            'title'      => 'Success !',
            'delay'      => 3000,
            'message'    => 'Product prices and stock updated successfully!',
            'htmlResult' => view('admin.products-price-and-stock.table', compact('products'))->render()
        ]);
    }

    /**
     * Get all the products.
     *
     * @return  Illuminate\Database\Eloquent\Collection
     */
    protected function getAllProducts()
    {
        $allProducts = Product::withTrashed()
                        ->with(['options'])
                        ->orderBy('id', 'DESC')
                        ->get();

        $productsCollection = collect();

        foreach ($allProducts as $product) {
            foreach ($product->options as $option) {
                $productsCollection->push($option);
            }
        }

        return $productsCollection;
    }
}
