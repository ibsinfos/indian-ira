<?php

namespace IndianIra\Http\Controllers\Users;

use Illuminate\Http\Request;
use IndianIra\Http\Controllers\Controller;

class BillingAddressController extends Controller
{
    /**
     * Display the billing address edit form to the user.
     *
     * @return  \Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->billingAddress == null) {
            $user->billingAddress()->create();
        }

        $billingAddress = $user->billingAddress;

        return view('users.billing_address', compact(
            'user', 'billingAddress'
        ));
    }

    /**
     * Update the billing address of the user.
     *
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'address_line_1' => 'required|max:100|regex:/^[a-zA-Z0-9 \/_.,\-\']+$/',
            'address_line_2' => 'nullable|max:100|regex:/^[a-zA-Z0-9 \/_.,\-\']+$/',
            'area'           => 'required|max:100|regex:/^[a-zA-Z0-9 \/_.,\-\']+$/',
            'landmark'       => 'nullable|max:100|regex:/^[a-zA-Z0-9 \/_.,\-\']+$/',
            'city'           => 'required|max:100|regex:/^[a-zA-Z0-9 \/_.,\-\']+$/',
            'pin_code'       => 'required|max:100|regex:/^[a-zA-Z0-9 \/_.,\-\']+$/',
            'state'          => 'required|max:100|regex:/^[a-zA-Z0-9 \/_.,\-\']+$/',
            'country'        => 'required|max:100|regex:/^[a-zA-Z0-9 \/_.,\-\']+$/',
        ], [
            'address_line_1.regex' => 'The address line 1 has got invalid characters.',
            'address_line_2.regex' => 'The address line 2 has got invalid characters.',
            'area.regex'           => 'The area has got invalid characters.',
            'landmark.regex'       => 'The landmark has got invalid characters.',
            'pin_code.regex'       => 'The pin code has got invalid characters.',
            'city.regex'           => 'The city has got invalid characters.',
            'state.regex'          => 'The state has got invalid characters.',
            'country.regex'        => 'The country has got invalid characters.',
        ]);

        $user = auth()->user();

        $user->billingAddress->update($request->all());

        return response([
            'status'   => 'success',
            'title'    => 'Success !',
            'delay'    => 3000,
            'message'  => 'Billing Address updated successfully! Reloading...',
            'location' => route('users.billingAddress')
        ]);
    }
}
