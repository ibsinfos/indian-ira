<?php

namespace IndianIra\Http\Controllers\Admin\GlobalSettings;

use Illuminate\Http\Request;
use IndianIra\GlobalSettingBankDetail;
use IndianIra\Http\Controllers\Controller;

class BankDetailsController extends Controller
{
    /**
     * Display the Global Settings Bank Details Page.
     *
     * @return  \Illuminate\View\View
     */
    public function index()
    {
        $bank = GlobalSettingBankDetail::first();

        return view('admin.global-settings.bank-details', compact('bank'));
    }

    /**
     * Update the global settings bank details.
     *
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'account_holder_name'  => 'required|max:200',
            'account_type'         => 'required|max:200',
            'account_number'       => 'required|alpha_num|max:50',
            'bank_name'            => 'required|max:200',
            'bank_branch_and_city' => 'required|max:200',
            'bank_ifsc_code'       => 'required|alpha_num|max:20',
        ]);

        $request['account_number'] = strtoupper($request->account_number);
        $request['bank_ifsc_code'] = strtoupper($request->bank_ifsc_code);

        if (GlobalSettingBankDetail::first() == null) {
            GlobalSettingBankDetail::create($request->all());
        } else {
            GlobalSettingBankDetail::first()->update($request->all());
        }

        return response([
            'status'   => 'success',
            'title'    => 'Success !',
            'delay'    => 3000,
            'message'  => 'Bank details updated successfully! Redirecting...',
            'location' => route('admin.dashboard')
        ]);
    }
}
