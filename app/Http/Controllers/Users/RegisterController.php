<?php

namespace IndianIra\Http\Controllers\Users;

use IndianIra\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use IndianIra\Http\Controllers\Controller;
use IndianIra\Mail\Users\ConfirmRegistration;

class RegisterController extends Controller
{
    /**
     * Display the User Registration form.
     *
     * @return  \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index()
    {
        if (auth()->check()) {
            return redirect(route('homePage'));
        }

        return view('users.register');
    }

    /**
     * Register the User and Mail them the details.
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
            'first_name'       => 'required|max:100',
            'last_name'        => 'required|max:100',
            'username'         => 'required|alpha_dash|max:50|unique:users,username',
            'email'            => 'required|email|unique:users,email',
            'password'         => 'required',
            'confirm_password' => 'required|same:password',
            'contact_number'   => 'nullable|numeric'
        ]);

        $request['verification_token'] = str_random(60);
        $user = User::create($request->all());

        session(['registeredUser' => $user]);

        Mail::to($user->email, $user->getFullName())
            ->send(new ConfirmRegistration($user));

        return response([
            'status'   => 'success',
            'title'    => 'Success !',
            'delay'    => 3000,
            'message'  => 'You have successfully registered.. Redirecting...',
            'location' => route('users.showConfirmRegistrationPage'),
        ]);
    }
}
