<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(\IndianIra\User::class)->create([
            'first_name' => 'Super',
            'last_name'  => 'Administrator',
            'username'   => 'admin',
            'email'      => 'admin@example.com',
            'password'   => bcrypt('Password'),
        ]);
    }
}
