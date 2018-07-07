<?php

namespace IndianIra\Http\Controllers\Cart;

use IndianIra\Coupon;
use IndianIra\Product;
use Illuminate\Http\Request;
use IndianIra\Utilities\Cart;
use IndianIra\Http\Controllers\Controller;

class CouponsController extends Controller
{
    /**
     * Apply the coupon discount in the cart.
     *
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function apply(Request $request)
    {
        $this->validate($request, [
            'couponCode' => 'required|alpha_dash|exists:coupons,code'
        ], [
            'couponCode.exists' => 'That coupon code does not exists or might have expired.',
        ]);

        $coupon = Coupon::whereCode($request->couponCode)->first();

        $grandTotalWithShipping = Cart::totalWithoutCouponDiscount();

        $discountAmount = ((float) $grandTotalWithShipping * ($coupon->discount_percent / 100));

        session(['appliedDiscount' => [
            'coupon' => $coupon,
            'amount' => $discountAmount
        ]]);

        session(['cartTotalAmounts' => [
            'netAmount'              => (float) round(Cart::totalNetAmount(), 2),
            'gstAmount'              => (float) round(Cart::totalGstAmount(), 2),
            'grandTotal'             => (float) round(Cart::grandTotalAmount(), 2),
            'shipping'               => (float) round(Cart::totalShippingAmount(), 2),
            'grandTotalWithShipping' => (float) round(Cart::totalWithoutCouponDiscount(), 2),
            'couponDiscount'         => (float) round($discountAmount, 2),
            'totalPayable'           => (float) round(Cart::totalPayableAmount() - $discountAmount, 2),
        ]]);

        $cart = session('cart', collect());

        return response([
            'status'  => 'success',
            'title'   => 'Success !',
            'delay'   => 3000,
            'message' => 'Coupon Code applied successfully...',
            'htmlResult' => view('cart.table', compact('cart'))->render()
        ]);
    }

    /**
     * Remove the already applied coupon from the cart.
     *
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function remove()
    {
        session()->forget('appliedDiscount');

        $amounts = session('cartTotalAmounts');

        session(['cartTotalAmounts' => [
            'netAmount'              => (float) round($amounts['netAmount'], 2),
            'gstAmount'              => (float) round($amounts['gstAmount'], 2),
            'grandTotal'             => (float) round($amounts['grandTotal'], 2),
            'shipping'               => (float) round($amounts['shipping'], 2),
            'grandTotalWithShipping' => (float) round($amounts['grandTotalWithShipping'], 2),
            'couponDiscount'         => (float) round(0.00, 2),
            'totalPayable'           => (float) round($amounts['totalPayable'], 2),
        ]]);

        $cart = session('cart', collect());

        return response([
            'status'  => 'success',
            'title'   => 'Success !',
            'delay'   => 3000,
            'message' => 'Coupon Code removed successfully...',
            'htmlResult' => view('cart.table', compact('cart'))->render()
        ]);
    }
}
