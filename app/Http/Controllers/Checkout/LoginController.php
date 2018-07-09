<?php

namespace IndianIra\Http\Controllers\Checkout;

use Illuminate\Http\Request;
use IndianIra\Http\Controllers\Controller;
use IndianIra\Utilities\ProcessLoginCredentials;

class LoginController extends Controller
{
    use ProcessLoginCredentials;

    /**
     * Login the user after validation.
     *
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function postLogin(Request $request)
    {
        if (auth()->check()) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'message' => 'You are already logged in...',
                'location' => route('checkout.address'),
            ]);
        }

        $this->validate($request, [
            'usernameOrEmail' => 'required',
            'password'        => 'required'
        ]);

        $loggedIn = $this->processCredentials($request);

        if ($loggedIn) {
            return response([
                'status'   => 'success',
                'title'    => 'Success !',
                'message'  => 'Logged in successfully. Redirecting...',
                'location' => route('checkout.address')
            ]);
        }

        return response([
            'status'  => 'failed',
            'title'   => 'Failed !',
            'delay'   => 3000,
            'message' => 'Invalid Credentials'
        ]);
    }
}
