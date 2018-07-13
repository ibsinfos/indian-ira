<?php

namespace Tests\Feature;

use Tests\TestCase;
use IndianIra\Product;
use IndianIra\ProductPriceAndOption;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->generateSuperAdministrator();
    }

    /** @test */
    function no_products_in_cart()
    {
        $this->assertNull(session('cart'));
    }

    /** @test */
    function user_can_view_the_cart()
    {
        $this->withoutExceptionHandling()
             ->get(route('cart.show'))
             ->assertViewIs('cart.index')
             ->assertSee('Cart');
    }

    /** @test */
    function user_adds_a_product_with_zero_options_in_the_cart()
    {
        $product = factory(Product::class)->create(['number_of_options' => 0, 'display' => 'Enabled']);
        factory(ProductPriceAndOption::class)->create(['product_id' => $product->id, 'display' => 'Enabled']);

        $response = $this->withoutExceptionHandling()
                         ->get(route('cart.add', $product->code));

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Product: ' . $product->name . ' with code ' . $product->code . ' added successfully in the cart.');

        $this->assertEquals(session('cart')[$product->code]['quantity'], 1);

        $this->assertNotNull(session('cart'));
    }

    /** @test */
    function quantity_should_increase_if_same_product_is_added_in_the_cart()
    {
        $product = factory(Product::class)->create(['number_of_options' => 0, 'display' => 'Enabled']);
        factory(ProductPriceAndOption::class)->create([
            'product_id' => $product->id,
            'display' => 'Enabled',
            'selling_price' => 250.00,
        ]);

        session(['cart' => collect()->put($product->code, [
            'product' => $product,
            'options' => $option = $product->options->last(),
            'quantity' => 1,
            'selling_price' => $option->selling_price,
            'product_total' => $option->selling_price,
        ])]);

        $this->assertNotNull(session('cart'));

        $response = $this->withoutExceptionHandling()
                         ->get(route('cart.add', $product->code));

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Product: ' . $product->name . ' with code ' . $product->code . ' added successfully in the cart.');

        $this->assertEquals(session('cart')[$product->code]['quantity'], 2);
        $this->assertEquals(session('cart')[$product->code]['product_total'], 500.00);
    }

    /** @test */
    function product_cannot_be_added_if_it_does_exists_if_it_does_not_exists()
    {
        $product = factory(Product::class)->create(['number_of_options' => 0]);

        $response = $this->withoutExceptionHandling()
                         ->get(route('cart.add', 'someRandomCode'));

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Product with that code cannot be found!');
    }

    /** @test */
    function product_cannot_be_added_if_it_does_exists_if_Disbabled()
    {
        $product = factory(Product::class)->create(['number_of_options' => 0, 'display' => 'Disabled']);

        $response = $this->withoutExceptionHandling()
                         ->get(route('cart.add', $product->code));

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Product with that code cannot be found!');
    }

    /** @test */
    function user_can_add_any_number_of_products_with_zero_options_in_the_cart()
    {
        $products = factory(Product::class, 5)->create(['number_of_options' => 0, 'display' => 'Enabled']);
        foreach ($products as $product) {
            factory(ProductPriceAndOption::class)->create([
                'product_id' => $product->id,
                'display' => 'Enabled',
                'selling_price' => 100.00
            ]);
        }

        $product = $products->first();

        session(['cart' => collect()->put($product->code, [
            'product'       => $product,
            'options'       => $option = $product->options->last(),
            'quantity'      => 1,
            'selling_price' => (float) $option->selling_price,
            'product_total' => (float) $option->selling_price,
        ])]);

        $product = $products->where('id', '<>', $product->id)->random();

        $response = $this->withoutExceptionHandling()
                         ->get(route('cart.add', $product->code));

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Product: ' . $product->name . ' with code ' . $product->code . ' added successfully in the cart.');

        $sum = ($option->selling_price + $product->options->last()->selling_price);

        $this->assertEquals(2, session('cart')->count());
        $this->assertEquals(200.00, session('cart')->sum('selling_price'));
    }

    /** @test */
    function user_may_add_product_with_1_or_more_options()
    {
        $product = factory(Product::class)->create(['number_of_options' => 1, 'display' => 'Enabled']);
        $options = factory(ProductPriceAndOption::class, 5)->create([
            'option_code'   => 'OPT-' . uniqid() . '-' . mt_rand(1000, 99999),
            'product_id'    => $product->id,
            'display'       => 'Enabled',
            'selling_price' => 100.00
        ]);

        $option = $options->random();

        $response = $this->withoutExceptionHandling()
                         ->get(route('cart.add', [$product->code, $option->option_code]));

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Product: ' . $product->name . ' with option code ' . $option->option_code . ' added successfully in the cart.');
    }

    /** @test */
    function user_may_add_multiple_options_of_the_same_product_in_the_cart()
    {
        $product = factory(Product::class)->create(['number_of_options' => 1, 'display' => 'Enabled']);
        $options = factory(ProductPriceAndOption::class, 5)->create([
            'option_code'   => 'OPT-' . uniqid() . '-' . mt_rand(1000, 99999),
            'product_id'    => $product->id,
            'display'       => 'Enabled',
            'selling_price' => 100.00
        ]);

        $option = $options->random();

        $response = $this->withoutExceptionHandling()
                         ->get(route('cart.add', [$product->code, $option->option_code]));

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Product: ' . $product->name . ' with option code ' . $option->option_code . ' added successfully in the cart.');

        $option = $options->where('id', '<>', $option->id)->random();

        $response = $this->withoutExceptionHandling()
                         ->get(route('cart.add', [$product->code, $option->option_code]));

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Product: ' . $product->name . ' with option code ' . $option->option_code . ' added successfully in the cart.');
    }

    /** @test */
    function quantity_is_increased_if_same_product_with_option_is_added_in_cart()
    {
        $product = factory(Product::class)->create(['number_of_options' => 1, 'display' => 'Enabled']);
        $options = factory(ProductPriceAndOption::class, 5)->create([
            'option_code'   => 'OPT-' . uniqid() . '-' . mt_rand(1000, 99999),
            'product_id'    => $product->id,
            'display'       => 'Enabled',
            'selling_price' => 100.00
        ]);

        $option = $options->random();

        session(['cart' => collect()->put($option->option_code, [
            'product'       => $product,
            'options'       => $option,
            'quantity'      => 1,
            'selling_price' => (float) $option->selling_price,
            'product_total' => (float) $option->selling_price,
        ])]);

        $this->assertNotNull(session('cart'));

        $response = $this->withoutExceptionHandling()
                         ->get(route('cart.add', [$product->code, $option->option_code]));

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Product: ' . $product->name . ' with option code ' . $option->option_code . ' added successfully in the cart.');

        $this->assertEquals(session('cart')[$option->option_code]['quantity'], 2);
        $this->assertEquals(session('cart')[$option->option_code]['product_total'], 200.00);
    }

    /** @test */
    function a_product_quantity_cannot_be_more_quantity_than_in_stock()
    {
        $product = factory(Product::class)->create(['number_of_options' => 0, 'display' => 'Enabled']);
        factory(ProductPriceAndOption::class)->create([
            'product_id'    => $product->id,
            'display'       => 'Enabled',
            'selling_price' => 250.00,
            'stock'         => 1,
        ]);

        session(['cart' => collect()->put($product->code, [
            'product' => $product,
            'options' => $option = $product->options->last(),
            'quantity' => 1,
            'selling_price' => $option->selling_price,
            'product_total' => (float) $option->selling_price,
        ])]);

        $response = $this->withoutExceptionHandling()
                         ->get(route('cart.add', $product->code));

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'You have already added the maximum quantity available of this product.');
    }

    /** @test */
    function user_may_update_the_quantity_of_the_product_in_the_cart()
    {
        $category = factory(\IndianIra\Category::class)->create(['display' => 'Enabled']);

        $product = factory(Product::class)->create(['number_of_options' => 0, 'display' => 'Enabled']);
        factory(ProductPriceAndOption::class)->create([
            'product_id'    => $product->id,
            'display'       => 'Enabled',
            'selling_price' => 250.00,
        ]);

        $category->products()->attach([$product->id]);

        session(['cart' => collect()->put($product->code, [
            'product' => $product,
            'options' => $option = $product->options->last(),
            'quantity' => 1,
            'selling_price' => $option->selling_price,
            'product_total' => (float) $option->selling_price,
        ])]);

        $this->assertNotNull(session('cart'));

        $response = $this->withoutExceptionHandling()
                         ->post(route('cart.updateQty', $product->code), ['quantity' => 3]);

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Product updated successfully!');

        $this->assertEquals(session('cart')[$product->code]['quantity'], 3);
        $this->assertEquals(session('cart')[$product->code]['product_total'], 750.00);
    }

    /** @test */
    function quantity_field_needs_to_be_an_integer()
    {
        $this->post(route('cart.updateQty', 'product-code'), [
                'quantity' => 'kdugf'
            ])
            ->assertSessionHasErrors('quantity');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('quantity'),
            'The quantity must be an integer.'
        );
    }

    /** @test */
    function a_product_can_be_removed_from_the_cart()
    {
        $category = factory(\IndianIra\Category::class)->create(['display' => 'Enabled']);

        $product1 = factory(Product::class)->create(['number_of_options' => 0, 'display' => 'Enabled']);
        factory(ProductPriceAndOption::class)->create([
            'product_id'    => $product1->id,
            'display'       => 'Enabled',
            'selling_price' => 250.00,
        ]);

        $product2 = factory(Product::class)->create(['number_of_options' => 0, 'display' => 'Enabled']);
        factory(ProductPriceAndOption::class)->create([
            'product_id'    => $product2->id,
            'display'       => 'Enabled',
            'selling_price' => 250.00,
        ]);

        $category->products()->attach([$product1->id, $product2->id]);

        $cart = collect();

        $cart->put($product1->code, [
            'product' => $product1,
            'options' => $option = $product1->options->last(),
            'quantity' => 1,
            'selling_price' => $option->selling_price,
            'product_total' => (float) $option->selling_price,
        ]);

        $cart->put($product2->code, [
            'product' => $product2,
            'options' => $option = $product2->options->last(),
            'quantity' => 1,
            'selling_price' => $option->selling_price,
            'product_total' => (float) $option->selling_price,
        ]);

        session(['cart' => $cart]);

        $this->assertNotNull(session('cart'));
        $this->assertEquals(2, session('cart')->count());

        $response = $this->withoutExceptionHandling()
                         ->get(route('cart.remove', $product1->code));

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Product removed successfully...');

        $this->assertEquals(1, session('cart')->count());
    }

    /** @test */
    function a_product_cannot_be_if_it_does_not_exists_in_the_cart()
    {
        $category = factory(\IndianIra\Category::class)->create(['display' => 'Enabled']);

        $product1 = factory(Product::class)->create(['number_of_options' => 0, 'display' => 'Enabled']);
        factory(ProductPriceAndOption::class)->create([
            'product_id'    => $product1->id,
            'display'       => 'Enabled',
            'selling_price' => 250.00,
        ]);

        $product2 = factory(Product::class)->create(['number_of_options' => 0, 'display' => 'Enabled']);
        factory(ProductPriceAndOption::class)->create([
            'product_id'    => $product2->id,
            'display'       => 'Enabled',
            'selling_price' => 250.00,
        ]);

        $category->products()->attach([$product1->id, $product2->id]);

        $cart = collect();

        $cart->put($product1->code, [
            'product' => $product1,
            'options' => $option = $product1->options->last(),
            'quantity' => 1,
            'selling_price' => $option->selling_price,
            'product_total' => (float) $option->selling_price,
        ]);

        $cart->put($product2->code, [
            'product' => $product2,
            'options' => $option = $product2->options->last(),
            'quantity' => 1,
            'selling_price' => $option->selling_price,
            'product_total' => (float) $option->selling_price,
        ]);

        session(['cart' => $cart]);

        $this->assertNotNull(session('cart'));
        $this->assertEquals(2, session('cart')->count());

        $response = $this->withoutExceptionHandling()
                         ->get(route('cart.remove', 'rand0om-code'));

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Product with that code cannot be found!');

        $this->assertEquals(2, session('cart')->count());
    }

    /** @test */
    function user_may_apply_a_coupon_to_avail_discount()
    {
        $category = factory(\IndianIra\Category::class)->create(['display' => 'Enabled']);

        $product1 = factory(Product::class)->create(['number_of_options' => 0, 'display' => 'Enabled']);
        factory(ProductPriceAndOption::class)->create([
            'product_id'    => $product1->id,
            'display'       => 'Enabled',
            'selling_price' => 250.00,
        ]);

        $product2 = factory(Product::class)->create(['number_of_options' => 0, 'display' => 'Enabled']);
        factory(ProductPriceAndOption::class)->create([
            'product_id'    => $product2->id,
            'display'       => 'Enabled',
            'selling_price' => 250.00,
        ]);

        $category->products()->attach([$product1->id, $product2->id]);

        $coupon = factory(\IndianIra\Coupon::class)->create([
            'code' => 'JULY12', 'discount_percent' => 12
        ]);

        $cart = collect();

        $cart->put($product1->code, [
            'product' => $product1,
            'options' => $option = $product1->options->last(),
            'quantity' => 1,
            'selling_price' => $option->selling_price,
            'product_total' => (float) $option->selling_price,
        ]);

        $cart->put($product2->code, [
            'product' => $product2,
            'options' => $option = $product2->options->last(),
            'quantity' => 1,
            'selling_price' => $option->selling_price,
            'product_total' => (float) $option->selling_price,
        ]);

        session(['cart' => $cart]);

        $this->assertNotNull(session('cart'));
        $this->assertEquals(2, session('cart')->count());

        $response = $this->withoutExceptionHandling()
                         ->post(route('cart.applyCoupon'), ['couponCode' => $coupon->code]);

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Coupon Code applied successfully...');
    }

    /** @test */
    function user_may_remove_a_coupon_to_if_it_is_already_applied()
    {
        $category = factory(\IndianIra\Category::class)->create(['display' => 'Enabled']);

        $product1 = factory(Product::class)->create(['number_of_options' => 0, 'display' => 'Enabled']);
        factory(ProductPriceAndOption::class)->create([
            'product_id'    => $product1->id,
            'display'       => 'Enabled',
            'selling_price' => 250.00,
        ]);

        $category->products()->attach([$product1->id]);

        $coupon = factory(\IndianIra\Coupon::class)->create([
            'code' => 'JULY12', 'discount_percent' => 12
        ]);

        $cart = collect();

        $cart->put($product1->code, [
            'product' => $product1,
            'options' => $option = $product1->options->last(),
            'quantity' => 1,
            'selling_price' => $option->selling_price,
            'product_total' => (float) $option->selling_price,
        ]);

        $totalWithoutCouponDiscount = \IndianIra\Utilities\Cart::totalWithoutCouponDiscount();

        session(['appliedDiscount' => [
            'coupon' => $coupon,
            'amount' => ((float) $totalWithoutCouponDiscount * ($coupon->discount_percent / 100)),
        ]]);

        session(['cart' => $cart]);

        $this->assertNotNull(session('cart'));
        $this->assertNotNull(session('appliedDiscount'));
        $this->assertEquals(1, session('cart')->count());

        $response = $this->withoutExceptionHandling()
                         ->get(route('cart.removeCoupon'));

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Coupon Code removed successfully...');

        $this->assertNull(session('appliedDiscount'));
    }

    /** @test */
    function user_can_empty_the_cart()
    {
        $product = factory(Product::class)->create(['number_of_options' => 0, 'display' => 'Enabled']);
        factory(ProductPriceAndOption::class)->create([
            'product_id' => $product->id,
            'display'    => 'Enabled',
        ]);
        $category = factory(\IndianIra\Category::class)->create(['display' => 'Enabled']);

        $category->products()->attach([$product->id]);

        session(['cart' => collect()->put($product->code, [
            'product' => $product,
            'options' => $option = $product->options->last(),
            'quantity' => 1,
            'selling_price' => $option->selling_price,
            'product_total' => (float) $option->selling_price,
        ])]);

        $this->assertNotNull(session('cart'));

        $response = $this->withoutExceptionHandling()
                         ->get(route('cart.empty'));

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Cart emptied successfully! Redirecting...');
        $this->assertEquals($result->location, route('homePage'));

        $this->assertNull(session('cart'));
    }
}
