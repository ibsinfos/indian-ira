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

$factory->define(IndianIra\GlobalSettingPaymentOption::class, function (Faker $faker) {
    return [
        'chosen' => 'online; offline; cod',
    ];
});

$factory->define(IndianIra\GlobalSettingCodCharge::class, function (Faker $faker) {
    return [
        'amount' => '50.00',
    ];
});

$factory->define(IndianIra\ShippingRate::class, function (Faker $faker) {
    return [
        'shipping_company_name'         => $faker->company . ' ' . $faker->companySuffix,
        'shipping_company_tracking_url' => $faker->url,
        'weight_from'                   => $faker->randomFloat(2),
        'weight_to'                     => $faker->randomFloat(2),
        'amount'                        => $faker->randomFloat(2),
    ];
});

$factory->define(IndianIra\Tag::class, function (Faker $faker) {
    return [
        'name'              => implode(' ', $faker->words(5)),
        'slug'              => 'the-simple-tag',
        'short_description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptates perferendis neque suscipit soluta quasi odio ratione voluptate, quo provident maiores sapiente laborum dolorem, cupiditate eos cumque illo sunt commodi temporibus.',
        'meta_title'        => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.',
        'meta_description'  => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur corporis saepe accusantium sit. Dicta explicabo sapiente error neque iste nihil ipsa!',
        'meta_keywords'     => implode(', ', $faker->words(5)),
    ];
});

