<?php

namespace IndianIra\Http\Controllers\Users\Settings;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use IndianIra\Http\Controllers\Controller;

class ChangePasswordController extends Controller
{
    /**
     * Display the change password form.
     *
     * @return  \Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();

        return view('users.settings.change_password', compact('user'));
    }

    /**
     * Update the password of the user.
     *
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'current_password'    => 'required',
            'new_password'        => 'required',
            'repeat_new_password' => 'required|same:new_password',
        ]);

        $user = auth()->user();

        if (Hash::check($request->current_password, $user->password)) {
            $user->update([
                'password' => bcrypt($request->new_password)
            ]);

            return response([
                'status'   => 'success',
                'title'    => 'Success !',
                'delay'    => 3000,
                'message'  => 'Password updated successfully! Reloading...',
                'location' => route('users.settings.password')
            ]);
        }

        return response([
            'status'  => 'failed',
            'title'   => 'Failed !',
            'delay'   => 3000,
            'message' => 'Invalid Current Password',
        ]);
    }
}
