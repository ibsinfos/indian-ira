<?php

namespace IndianIra\Http\Controllers;

use IndianIra\Category;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display the products of the given category id.
     *
     * @param   integer  $id
     * @param   string  $slug
     * @return  void|\Illuminate\View\View
     */
    public function show($categoryId, $categorySlug, $productCode, $productName)
    {
        $category = Category::whereDisplay('Enabled')->whereSlug($categorySlug)->find($categoryId);
        if (! $category) {
            abort(404);
        }

        $product = $category->products()->with('options')->onlyEnabled()->whereCode($productCode)->first();
        if (! $product) {
            abort(404);
        }

        if (app()->environment() == 'testing') {
            $allCategoriesInMenu = Category::onlySuperParent()->onlyEnabled()->get();
        }

        return view('products.show', compact(
            'category', 'product', 'allCategoriesInMenu'
        ));
    }
}
