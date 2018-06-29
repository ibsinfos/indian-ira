<?php

namespace IndianIra\Http\Controllers\Users\Settings;

use Illuminate\Http\Request;
use IndianIra\Http\Controllers\Controller;

class GeneralSettingsController extends Controller
{
    /**
     * Display the billing address edit form to the user.
     *
     * @return  \Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();

        return view('users.settings.general', compact('user'));
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
            'first_name'     => 'required|max:100',
            'last_name'      => 'required|max:100',
            'contact_number' => 'nullable|max:50',
        ]);

        $user = auth()->user();

        $user->update([
            'first_name'     => $request->first_name,
            'last_name'      => $request->last_name,
            'contact_number' => $request->contact_number,
        ]);

        return response([
            'status'   => 'success',
            'title'    => 'Success !',
            'delay'    => 3000,
            'message'  => 'General Details updated successfully! Reloading...',
            'location' => route('users.settings.general')
        ]);
    }
}
