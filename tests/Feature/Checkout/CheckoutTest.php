<?php

namespace Tests\Feature\Checkout;

use Tests\TestCase;
use IndianIra\Product;
use IndianIra\ProductPriceAndOption;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->generateSuperAdministrator();
    }

    /** @test */
    function redirect_to_home_page_if_no_products_are_added_in_cart()
    {
        $this->assertNull(session('cart'));

        $this->withoutExceptionHandling()
             ->get(route('checkout'))
             ->assertRedirect(route('homePage'));
    }

    /** @test */
    function products_are_already_added_in_cart()
    {
        $product = factory(Product::class)->create(['number_of_options' => 0, 'display' => 'Enabled']);
        $option = factory(ProductPriceAndOption::class)->create([
            'product_id' => $product->id,
            'display' => 'Enabled',
            'selling_price' => 250.00,
        ]);

        session(['cart' => collect()->put($product->code, [
            'product' => $product,
            'options' => $option,
            'quantity' => 1,
            'selling_price' => $option->selling_price,
            'product_total' => $option->selling_price,
        ])]);

        $this->assertNotNull(session('cart'));
        $this->assertCount(1, session('cart'));
    }

    /** @test */
    function display_the_authentication_forms_if_user_is_a_guest()
    {
        $product = factory(Product::class)->create(['number_of_options' => 0, 'display' => 'Enabled']);
        $option = factory(ProductPriceAndOption::class)->create([
            'product_id'    => $product->id,
            'display'       => 'Enabled',
            'selling_price' => 250.00,
        ]);

        session(['cart' => collect()->put($product->code, [
            'product'       => $product,
            'options'       => $option,
            'quantity'      => 1,
            'selling_price' => $option->selling_price,
            'product_total' => $option->selling_price,
        ])]);

        $this->withoutExceptionHandling()
             ->get(route('checkout'))
             ->assertViewIs('checkout.authentication')
             ->assertSee('Checkout Authentication');
    }
}
