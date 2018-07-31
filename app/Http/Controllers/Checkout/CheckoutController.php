<?php

namespace IndianIra\Http\Controllers\Checkout;

use Illuminate\Http\Request;
use IndianIra\Http\Controllers\Controller;

class CheckoutController extends Controller
{
    /**
     * Display the authentication page if user is guest else
     * redirect them to checkout's billing and shipping address page.
     *
     * @return  \Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function index()
    {
        if (auth()->check()) {
            return redirect(route('checkout.address'));
        }

        return view('checkout.authentication');
    }

    public function singlePage()
    {
        $user = auth()->user();

        $billingAddress = $user->billingAddress;

        $cart = session('cart', collect());

        $paymentMethods = explode('; ', \IndianIra\GlobalSettingPaymentOption::first()->chosen);

        return view('checkout.single_page', compact('user', 'billingAddress', 'cart', 'paymentMethods'));
    }

    /**
     * Add Cash On Delivery charges in the total cart payable amount.
     *
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function addCodCharges()
    {
        session()->forget('codCharges');

        $codCharges = 0.00;

        if (request()->payment_method == 'cod') {
            $codCharges = \IndianIra\GlobalSettingCodCharge::first();

            session(['codCharges' => $codCharges]);
        }

        $cart = session('cart', collect());

        return response([
            'status'     => 'success',
            'htmlResult' => view('checkout._confirm_cart_table', compact('cart'))->render()
        ]);
    }
}
