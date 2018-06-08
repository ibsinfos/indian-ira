<?php

namespace IndianIra\Utilities;

use Illuminate\Http\Request;

trait ProcessLoginCredentials
{
    /**
     * Process the login credentials.
     *
     * @param   \Illuminate\Http\Request  $request
     * @return  boolean
     */
    public function processCredentials(Request $request, $forAdmin = false)
    {
        $field = filter_var($request->usernameOrEmail, FILTER_VALIDATE_EMAIL)
                    ? 'email'
                    : 'username';

        $request->merge([$field => $request->usernameOrEmail]);

        if ($forAdmin == true) {
            if (auth()->attempt($request->only($field, 'password') + ['id' => 1])) {
                return true;
            }

            return false;
        }

        if (auth()->attempt($request->only($field, 'password'))) {
            return true;
        }

        return false;
    }

    /**
     * Process the credentials of Super Administrator
     *
     * @param   \Illuminate\Http\Request  $request
     * @return  boolean
     */
    protected function processCredentialsForAdmin(Request $request)
    {
        return $this->processCredentials($request, true);
    }
}
