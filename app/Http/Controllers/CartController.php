<?php

namespace IndianIra\Http\Controllers;

use IndianIra\Product;
use Illuminate\Http\Request;
use IndianIra\Utilities\Cart;

class CartController extends Controller
{
    /**
     * Add product of the given product code in the cart.
     *
     * @param   string  $productCode
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function add($productCode, $optionCode = null)
    {
        $product = Product::whereCode($productCode)->whereDisplay('Enabled')->first();
        if (! $product) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'delay'   => 3000,
                'message' => 'Product with that code cannot be found!'
            ]);
        }

        $cart = session('cart', collect());

        $code = $optionCode != null ? $optionCode : $product->code;

        if ($cart->isEmpty() || (! $cart->has($code) && session('cart') != null)) {
            $cart = Cart::add($product, $optionCode);
        } elseif ($cart->has($code) && session('cart') != null) {
            $cart = Cart::increaseQty($product, $optionCode);

            if ($cart == false) {
                return response([
                    'status'  => 'failed',
                    'title'   => 'Failed !',
                    'delay'   => 3000,
                    'message' => 'You have already added the maximum quantity available of this product.'
                ]);
            }
        }

        session(['cart' => $cart]);

        $message = $optionCode != null
            ? 'Product: ' . $product->name . ' with option code ' . $optionCode . ' added successfully in the cart.'
            : 'Product: ' . $product->name . ' with code ' . $product->code . ' added successfully in the cart.';

        return response([
            'status'  => 'success',
            'title'   => 'Success !',
            'delay'   => 3000,
            'message' => $message
        ]);
    }

    /**
     * Update the product's quantity for the given product code in the cart.
     *
     * @param   string  $productCode
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function updateQty($code, Request $request)
    {
        $this->validate($request, [
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::updateQty($code, $request->quantity);
        if ($cart == false) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'delay'   => 3000,
                'message' => 'You have already added the maximum quantity available of this product.'
            ]);
        }

        session(['cart' => $cart]);

        return response([
            'status'  => 'success',
            'title'   => 'Success !',
            'delay'   => 3000,
            'message' => 'Product updated successfully!'
        ]);
    }

    /**
     * Remove the product from the cart.
     *
     * @param   string  $productCode
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function remove($productCode)
    {
        $cart = session('cart', collect());
        if (! $cart->has($productCode)) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'delay'   => 3000,
                'message' => 'Product with that code cannot be found!'
            ]);
        }

        $cart = Cart::remove($productCode);

        session(['cart' => $cart]);

        return response([
            'status'  => 'success',
            'title'   => 'Success !',
            'delay'   => 3000,
            'message' => 'Product removed successfully...'
        ]);
    }

    /**
     * Empty the cart.
     *
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function empty()
    {
        Cart::empty();

        return response([
            'status'  => 'success',
            'title'   => 'Success !',
            'delay'   => 3000,
            'message' => 'Cart emptied successfully! Redirecting...',
            'location' => route('homePage')
        ]);
    }
}
