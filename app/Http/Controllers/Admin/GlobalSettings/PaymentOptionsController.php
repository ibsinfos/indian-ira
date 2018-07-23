<?php

namespace IndianIra\Http\Controllers\Admin\GlobalSettings;

use Illuminate\Http\Request;
use IndianIra\GlobalSettingPaymentOption;
use IndianIra\Http\Controllers\Controller;

class PaymentOptionsController extends Controller
{
    /**
     * Display the Global Settings Bank Details Page.
     *
     * @return  \Illuminate\View\View
     */
    public function index()
    {
        $paymentOptions = GlobalSettingPaymentOption::first();

        $alreadyChosen = [];
        if ($paymentOptions) {
            $alreadyChosen = explode('; ', $paymentOptions->chosen);
        }

        return view('admin.global-settings.payment-options', compact('paymentOptions', 'alreadyChosen'));
    }

    /**
     * Update the global settings payment options details.
     *
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'chosen'                => 'required',
            'other_payment_options' => 'nullable|max:200'
        ]);

        if (! is_array($request->chosen)) {
            $request['chosen'] = implode('; ', [$request->chosen]);
        } else {
            $request['chosen'] = implode('; ', $request->chosen);
        }

        if (GlobalSettingPaymentOption::first() == null) {
            GlobalSettingPaymentOption::create($request->all());
        } else {
            GlobalSettingPaymentOption::first()->update($request->all());
        }

        return response([
            'status'   => 'success',
            'title'    => 'Success !',
            'delay'    => 3000,
            'message'  => 'Payment Options updated successfully! Redirecting...',
            'location' => route('admin.dashboard')
        ]);
    }
}
