<?php

namespace IndianIra\Http\Controllers\Checkout;

use IndianIra\User;
use Illuminate\Http\Request;
use IndianIra\Http\Controllers\Controller;

class RegisterController extends Controller
{
    /**
     * Register the User
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
                'message' => 'You are already logged in...',
                'location' => route('checkout.address')
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

        $request['password'] = bcrypt($request->password);
        $request['is_verified'] = true;
        $request['verified_on'] = now();
        $user = User::create($request->all());

        auth()->login($user);

        return response([
            'status'   => 'success',
            'title'    => 'Success !',
            'delay'    => 3000,
            'message'  => 'You have successfully registered.. Redirecting...',
            'location' => route('checkout.address'),
        ]);
    }
}
