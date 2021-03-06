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
            'product_total' => $option != null ? (float) $option->selling_price : 0.00,
        ]);

        self::updateTotalAmounts();

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

        $cart = $cart->map(function ($c, $productOrOptCode) use ($code) {
            $qty = $c['quantity'];

            if ($productOrOptCode == $code) {
                $qty = ++$c['quantity'];
            }

            $c['product_total'] = ($c['selling_price'] * $qty);

            return $c;
        });

        self::updateTotalAmounts();

        return $cart;
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

        $cart = $cart->map(function ($c, $key) use ($code, $quantity) {
            $qty = $c['quantity'];

            if ($code == $key) {
                $qty = $c['quantity'] = $quantity;
            }

            $c['product_total'] = ($c['selling_price'] * $qty);

            return $c;
        });

        self::updateTotalAmounts();

        return $cart;
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

        self::updateTotalAmounts();

        return $cart;
    }

    /**
     * Empty the cart session.
     *
     * @return  void
     */
    public static function empty()
    {
        session()->forget([
            'cart', 'cartTotalAmounts', 'appliedDiscount',
            'shippingRateRecord', 'shippingCompany', 'codCharges',
        ]);
    }

    /**
     * Get the total quantity of all the products in the cart.
     *
     * @return  integer
     */
    public static function totalProducts()
    {
        $cart = session('cart', collect());

        if ($cart->isNotEmpty()) {
            return $cart->sum('quantity');
        }

        return 0;
    }

    /**
     * Get the net amount of the product for the given index.
     *
     * @param   string  $code
     * @return  float
     */
    public static function netAmount($code)
    {
        $cart = session('cart', collect());
        $totalNet = 0.0;

        foreach ($cart as $key => $row) {
            if ($key == $code) {
                $sellingPrice = $row['options']->selling_price;

                if ($row['options']->discount_price > 0.0) {
                    $sellingPrice = $row['options']->discount_price;
                }

                $totalNet += ($sellingPrice / (1 + ($row['product']->gst_percent / 100)));
            }
        }

        return (float) round($totalNet, 2);
    }

    /**
     * Get the total net amount of the products in the cart.
     *
     * @return  float
     */
    public static function totalNetAmount()
    {
        $cart = session('cart', collect());
        $totalNet = 0.0;

        foreach ($cart as $row) {
            $sellingPrice = $row['options']->selling_price;

            if ($row['options']->discount_price > 0.0) {
                $sellingPrice = $row['options']->discount_price;
            }

            $netPrice = ($sellingPrice / (1 + ($row['product']->gst_percent / 100)));

            $totalNet += ($netPrice * $row['quantity']);
        }

        return $totalNet;
    }

    /**
     * Get the gst amount of the product for the given index.
     *
     * @param   string  $code
     * @return  float
     */
    public static function gstAmount($code)
    {
        $cart = session('cart', collect());
        $totalGst = 0.0;

        foreach ($cart as $key => $row) {
            if ($key == $code) {
                $sellingPrice = $row['options']->selling_price;

                if ($row['options']->discount_price > 0.0) {
                    $sellingPrice = $row['options']->discount_price;
                }

                $totalGst += ($sellingPrice - self::netAmount($code));
            }
        }

        return (float) round($totalGst, 2);
    }

    /**
     * Get the total GST amount of the products in the cart.
     *
     * @return  float
     */
    public static function totalGstAmount()
    {
        $cart = session('cart', collect());
        $totalGst = 0.0;

        foreach ($cart as $row) {
            $sellingPrice = $row['options']->selling_price;

            if ($row['options']->discount_price > 0.0) {
                $sellingPrice = $row['options']->discount_price;
            }

            $netPrice = ($sellingPrice / (1 + ($row['product']->gst_percent / 100)));

            $gstAmount = $sellingPrice - $netPrice;

            $totalGst += ($gstAmount * $row['quantity']);
        }

        return $totalGst;
    }

    /**
     * Get the grand total amount of the products in the cart.
     *
     * @return  float
     */
    public static function grandTotalAmount()
    {
        return self::totalNetAmount() + self::totalGstAmount();
    }

    /**
     * Get the total shipping amount of the products in the cart.
     *
     * @return  float
     */
    public static function totalShippingAmount()
    {
        $cart = session('cart', collect());
        $totalShippingAmount = $totalWeight = 0.0;

        foreach ($cart as $code => $row) {
            $totalWeight += ($row['options']->weight * $row['quantity']);
        }

        $shippingRate = null;

        if (session('shippingRateRecord')) {
            $shippingRate = \IndianIra\ShippingRate::whereRaw('? between weight_from and weight_to', [$totalWeight])
                             ->whereLocationName(session('shippingRateRecord')->location_name)
                             ->first();
        }

        if ($shippingRate) {
            session(['shippingCompany' => $shippingRate]);

            return $shippingRate->amount;
        }

        return 0.0;
    }

    /**
     * Add cod charges to the cart total payable amount.
     *
     * @return  float
     */
    public static function codCharges()
    {
        if (session('codCharges')) {
            return session('codCharges')->amount;
        }

        return 0.00;
    }

    /**
     * Get the total payable amount of the products in the cart,
     * excluding the coupon discount.
     *
     * @return  float
     */
    public static function totalWithoutCouponDiscount()
    {
        return self::grandTotalAmount() + self::totalShippingAmount() + self::codCharges();
    }

    /**
     * Get the total payable amount of the products in the cart after
     * applying the coupon code discount.
     *
     * @return  float
     */
    public static function totalPayableAmount()
    {
        $actualPayable = self::totalWithoutCouponDiscount();

        $discount = 0.00;

        if (session('appliedDiscount')) {
            $discount = session('appliedDiscount')['amount'];
        }

        return ($actualPayable - $discount);
    }

    /**
     * Update all the total amounts of the cart.
     *
     * @return  void
     */
    public static function updateTotalAmounts()
    {
        $discountAmount = 0.0;

        if (session('appliedDiscount')) {
            $coupon = session('appliedDiscount')['coupon'];

            $grandTotalWithShipping = self::totalWithoutCouponDiscount();

            $discountAmount = ((float) $grandTotalWithShipping * ($coupon->discount_percent / 100));

            session(['appliedDiscount' => [
                'coupon' => $coupon,
                'amount' => $discountAmount
            ]]);
        }

        session()->forget('cartTotalAmounts');

        session(['cartTotalAmounts' => [
            'netAmount'              => (float) round(self::totalNetAmount(), 2),
            'gstAmount'              => (float) round(self::totalGstAmount(), 2),
            'grandTotal'             => (float) round(self::grandTotalAmount(), 2),
            'shipping'               => (float) round(self::totalShippingAmount(), 2),
            'grandTotalWithShipping' => (float) round(self::totalWithoutCouponDiscount(), 2),
            'couponDiscount'         => (float) round($discountAmount, 2),
            'totalPayable'           => (float) round(self::totalPayableAmount() - $discountAmount, 2),
        ]]);
    }
}
