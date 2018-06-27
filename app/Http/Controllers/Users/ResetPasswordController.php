<?php

namespace IndianIra\Http\Controllers\Users;

use Illuminate\Http\Request;
use IndianIra\ForgotPassword;
use Illuminate\Support\Facades\Mail;
use IndianIra\Mail\Users\ResetPassword;
use IndianIra\Http\Controllers\Controller;

class ResetPasswordController extends Controller
{
    /**
     * Display the reset password form.
     *
     * @return  \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit($token)
    {
        if (auth()->check()) {
            return redirect(route('users.dashboard'));
        }

        $exists = ForgotPassword::whereToken($token)->first();
        if (! $exists) {
            abort(404);
        }

        return view('users.reset_password', compact('token'));
    }

    /**
     * Register the Forogot Password and Mail them the details.
     *
     * @param   string  $token
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function update($token, Request $request)
    {
        if (auth()->check()) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'delay'   => 3000,
                'message' => 'You are already logged in.'
            ]);
        }

        $this->validate($request, [
            'new_password' => 'required',
            'repeat_new_password' => 'required|same:new_password',
        ]);

        $password = ForgotPassword::whereToken($token)->first();
        if (! $password) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'delay'   => 3000,
                'message' => 'Invalid token'
            ]);
        }

        \IndianIra\User::whereEmail($password->email)->first()->update([
            'password' => bcrypt($request->new_password)
        ]);

        $password->delete();

        return response([
            'status'   => 'success',
            'title'    => 'Success !',
            'delay'    => 3000,
            'message'  => 'Password updated successfully. Redirecting to login page...',
            'location' => route('users.login'),
        ]);
    }
}
