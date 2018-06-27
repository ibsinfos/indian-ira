<?php

namespace IndianIra\Http\Controllers\Users;

use Illuminate\Http\Request;
use IndianIra\Http\Controllers\Controller;
use IndianIra\Utilities\ProcessLoginCredentials;

class DashboardController extends Controller
{
    /**
     * Display the user's dashboard.
     *
     * @return  \Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();

        return view('users.dashboard', compact('user'));
    }


    /**
     * Logout the user.
     *
     * @return  \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        if (auth()->check()) {
            auth()->logout();
        }

        return redirect(route('users.login'));
    }}
