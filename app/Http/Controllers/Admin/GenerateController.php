<?php

namespace IndianIra\Http\Controllers\Admin;

use IndianIra\User;
use Illuminate\Http\Request;
use IndianIra\Mail\AdminGenerated;
use Illuminate\Support\Facades\Mail;
use IndianIra\Http\Controllers\Controller;

class GenerateController extends Controller
{
    /**
     * Display the Super Administrator generator form.
     *
     * @return  \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index()
    {
        if (cache('superAdminExists') == true || \IndianIra\User::first() != null) {
            return redirect(route('homePage'));
        }

        return view('admin.generate');
    }

    /**
     * Generate the Super Administrator and Mail them the details.
     *
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        if (cache('superAdminExists') == true || \IndianIra\User::first() != null) {
            return response([
                'status'   => 'failed',
                'title'    => 'Failed !',
                'delay'    => 3000,
                'message'  => 'Super Administrator already exists',
                'location' => route('homePage'),
            ]);
        }

        $this->validate($request, [
            'first_name'       => 'required|max:100',
            'last_name'        => 'required|max:100',
            'username'         => 'required|alpha_dash|max:50',
            'email'            => 'required|email',
            'password'         => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        cache()->flush();
        session()->flush();

        session(['password' => $request->password]);

        $request['password'] = bcrypt($request->password);
        $admin = User::create($request->all());

        cache()->rememberForever('superAdminExists', function () {
            return User::first() != null;
        });

        Mail::to($admin->email, $admin->getFullName())
            ->send(new AdminGenerated($admin));

        session()->forget('password');

        return response([
            'status'   => 'success',
            'title'    => 'Success !',
            'delay'    => 3000,
            'message'  => 'Super administrator generated successfully.. Redirecting...',
            'location' => route('homePage'),
        ]);
    }
}
