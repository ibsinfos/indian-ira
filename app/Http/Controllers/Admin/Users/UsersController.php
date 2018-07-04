<?php

namespace IndianIra\Http\Controllers\Admin\Users;

use IndianIra\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use IndianIra\Http\Controllers\Controller;
use IndianIra\Mail\Users\RegistrationSuccessful;

class UsersController extends Controller
{
    /**
     * Display all the users.
     *
     * @return  \Illuminate\View\View
     */
    public function index()
    {
        $users = $this->getAllUsers();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Display the edit form to update the user's details.
     *
     * @param   integer  $userId
     * @return  \Illuminate\View\View
     */
    public function edit($userId)
    {
        $user = $this->getAllUsers()->where('id', $userId)->first();

        if (! $user) {
            abort(404);
        }

        if (empty(request()->keys())) {
            return redirect(route('admin.users.edit', $user->id) . '?general-details');
        }

        $billingAddress = $user->billingAddress;

        return view('admin.users.edit', compact('user', 'billingAddress'));
    }

    /**
     * Verify the unverified user for the given user id.
     *
     * @param   integer  $userId
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function verify($userId)
    {
        $user = $this->getAllUsers()->where('id', $userId)->first();

        if (! $user) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'delay'   => 3000,
                'message' => 'User with that id cannot be found!',
            ]);
        }

        $user->update([
            'password'           => bcrypt($user->password),
            'is_verified'        => true,
            'verified_on'        => now(),
            'verification_token' => null
        ]);

        $user = $user->fresh();

        Mail::to($user->email, $user->getFullName())
             ->send(new RegistrationSuccessful($user));

        $user->billingAddress()->create();

        $users = $this->getAllUsers();

        return response([
            'status'  => 'success',
            'title'   => 'Success !',
            'delay'   => 3000,
            'message' => 'User verified successfully...',
            'htmlResult' => view('admin.users.table', compact('users'))->render()
        ]);
    }

    /**
     * Update the general details of the given user id.
     *
     * @param   integer  $userId
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function updateGeneral($userId, Request $request)
    {
        $user = $this->getAllUsers()->where('id', $userId)->first();

        if (! $user) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'delay'   => 3000,
                'message' => 'User with that id cannot be found!',
            ]);
        }

        $this->validate($request, [
            'first_name'     => 'required|max:100',
            'last_name'      => 'required|max:100',
            'username'       => 'required|alpha_dash|max:50|unique:users,username,' . $userId,
            'email'          => 'required|email|unique:users,email,' . $userId,
            'contact_number' => 'nullable|numeric',
        ]);

        $user->update($request->all());

        return response([
            'status'   => 'success',
            'title'    => 'Success !',
            'delay'    => 3000,
            'message'  => 'General details updated successfully! Reloading...',
            'location' => route('admin.users.edit', $user->id) . '?general-details'
        ]);
    }

    /**
     * Update the billing address of the given user id.
     *
     * @param   integer  $userId
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function updateBilling($userId, Request $request)
    {
        $user = $this->getAllUsers()->where('id', $userId)->first();

        if (! $user) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'delay'   => 3000,
                'message' => 'User with that id cannot be found!',
            ]);
        }

        $this->validate($request, [
            'address_line_1' => 'required|max:100|regex:/^[a-zA-Z0-9 \/_.,\-\']+$/',
            'address_line_2' => 'nullable|max:100|regex:/^[a-zA-Z0-9 \/_.,\-\']+$/',
            'area'           => 'required|max:100|regex:/^[a-zA-Z0-9 \/_.,\-\']+$/',
            'landmark'       => 'nullable|max:100|regex:/^[a-zA-Z0-9 \/_.,\-\']+$/',
            'city'           => 'required|max:100|regex:/^[a-zA-Z0-9 \/_.,\-\']+$/',
            'pin_code'       => 'required|max:100|regex:/^[a-zA-Z0-9 \/_.,\-\']+$/',
            'state'          => 'required|max:100|regex:/^[a-zA-Z0-9 \/_.,\-\']+$/',
            'country'        => 'required|max:100|regex:/^[a-zA-Z0-9 \/_.,\-\']+$/',
        ], [
            'address_line_1.regex' => 'The address line 1 has got invalid characters.',
            'address_line_2.regex' => 'The address line 2 has got invalid characters.',
            'area.regex'           => 'The area has got invalid characters.',
            'landmark.regex'       => 'The landmark has got invalid characters.',
            'pin_code.regex'       => 'The pin code has got invalid characters.',
            'city.regex'           => 'The city has got invalid characters.',
            'state.regex'          => 'The state has got invalid characters.',
            'country.regex'        => 'The country has got invalid characters.',
        ]);

        $user->billingAddress->update($request->all());

        return response([
            'status'   => 'success',
            'title'    => 'Success !',
            'delay'    => 3000,
            'message'  => 'Billing Address updated successfully! Reloading...',
            'location' => route('admin.users.edit', $user->id) . '?billing-address'
        ]);
    }

    /**
     * Update the password of the user.
     *
     * @param   \Illuminate\Http\Request  $request
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function updatePassword($userId, Request $request)
    {
        $user = $this->getAllUsers()->where('id', $userId)->first();

        if (! $user) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'delay'   => 3000,
                'message' => 'User with that id cannot be found!',
            ]);
        }

        $this->validate($request, [
            'new_password'        => 'required',
            'repeat_new_password' => 'required|same:new_password',
        ]);

        $user->update([
            'password' => bcrypt($request->new_password)
        ]);

        return response([
            'status'   => 'success',
            'title'    => 'Success !',
            'delay'    => 3000,
            'message'  => 'Password updated successfully! Reloading...',
            'location' => route('admin.users.edit', $user->id) . '?change-password'
        ]);
    }

    /**
     * Temporarily delete the user of the given user id.
     *
     * @param   integer  $userId
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function delete($userId)
    {
        $user = $this->getAllUsers()->where('id', $userId)->first();

        if (! $user) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'delay'   => 3000,
                'message' => 'User with that id cannot be found!',
            ]);
        }

        $user = $user->delete();

        $users = $this->getAllUsers();

        return response([
            'status'  => 'success',
            'title'   => 'Success !',
            'delay'   => 3000,
            'message' => 'User deleted temporarily!',
            'htmlResult' => view('admin.users.table', compact('users'))->render()
        ]);
    }

    /**
     * Restore the temporarily deleted user of the given user id.
     *
     * @param   integer  $userId
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function restore($userId)
    {
        $user = $this->getAllUsers()->where('id', $userId)->first();

        if (! $user) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'delay'   => 3000,
                'message' => 'User with that id cannot be found!',
            ]);
        }

        $user = $user->restore();

        $users = $this->getAllUsers();

        return response([
            'status'  => 'success',
            'title'   => 'Success !',
            'delay'   => 3000,
            'message' => 'User restored successfully!',
            'htmlResult' => view('admin.users.table', compact('users'))->render()
        ]);
    }

    /**
     * Permanently delete the temporarily deleted user of the given user id.
     *
     * @param   integer  $userId
     * @return  \Symfony\Component\HttpFoundation\Response
     */
    public function destroy($userId)
    {
        $user = $this->getAllUsers()->where('id', $userId)->first();

        if (! $user) {
            return response([
                'status'  => 'failed',
                'title'   => 'Failed !',
                'delay'   => 3000,
                'message' => 'User with that id cannot be found!',
            ]);
        }

        $user->billingAddress()->delete();

        $user = $user->forceDelete();

        $users = $this->getAllUsers();

        return response([
            'status'  => 'success',
            'title'   => 'Success !',
            'delay'   => 3000,
            'message' => 'User destroyed successfully!',
            'htmlResult' => view('admin.users.table', compact('users'))->render()
        ]);
    }

    /**
     * Get all the users.
     *
     * @return  \Illuminate\Database\Eloquent\Collection
     */
    protected function getAllUsers()
    {
        return User::withTrashed()
                    ->where('id', '<>', 1)
                    ->orderBy('id', 'DESC')
                    ->get();
    }
}
