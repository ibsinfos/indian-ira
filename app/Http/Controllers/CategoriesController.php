<?php

namespace IndianIra\Http\Controllers;

use IndianIra\Category;
use Illuminate\Http\Request;
use IndianIra\Utilities\PaginateCollection;

class CategoriesController extends Controller
{
    use PaginateCollection;

    protected $allProducts = null;

    /**
     * Display the products of the given category id.
     *
     * @param   integer  $id
     * @param   string  $slug
     * @return  void|\Illuminate\View\View
     */
    public function show($id, $slug)
    {
        $this->allProducts = collect();

        $category = Category::with(['products' => function ($query) {
            return $query->where('display', 'Enabled');
        }, 'childs'])->whereDisplay('Enabled')->whereSlug($slug)->find($id);
        if (! $category) {
            abort(404);
        }

        $superParentCategory = $category->getSuperParent();

        $this->allProducts = $category->products()->onlyEnabled()->get();

        // The category is the first child - no parent
        if ($category->isSuperParent()) {
            foreach ($category->childs as $child) {
                if (
                    $child->display == 'Enabled' &&
                    $child->products()->onlyEnabled()->get()->isNotEmpty()->sortBy('sort_number')
                ) {
                    foreach ($child->products as $product) {
                        $this->addProducts($product);
                    }
                }
            }
        }

        // The category is the first child - acts as parent and child
        if (! $category->isSuperParent() && $category->isParent()) {
            foreach ($category->childs as $child) {
                if (
                    $child->display == 'Enabled' &&
                    $child->products()->onlyEnabled()->get()->isNotEmpty()->sortBy('sort_number')
                ) {
                    foreach ($child->products as $product) {
                        $this->addProducts($product);
                    }
                }
            }
        }

        // The category is the last child
        if ($category->childs->isEmpty()) {
            foreach ($category->products as $product) {
                $this->addProducts($product);
            }
        }

        if (app()->environment() == 'testing') {
            $allCategoriesInMenu = Category::onlySuperParent()->onlyEnabled()->get();
        }

        $products = $this->paginate($this->allProducts->unique()->sortBy('sort_number'), 12);

        return view('categories.show', compact(
            'allCategories', 'category', 'products', 'allCategoriesInMenu',
            'superParentCategory'
        ));
    }

    /**
     * Add the products in the collection.
     *
     * @param   \IndianIra\Product  $product
     * @return  \Illuminate\Support\Collection
     */
    protected function addProducts($product)
    {
        if (! $this->allProducts->contains($product->code)) {
            $this->allProducts->push($product);
        }

        return $this->allProducts;
    }
}
