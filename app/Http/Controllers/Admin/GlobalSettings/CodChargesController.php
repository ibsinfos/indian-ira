<?php

namespace IndianIra\Http\Controllers\Admin\GlobalSettings;

use Illuminate\Http\Request;
use IndianIra\GlobalSettingCodCharge;
use IndianIra\Http\Controllers\Controller;

class CodChargesController extends Controller
{
    /**
     * Display the Global Settings Bank Details Page.
     *
     * @return  \Illuminate\View\View
     */
    public function index()
    {
        $codCharges = GlobalSettingCodCharge::first();

        return view('admin.global-settings.cod-charges', compact('codCharges'));
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
            'amount' => 'required|regex:/^\d+(\.(\d{0,2}))?$/',
        ], [
            'amount.regex' => 'The cod charges amount field should contain only numeric values with optional 2 digits after decimal.'
        ]);

        if (GlobalSettingCodCharge::first() == null) {
            GlobalSettingCodCharge::create($request->all());
        } else {
            GlobalSettingCodCharge::first()->update($request->all());
        }

        return response([
            'status'   => 'success',
            'title'    => 'Success !',
            'delay'    => 3000,
            'message'  => 'COD Charges updated successfully! Redirecting...',
            'location' => route('admin.dashboard')
        ]);
    }
}
