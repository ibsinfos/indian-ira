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

        return view('checkout.single_page', compact('user', 'billingAddress'));
    }
}
