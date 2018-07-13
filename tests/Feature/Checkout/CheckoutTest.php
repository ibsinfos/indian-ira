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
            'discount_price' => 0.0
        ]);
        $category = factory(\IndianIra\Category::class)->create(['display' => 'Enabled']);

        $category->products()->attach([$product->id]);

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
            'discount_price' => 0.0
        ]);
        $category = factory(\IndianIra\Category::class)->create(['display' => 'Enabled']);

        $category->products()->attach([$product->id]);

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

    /** @test */
    function add_cod_charges_if_payment_method_is_COD()
    {
        $this->signInUser();

        $codCharges = factory(\IndianIra\GlobalSettingCodCharge::class)->create(['amount' => 60.00]);
        $product = factory(Product::class)->create(['number_of_options' => 0, 'display' => 'Enabled']);
        $option = factory(ProductPriceAndOption::class)->create([
            'product_id'    => $product->id,
            'display'       => 'Enabled',
            'selling_price' => 250.00,
            'discount_price' => 0.0
        ]);
        $category = factory(\IndianIra\Category::class)->create(['display' => 'Enabled']);

        $category->products()->attach([$product->id]);

        session(['cart' => collect()->put($product->code, [
            'product'       => $product,
            'options'       => $option,
            'quantity'      => 1,
            'selling_price' => $option->selling_price,
            'product_total' => $option->selling_price,
        ])]);

        $response = $this->withoutExceptionHandling()
                        ->get(route('checkout.addCodCharges', ['payment_method' => 'cod']));

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'success');
        $this->assertNotNull(session('codCharges'));

        $this->assertEquals(60, \IndianIra\Utilities\Cart::codCharges());

        // 250 + 60 = 310
        $this->assertEquals(310, \IndianIra\Utilities\Cart::totalPayableAmount());
    }

    /** @test */
    function no_cod_charges_are_applied_if_payment_method_is_is_not_COD()
    {
        $this->signInUser();

        $codCharges = factory(\IndianIra\GlobalSettingCodCharge::class)->create(['amount' => 60.00]);
        $product = factory(Product::class)->create(['number_of_options' => 0, 'display' => 'Enabled']);
        $option = factory(ProductPriceAndOption::class)->create([
            'product_id'    => $product->id,
            'display'       => 'Enabled',
            'selling_price' => 250.00,
            'discount_price' => 0.0
        ]);
        $category = factory(\IndianIra\Category::class)->create(['display' => 'Enabled']);

        $category->products()->attach([$product->id]);

        session(['cart' => collect()->put($product->code, [
            'product'       => $product,
            'options'       => $option,
            'quantity'      => 1,
            'selling_price' => $option->selling_price,
            'product_total' => $option->selling_price,
        ])]);

        $response = $this->withoutExceptionHandling()
                        ->get(route('checkout.addCodCharges', ['payment_method' => array_random(['online', 'offline'])]));

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'success');
        $this->assertNull(session('codCharges'));

        $this->assertEquals(0.0, \IndianIra\Utilities\Cart::codCharges());

        // 250 + 0.00 = 250.0
        $this->assertEquals(250, \IndianIra\Utilities\Cart::totalPayableAmount());
    }
}
