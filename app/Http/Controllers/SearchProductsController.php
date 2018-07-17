<?php

namespace IndianIra\Http\Controllers;

use IndianIra\Product;
use IndianIra\Category;
use Illuminate\Http\Request;

class SearchProductsController extends Controller
{
    /**
     * Search the product.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = e($request->input('q'));

        if (! $query && $query == '') {
            return response([
                'status' => 'failed'
            ]);
        }

        $products = Product::where('display', 'Enabled')
            ->where('name', 'LIKE', '%'.$query.'%')
            ->orderBy('name', 'asc')
            ->get();

        $products->transform(function ($product) {
            $product->name = title_case($product->name);

            return $product;
        });

        $categories = Category::all();

        $products->each(function ($product) use ($categories) {
            if ($product->categories->isEmpty() && app()->environment() != 'production') {
                $product->categories()->attach($categories->pluck('id')->toArray());
            }
        });

        $products = $this->appendURL($products);

        $products = $this->appendValue($products, 'product', 'class');

        return response()->json(['data' => $products]);
    }

    /**
     * Append values to existing array of products.
     *
     * @param  array $data
     * @param  string $type
     * @param  string $element
     * @return array
     */
    public function appendValue($data, $type, $element)
    {
        foreach ($data as $key => &$item) {
            $item[$element] = $type;
        }

        return $data;
    }

    /**
     * Append product URL to existing array of products.
     *
     * @param  array $data
     * @return array
     */
    public function appendURL($data)
    {
        foreach ($data as $key => &$item) {
            $item['url'] = url($item->canonicalPageUrl());
        }

        return $data;
    }

}
