<?php

namespace IndianIra\Http\Controllers;

use IndianIra\Tag;
use Illuminate\Http\Request;
use IndianIra\Utilities\PaginateCollection;

class TagsController extends Controller
{
    use PaginateCollection;

    /**
     * Display all the tags.
     *
     * @return  \Illuminate\View\View
     */
    public function index()
    {
        $tags = Tag::orderBy('name')->get();

        return view('tags.index', compact('tags'));
    }

    /**
     * Display the products of the given tag slug.
     *
     * @param   string  $slug
     * @return  void|\Illuminate\View\View
     */
    public function show($slug)
    {
        $tag = Tag::with(['products'])->whereSlug($slug)->get()->last();
        if (! $tag) {
            abort(404);
        }

        $products = $tag->products->where('display', 'Enabled');
        $products = $this->paginate($products->sortBy('sort_number'), 12);

        return view('tags.show', compact('tag', 'products'));
    }

    /**
     * Display the product from the given tag slug and product code.
     *
     * @param   string  $tagSlug
     * @param   string  $productCode
     * @return  \Illuminate\View\View
     */
    public function product($tagSlug, $productCode)
    {
        $tag = Tag::with(['products'])->whereSlug($tagSlug)->get()->last();
        if (! $tag) {
            abort(404);
        }

        $product = $tag->products->where('display', 'Enabled')->where('code', $productCode)->first();
        if (! $product) {
            abort(404);
        }

        $relatedProducts = $product->interRelated->shuffle();

        return view('tags.product', compact('tag', 'product', 'relatedProducts'));
    }
}
