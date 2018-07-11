<?php

namespace IndianIra\Http\Controllers\Cart;

use IndianIra\Product;
use IndianIra\ShippingRate;
use Illuminate\Http\Request;
use IndianIra\Utilities\Cart;
use IndianIra\Http\Controllers\Controller;

class CartController extends Controller
{
    /**
     * Display the cart page.
     *
     * @return  \Illuminate\View\View
     */
    public function show()
    {
        $cart = session('cart', collect());

        $shippingRatesCity    = ShippingRate::whereLocationType('City')
                                    ->orderBy('location_name')
                                    ->get()
                                    ->pluck('location_name')
                                    ->unique();

        $shippingRatesState   = ShippingRate::whereLocationType('State')
                                    ->orderBy('location_name')
                                    ->get()
                                    ->pluck('location_name')
                                    ->unique();

        $shippingRatesCountry = ShippingRate::whereLocationType('Country')
                                    ->orderBy('location_name')
                                    ->get()
                                    ->pluck('location_name')
                                    ->unique();

        return view('cart.index', compact(
            'cart', 'shippingRatesCity', 'shippingRatesState', 'shippingRatesCountry'
        ));
    }

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

        $this->updateCouponDiscountAmount();

        $message = $optionCode != null
            ? 'Product: ' . $product->name . ' with option code ' . $optionCode . ' added successfully in the cart.'
            : 'Product: ' . $product->name . ' with code ' . $product->code . ' added successfully in the cart.';

        return response([
            'status'  => 'success',
            'title'   => 'Success !',
            'delay'   => 3000,
            'message' => $message,
            'count'   => \IndianIra\Utilities\Cart::totalProducts(),
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

        $cart = session('cart', collect());

        $this->updateCouponDiscountAmount();

        return response([
            'status'  => 'success',
            'title'   => 'Success !',
            'delay'   => 3000,
            'message' => 'Product updated successfully!',
            'count'   => \IndianIra\Utilities\Cart::totalProducts(),
            'htmlResult' => view('cart.table', compact('cart'))->render()
        ]);
    }

    /**
     * Calculate the shipping amount based on the location provided.
     *
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function shippingAmount()
    {
        $request = request();

        session()->forget('shippingRateRecord');

        $shippingRate = ShippingRate::whereLocationName($request->location)->first();

        if ($shippingRate == null) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed',
                'delay'   => 3000,
                'message' => 'Shipping Rate with that Location name cannot be found!',
            ]);
        }

        session(['shippingRateRecord' => $shippingRate]);

        $cart = session('cart');

        return response([
            'status'     => 'success',
            'title'      => 'Success !',
            'delay'      => 3000,
            'message'    => 'Shipping Rate calculated successfully...',
            'count'      => \IndianIra\Utilities\Cart::totalProducts(),
            'htmlResult' => view('cart.table', compact('cart'))->render()
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

        $this->updateCouponDiscountAmount();

        $cart = session('cart', collect());

        return response([
            'status'  => 'success',
            'title'   => 'Success !',
            'delay'   => 3000,
            'message' => 'Product removed successfully...',
            'count'   => \IndianIra\Utilities\Cart::totalProducts(),
            'htmlResult' => view('cart.table', compact('cart'))->render()
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

        $cart = session('cart', collect());

        return response([
            'status'  => 'success',
            'title'   => 'Success !',
            'delay'   => 3000,
            'message' => 'Cart emptied successfully! Redirecting...',
            'count'   => \IndianIra\Utilities\Cart::totalProducts(),
            'htmlResult' => view('cart.table', compact('cart'))->render(),
            'location' => route('homePage')
        ]);
    }

    /**
     * Update the coupon discount amount if it exists in session.
     *
     * @return  void
     */
    protected function updateCouponDiscountAmount()
    {
        if (session('appliedDiscount')) {
            $coupon = session('appliedDiscount')['coupon'];

            $grandTotalWithShipping = \IndianIra\Utilities\Cart::totalWithoutCouponDiscount();

            $discountAmount = ((float) $grandTotalWithShipping * ($coupon->discount_percent / 100));

            session(['appliedDiscount' => [
                'coupon' => $coupon,
                'amount' => $discountAmount
            ]]);
        }
    }
}
