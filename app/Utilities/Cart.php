<?php

namespace IndianIra\Utilities;

class Cart
{
    /**
     * Add a product in the cart.
     *
     * @param  \IndianIra\Product  $product
     * @param  string  $optionCode
     */
    public static function add($product, $optionCode = null)
    {
        $cart = session('cart', collect());

        $option = $product->options->last();
        $key = $product->code;

        if ($optionCode != null) {
            $option = $product->options
                              ->where('display', 'Enabled')
                              ->where('option_code', $optionCode)
                              ->first();

            $key = $option->option_code;
        }

        $cart->put($key, [
            'product'       => $product,
            'options'       => $option,
            'quantity'      => 1,
            'selling_price' => $option != null ? (float) $option->selling_price : 0.00,
        ]);

        return $cart;
    }

    /**
     * Increase the quantity of the given product.
     *
     * @param   \IndianIra\Product  $product
     * @param   string  $optionCode
     * @return  boolean|\Illuminate\Support\Collection
     */
    public static function increaseQty($product, $optionCode = null)
    {
        $cart = session('cart', collect());

        $code = $optionCode != null ? $optionCode : $product->code;

        if (
            $cart->has($code) == false ||
            $cart->get($code)['quantity'] >= $cart->get($code)['options']->stock
        ) {
            return false;
        }

        return $cart->map(function ($c, $productOrOptCode) use ($code) {
            $qty = $c['quantity'];

            if ($productOrOptCode == $code) {
                $qty = ++$c['quantity'];
            }

            $c['selling_price'] *= $qty;

            return $c;
        });
    }

    /**
     * Update the quantity of the given code that exists as key
     * in the cart's session array.
     *
     * @param   string  $product
     * @param   integer  $quantity
     * @return  boolean|\Illuminate\Support\Collection
     */
    public static function updateQty($code, $quantity = 1)
    {
        $cart = session('cart');

        return $cart->map(function ($c, $key) use ($code, $quantity) {
            $qty = $c['quantity'];

            if ($code == $key) {
                $qty = $c['quantity'] = $quantity;
            }

            $c['selling_price'] *= $qty;

            return $c;
        });
    }

    /**
     * Remove the product code from the cart session array.
     *
     * @param   string  $code
     * @return  \Illuminate\Support\Collection
     */
    public static function remove($code)
    {
        $cart = session('cart');

        $cart = $cart->except($code);

        return $cart;
    }

    /**
     * Empty the cart session.
     *
     * @return  void
     */
    public static function empty()
    {
        session()->forget('cart');
    }
}
