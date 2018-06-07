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
}
