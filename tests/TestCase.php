<?php

namespace Tests;

use IndianIra\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Generate the Super Administrator for testing purpose.
     *
     * @return  \IndianIra\User
     */
    public function generateSuperAdministrator()
    {
        return factory(User::class)->create([
            'first_name'       => 'Super',
            'last_name'        => 'Administrator',
            'username'         => 'admin',
            'email'            => 'admin@example.com',
            'password'         => bcrypt('Password'),
        ]);
    }

    /**
     * Sign in as the super administrator.
     *
     * @return  void
     */
    public function signInSuperAdministrator($admin = null)
    {
        if ($admin == null) {
            $admin = $this->generateSuperAdministrator();
        }

        auth()->login($admin);
    }

    /**
     * Sign in as the user.
     *
     * @param   $user
     * @return  void
     */
    public function signInUser($user = null)
    {
        if ($user == null) {
            $user = factory(User::class)->create();
        }

        auth()->login($user->fresh());

        return $user->fresh();
    }
}
