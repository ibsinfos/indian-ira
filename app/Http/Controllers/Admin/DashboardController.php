<?php

namespace IndianIra\Http\Controllers\Admin;

use Illuminate\Http\Request;
use IndianIra\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return  \Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();

        return view('admin.dashboard', compact('user'));
    }


    /**
     * Logout the super administrator.
     *
     * @return  \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        if (auth()->check()) {
            auth()->logout();
        }

        return redirect(route('homePage'));
    }
}
