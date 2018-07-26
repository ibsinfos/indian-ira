<?php

namespace IndianIra\Http\Controllers\Admin\Categories;

use IndianIra\Category;
use Illuminate\Http\Request;
use IndianIra\Http\Controllers\Controller;

class ProductSortController extends Controller
{
    /**
     * Display all the products of the given category id.
     *
     * @return  \Illuminate\View\View
     */
    public function index($id)
    {
        $category = $this->getAllCategories()->where('id', $id)->first();

        if (! $category) {
            abort(404);
        }

        $products = $category->getAllProducts();

        return view('admin.categories-products.index', compact('category', 'products'));
    }

    /**
     * Update the sort number of the given product id.
     *
     * @param   integer  $categoryId
     * @param   integer  $productId
     * @param   \Illuminate\Http\Request  $request
     * @return  \Illuminate\Support\Facades\Response
     */
    public function updateSort($categoryId, $productId, Request $request)
    {
        $category = $this->getAllCategories()->where('id', $categoryId)->first();
        if (! $category) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'delay'   => 3000,
                'message' => 'Category with that id cannot be found!'
            ]);
        }

        $product = $category->getAllProducts()->where('id', $productId)->first();
        if (! $product) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'delay'   => 3000,
                'message' => 'Product with that id cannot be found!'
            ]);
        }

        $this->validate($request, [
            'sort_number' => 'required|integer|min:0'
        ]);

        $product->update(['sort_number' => (int) $request->sort_number]);

        $products = $category->getAllProducts();

        return response([
            'status'     => 'success',
            'title'      => 'Success !',
            'delay'      => 3000,
            'message'    => 'Product sort number updated successfully!',
            'htmlResult' => view('admin.categories-products.table', compact('category', 'products'))->render()
        ]);
    }

    /**
     * Get all the categories.
     *
     * @return  Illuminate\Database\Eloquent\Collection
     */
    protected function getAllCategories()
    {
        return Category::withTrashed()->orderBy('id', 'DESC')->get();
    }
}
