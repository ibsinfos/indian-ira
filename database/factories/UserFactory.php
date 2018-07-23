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
        'first_name'         => str_replace(['.', '\'', '-'], ['_', '_', '_'], $faker->firstName),
        'last_name'          => str_replace(['.', '\'', '-'], ['_', '_', '_'], $faker->lastName),
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
        'location_type'                 => array_random(['City', 'State', 'Country']),
        'location_name'                 => array_random([$faker->city, $faker->state, $faker->country]),
        'weight_from'                   => (float) round($faker->randomFloat(2, 100, 999), 2),
        'weight_to'                     => (float) round($faker->randomFloat(2, 100, 999), 2),
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
        'display_in_menu'   => array_random([0, 1]),
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
        'number_of_options' => array_random([0, 1, 2]),
        'sort_number'       => (integer) $faker->randomDigit(),

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
        'option_code'      => uniqid().mt_rand(1, 99999),
        'option_1_heading' => 'Header 1',
        'option_1_value'   => 'Value 1',
        'option_2_heading' => 'Header 2',
        'option_2_value'   => 'Value 2',
        'selling_price'    => (float) round($faker->randomFloat(2, 100, 9999), 2),
        'discount_price'   => (float) round($faker->randomFloat(2, 100, 9999), 2),
        'stock'            => $faker->randomNumber(2, true),
        'sort_number'      => $faker->randomDigit(),
        'weight'           => (float) round($faker->randomFloat(2, 1, 1000), 2),
        'display'          => 'Enabled',
        'image'            => '/images-products/image-option-cart.jpg; /images-products/image-option-catalog.jpg; /images-products/image-option-zoomed.jpg',
        'gallery_image_1' => null,
        'gallery_image_2' => null,
        'gallery_image_3' => null,
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
    $specialCharacters = ['.', '-', '\'', ',', '/', '!', '@', '#', '$', '%'];

    return [
        'code'             => str_replace($specialCharacters, '_', strtoupper($faker->word)) . mt_rand(1000, 9999),
        'discount_percent' => (float) round($faker->randomFloat(2, 0, 100), 2),
    ];
});

$factory->define(IndianIra\Carousel::class, function (Faker $faker) {
    return [
        'name'              => title_case($faker->word),
        'display'           => array_random(['Enabled', 'Disabled']),
        'short_description' => $faker->sentence,
    ];
});

$factory->define(IndianIra\Order::class, function (Faker $faker) {
    $user = factory(\IndianIra\User::class)->create();
    $product = factory(\IndianIra\Product::class)->create(['display' => 'Enabled']);
    $option = factory(\IndianIra\ProductPriceAndOption::class)->create(['display' => 'Enabled', 'product_id' => $product->id]);
    $category = factory(\IndianIra\Category::class)->create(['display' => 'Enabled']);
    $category->products()->attach([$product->id]);

    return [
        'order_code'                 => 'ORD-'.mt_rand(1, 999999),
        'user_id'                    => $user->id,
        'user_full_name'             => $user->getFullName(),
        'user_username'              => $user->username,
        'user_email'                 => $user->email,
        'user_contact_number'        => $user->contact_number,
        'product_id'                 => $product->id,
        'product_code'               => $product->code,
        'product_name'               => $product->name,
        'product_cart_image'         => '/images/default-product-image-cart.jpg',
        'product_page_url'           => $product->canonicalPageUrl(),
        'product_number_of_options'  => $product->number_of_options,
        'product_option_id'          => $option->id,
        'product_option_code'        => $option->option_code,
        'product_option_1_heading'   => $option->option_1_heading,
        'product_option_1_value'     => $option->option_1_value,
        'product_option_2_heading'   => $option->option_2_heading,
        'product_option_2_value'     => $option->option_2_value,
        'product_stock'              => $option->stock,
        'product_weight'             => $option->weight,
        'product_quantity'           => 1,
        'product_selling_price'      => $option->selling_price,
        'product_discount_price'     => $option->discount_price,
        'product_net_amount'         => (float) $faker->randomFloat(2, 1000, 9999),
        'product_gst_amount'         => (float) $faker->randomFloat(2, 1000, 9999),
        'product_gst_percent'        => (float) $product->get_percent,
        'product_total_amount'       => (float) $faker->randomFloat(2, 1000, 9999),
        'payment_method'             => array_random(['online', 'offline', 'cod']),
        'coupon_code'                => null,
        'coupon_discount_percent'    => 0.0,
        'cart_total_net_amount'      => (float) $faker->randomFloat(2, 1000, 9999),
        'cart_total_gst_amount'      => (float) $faker->randomFloat(2, 1000, 9999),
        'cart_total_shipping_amount' => (float) $faker->randomFloat(2, 1000, 9999),
        'cart_total_cod_amount'      => (float) $faker->randomFloat(2, 1000, 9999),
        'cart_coupon_amount'         => 0.0,
        'cart_total_payable_amount'  => (float) $faker->randomFloat(2, 1000, 9999),
    ];
});

$factory->define(IndianIra\OrderAddress::class, function (Faker $faker) {
    $order = factory(\IndianIra\Order::class)->create();

    return [
        'order_id'                 => $order->id,
        'order_code'               => $order->order_code,
        'full_name'                => str_replace(['.', '\'', '-'], ['_', '_', '_'], $faker->name),
        'address_line_1'           => 'A 705, Golden Nest Building',
        'address_line_2'           => 'Sector 9 Charkop',
        'area'                     => 'Kandivali West',
        'landmark'                 => 'Swami Samarth Temple',
        'city'                     => 'Mumbai',
        'pin_code'                 => '400067',
        'state'                    => 'Maharashtra',
        'country'                  => 'India',

        'shipping_same_as_billing' => 'yes',

        'shipping_full_name'       => str_replace(['.', '\'', '-'], ['_', '_', '_'], $faker->name),
        'shipping_address_line_1'  => 'A 705, Golden Nest Building',
        'shipping_address_line_2'  => 'Sector 9 Charkop',
        'shipping_area'            => 'Kandivali West',
        'shipping_landmark'        => 'Swami Samarth Temple',
        'shipping_city'            => 'Mumbai',
        'shipping_pin_code'        => '400067',
        'shipping_state'           => 'Maharashtra',
        'shipping_country'         => 'India',
    ];
});

$factory->define(IndianIra\OrderHistory::class, function (Faker $faker) {
    $order = factory(\IndianIra\Order::class)->create();
    $shippingCompany = factory(\IndianIra\ShippingRate::class)->create();

    return [
        'order_id'              => $order->id,
        'order_code'            => $order->order_code,
        'user_id'               => $order->user_id,
        'user_full_name'        => $order->user_full_name,
        'user_email'            => $order->user_email,
        'product_id'            => $order->product_id,
        'product_code'          => $order->product_code,
        'product_name'          => $order->product_name,
        'product_option_id'     => $order->product_option_id,
        'product_option_code'   => $order->product_option_code,
        'shipping_company'      => $shippingCompany->shipping_company_name,
        'shipping_tracking_url' => $shippingCompany->shipping_company_tracking_url,
        'status'                => 'Processing',
        'notes'                 => 'Order placed successfully...',
    ];
});
