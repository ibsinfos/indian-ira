<?php

namespace IndianIra\Http\Controllers\Users;

use Illuminate\Http\Request;
use IndianIra\ForgotPassword;
use Illuminate\Support\Facades\Mail;
use IndianIra\Mail\Users\ResetPassword;
use IndianIra\Http\Controllers\Controller;

class ForgotPasswordController extends Controller
{
    /**
     * Display the forgot password form.
     *
     * @return  \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index()
    {
        if (auth()->check()) {
            return redirect(route('users.dashboard'));
        }

        return view('users.forgot_password');
    }

    /**
     * Register the Forogot Password and Mail them the details.
     *
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
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
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'That email does not exists in our system.'
        ]);

        $forgotPassword = ForgotPassword::create([
            'email'      => $request->email,
            'token'      => str_random(60),
            'expires_on' => \Carbon\Carbon::now()->addHour()
        ]);

        session(['forgotPassword' => $forgotPassword]);

        Mail::to($forgotPassword->email)
            ->send(new ResetPassword($forgotPassword));

        return response([
            'status'   => 'success',
            'title'    => 'Success !',
            'delay'    => 3000,
            'message'  => 'We have sent an E-Mail to the given mail address to reset your password',
            'location' => route('users.forgotPassword'),
        ]);
    }
}
