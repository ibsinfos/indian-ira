<?php

namespace IndianIra\Http\Controllers\Admin;

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
        if (auth()->check() && auth()->id() == 1) {
            return redirect(route('admin.dashboard'));
        }

        return view('admin.login');
    }

    /**
     * Login the administrator after validation.
     *
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function postLogin(Request $request)
    {
        if (auth()->check() && auth()->id() == 1) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'message' => 'You are already logged in...',
                'location' => route('admin.dashboard'),
            ]);
        }

        $this->validate($request, [
            'usernameOrEmail' => 'required',
            'password'        => 'required'
        ]);

        $loggedIn = $this->processCredentialsForAdmin($request);

        if ($loggedIn) {
            return response([
                'status'   => 'success',
                'title'    => 'Success !',
                'message'  => 'Logged in successfully. Redirecting...',
                'location' => route('admin.dashboard')
            ]);
        }

        return response([
            'status'  => 'failed',
            'title'   => 'Failed !',
            'message' => 'Invalid Credentials'
        ]);
    }
}
