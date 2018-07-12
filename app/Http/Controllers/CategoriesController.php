<?php

namespace IndianIra\Http\Controllers;

use IndianIra\Category;
use Illuminate\Http\Request;
use IndianIra\Utilities\PaginateCollection;

class CategoriesController extends Controller
{
    use PaginateCollection;

    /**
     * Display the products of the given category id.
     *
     * @param   integer  $id
     * @param   string  $slug
     * @return  void|\Illuminate\View\View
     */
    public function show($id, $slug)
    {
        $category = Category::whereDisplay('Enabled')->whereSlug($slug)->find($id);
        if (! $category) {
            abort(404);
        }

        if (app()->environment() == 'testing') {
            $allCategoriesInMenu = Category::onlySuperParent()->onlyEnabled()->get();
        }

        $products = $this->paginate($category->onlyEnabledProducts(), 12);

        return view('categories.show', compact(
            'allCategories', 'category', 'products', 'allCategoriesInMenu'
        ));
    }
}
