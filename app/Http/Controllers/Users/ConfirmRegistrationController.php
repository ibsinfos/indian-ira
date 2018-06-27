<?php

namespace IndianIra\Http\Controllers\Users;

use IndianIra\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use IndianIra\Http\Controllers\Controller;
use IndianIra\Mail\Users\ConfirmRegistration;
use IndianIra\Mail\Users\RegistrationSuccessful;

class ConfirmRegistrationController extends Controller
{
    /**
     * Display the confirm registration page.
     *
     * @return  \Illuminate\View\View
     */
    public function show()
    {
        return view('users.confirm_registration');
    }

    /**
     * Mark the user as successfuly verified.
     *
     * @param   string  $token
     * @return  \Illuminate\Http\RedirectResponse
     */
    public function update($token)
    {
        $user = User::whereVerificationToken($token)->first();
        if ($user == null) return redirect('/');

        $user->update([
            'password'           => bcrypt($user->password),
            'is_verified'        => true,
            'verified_on'        => \Carbon\Carbon::now(),
            'verification_token' => null
        ]);

        $user = $user->fresh();

        Mail::to($user->email, $user->getFullName())
             ->send(new RegistrationSuccessful($user));

        session()->flash(
            'successfullyConfirmed', 'You have successfully confirmed your E-Mail address. Please login.'
        );

        session()->forget(['clickedResendConfirmLinkButton', 'registeredUser']);

        return redirect(route('users.login'));
    }

    /**
     * Resend the confirmation email to the user.
     *
     * @return  \Illuminate\View\View
     */
    public function resend()
    {
        $user = session('registeredUser');
        if (! $user) abort(404);

        if (! session('clickedResendConfirmLinkButton')) {
            $user->update([
                'is_verified' => false,
                'verified_on' => null,
                'verification_token' => str_random(60),
            ]);

            $user = $user->fresh();

            session(['clickedResendConfirmLinkButton' => true]);

            Mail::to($user)
                ->send(new ConfirmRegistration($user));
        }

        return view('users.confirm_registration');
    }
}
