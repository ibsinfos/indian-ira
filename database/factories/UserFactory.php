<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(IndianIra\User::class, function (Faker $faker) {
    return [
        'first_name'     => $faker->firstName,
        'last_name'      => $faker->lastName,
        'username'       => $faker->userName,
        'email'          => $faker->unique()->safeEmail,
        'password'       => 'Password',
        'remember_token' => str_random(10),
    ];
});

$factory->define(IndianIra\GlobalSettingBankDetail::class, function (Faker $faker) {
    return [
        'account_holder_name'  => config('app.name'),
        'account_type'         => array_random(['Savings', 'Current']),
        'account_number'       => $faker->bankAccountNumber,
        'bank_name'            => $faker->company,
        'bank_branch_and_city' => $faker->address,
        'bank_ifsc_code'       => implode('', $faker->randomElements(array_merge(range('A', 'Z'), range(0, 9)), 11)),
    ];
});

