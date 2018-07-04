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
        'first_name'         => $faker->firstName,
        'last_name'          => $faker->lastName,
        'username'           => substr(str_replace('.', '_', $faker->userName), 0, 50),
        'email'              => $faker->unique()->safeEmail,
        'password'           => bcrypt('Password'),
        'remember_token'     => str_random(10),
        'verification_token' => null,
        'is_verified'        => true,
        'verified_on'        => \Carbon\Carbon::now(),
        'contact_number'     => '9876543210'
    ];
});

$factory->define(IndianIra\UserBillingAddress::class, function (Faker $faker) {
    return [
        'user_id'        => factory(\IndianIra\User::class)->create()->id,
        'address_line_1' => $faker->buildingNumber . ' ' . $faker->streetName,
        'address_line_2' => $faker->citySuffix,
        'area'           => $faker->secondaryAddress,
        'landmark'       => $faker->streetSuffix,
        'city'           => $faker->city,
        'pin_code'       => array_random(range(100000, 999999)),
        'state'          => $faker->state,
        'country'        => $faker->country,
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
        'shipping_company_name'         => str_replace(['-', '_', '.'], [' ', ' ', ' '], $faker->company) . ' ' . $faker->companySuffix,
        'shipping_company_tracking_url' => substr($faker->url, 0, 200),
        'weight_from'                   => (float) round($faker->randomFloat(2, 1000, 9999), 2),
        'weight_to'                     => (float) round($faker->randomFloat(2, 1000, 9999), 2),
        'amount'                        => (float) round($faker->randomFloat(2, 1, 9999), 2),
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

$factory->define(IndianIra\Category::class, function (Faker $faker) {
    return [
        'parent_id'         => 0,
        'name'              => $faker->word,
        'slug'              => $faker->slug,
        'page_url'          => $faker->url,
        'display'           => 'Enabled',
        'display_text'      => title_case($faker->word),
        'short_description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptates perferendis neque suscipit soluta quasi odio ratione voluptate, quo provident maiores sapiente laborum dolorem, cupiditate eos cumque illo sunt commodi temporibus.',
        'meta_title'        => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.',
        'meta_description'  => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur corporis saepe accusantium sit. Dicta explicabo sapiente error neque iste nihil ipsa!',
        'meta_keywords'     => implode(', ', $faker->words(5)),
    ];
});

$factory->define(IndianIra\Product::class, function (Faker $faker) {
    return [
        // 'code'              => 'PRD-'. time() . '-' . rand(1000, 9999),
        'code'              => uniqid().mt_rand(1, 99999).time(),
        'name'              => implode(' ', $faker->words(5)),
        'gst_percent'       => 18,
        'display'           => array_random(['Enabled', 'Disabled']),
        'number_of_options' => array_random([1, 2, 3]),

        'description'       => $faker->paragraph(3, true),
        'additional_notes'  => $faker->paragraph(3, true),
        'terms'             => $faker->paragraph(3, true),

        'meta_title'        => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.',
        'meta_description'  => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur corporis saepe accusantium sit. Dicta explicabo sapiente error neque iste nihil ipsa!',
        'meta_keywords'     => implode(', ', $faker->words(5)),

        'images'            => '/images-products/image-cart.jpg; /images-products/image-catalog.jpg; /images-products/image-zoomed.jpg',

        'deleted_at'        => null,
    ];
});

$factory->define(IndianIra\ProductPriceAndOption::class, function (Faker $faker) {
    return [
        'product_id'       => $product = function () {
            return factory(\IndianIra\Product::class)->create();
        },
        // 'option_code'      => 'OPT-' . time() . '-' . mt_rand(1000, 9999),
        'option_code'      => uniqid().mt_rand(1, 99999),
        'option_1_heading' => 'Header 1',
        'option_1_value'   => 'Value 1',
        'option_2_heading' => 'Header 2',
        'option_2_value'   => 'Value 2',
        'selling_price'    => round($faker->randomFloat(2, 100, 9999), 2),
        'discount_price'   => round($faker->randomFloat(2, 100, 9999), 2),
        'stock'            => $faker->randomNumber(2, true),
        'weight'           => round($faker->randomFloat(2, 1000, 9999), 2),
        'display'          => 'Enabled',
        'image'            => '/images-products/image-option-cart.jpg; /images-products/image-option-catalog.jpg; /images-products/image-option-zoomed.jpg',
    ];
});

$factory->define(IndianIra\ForgotPassword::class, function (Faker $faker) {
    return [
        'email'      => $faker->unique()->safeEmail,
        'token'      => str_random(60),
        'expires_on' => \Carbon\Carbon::now()->addHour(),
    ];
});

$factory->define(IndianIra\Coupon::class, function (Faker $faker) {
    return [
        'code'             => strtoupper($faker->word) . mt_rand(1000, 9999),
        'discount_percent' => round($faker->randomFloat(2, 0, 100), 2),
    ];
});

$factory->define(IndianIra\Carousel::class, function (Faker $faker) {
    return [
        'name'              => title_case($faker->word),
        'display'           => array_random(['Enabled', 'Disabled']),
        'short_description' => $faker->sentence,
    ];
});
