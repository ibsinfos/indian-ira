<?php

namespace IndianIra\Http\Controllers\Users;

use IndianIra\Product;
use Illuminate\Http\Request;
use IndianIra\Http\Controllers\Controller;

class WishListController extends Controller
{
    /**
     * Display all the products that are added in wishlist of the
     * authenticated user.
     *
     * @return  \Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();

        $productsInWishlst = $user->wishlist->sortByDesc('id');

        return view('users.wishlist.index', compact('user', 'productsInWishlst'));
    }

    /**
     * Store the product of the given product code in the user's wishlist.
     *
     * @param   string  $productCode
     * @return  \Illuminate\Support\Facades\Response
     */
    public function store($productCode)
    {
        $user = auth()->user();

        $product = Product::onlyEnabled()->whereCode($productCode)->first();

        if ($product->existsInWishlist($user)) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'delay'   => 3000,
                'message' => 'Product already exists in your wishlist',
            ]);
        }

        $user->wishlist()->create([
            'product_id'       => $product->id,
            'product_name'     => $product->name,
            'product_code'     => $product->code,
            'product_image'    => $product->cartImage(),
            'product_page_url' => $product->canonicalPageUrl(),
        ]);

        return response([
            'status'  => 'success',
            'title'   => 'Success !',
            'delay'   => 3000,
            'message' => 'Product added successfully in your wishlist ! Redirecting...',
            'location' => route('users.wishlist')
        ]);
    }

    /**
     * Remove the product of the given product id in the user's wishlist.
     *
     * @param   string  $productId
     * @return  \Illuminate\Support\Facades\Response
     */
    public function delete($productId)
    {
        $user = auth()->user();

        $productInWishlist = $user->wishlist()->where('product_id', $productId)->first();

        if (! $productInWishlist) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'delay'   => 3000,
                'message' => 'Product does not exists in your wishlist',
            ]);
        }

        $productInWishlist->delete();

        $productsInWishlst = $user->wishlist->sortByDesc('id');

        return response([
            'status'     => 'success',
            'title'      => 'Success !',
            'delay'      => 3000,
            'message'    => 'Product removed successfully in your wishlist !',
            'location'   => route('users.wishlist'),
            'htmlResult' => view('users.wishlist.table', compact('productsInWishlst'))->render()
        ]);
    }
}
