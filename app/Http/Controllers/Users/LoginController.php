<?php

namespace IndianIra\Http\Controllers\Users;

use Illuminate\Http\Request;
use IndianIra\Http\Controllers\Controller;
use IndianIra\Utilities\ProcessLoginCredentials;

class LoginController extends Controller
{
    use ProcessLoginCredentials;

    /**
     * Display the login form.
     *
     * @return  \Illuminate\View\View
     */
    public function index()
    {
        if (auth()->check()) {
            return redirect(route('users.dashboard'));
        }

        return view('users.login');
    }

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
                'location' => route('users.dashboard'),
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
                'location' => route('users.dashboard')
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
