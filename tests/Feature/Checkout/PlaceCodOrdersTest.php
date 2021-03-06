<?php

namespace Tests\Feature\Checkout;

use Tests\TestCase;
use IndianIra\Order;
use IndianIra\Product;
use IndianIra\OrderAddress;
use IndianIra\OrderHistory;
use IndianIra\Mail\OrderPlaced;
use IndianIra\Mail\OrderReceived;
use Illuminate\Support\Facades\Mail;
use IndianIra\ProductPriceAndOption;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlaceCodOrdersTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->generateSuperAdministrator();

        $this->signInUser();
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
        $this->addProductsInSessionCart();

        $this->assertNotNull(session('cart'));
        $this->assertCount(1, session('cart'));
    }

    /** @test */
    function user_places_an_cod_order()
    {
        $user = auth()->user();

        $codCharges = factory(\IndianIra\GlobalSettingCodCharge::class)->create(['amount' => 60.00]);

        $sessionCart = $this->addProductsInSessionCart();

        $formValues = $this->getCodOrderProductsFormData(['payment_method' => 'cod']);

        session(['codCharges' => $codCharges]);

        $response = $this->withoutExceptionHandling()
                         ->post(route('checkout.proceedCod'), $formValues);

        $result = json_decode($response->getContent());

        $this->assertCount(1, Order::all());
        $this->assertCount(1, OrderAddress::all());
        $this->assertCount(1, OrderHistory::all());
        $this->assertEquals(9, $sessionCart['option']->stock);
        $this->assertNotNull(session('codOrders'));
        $this->assertNotNull(session('shippingCompany'));
        $this->assertEquals(Order::first()->product_cart_image, '/images/default-product-image-cart.jpg');
        $this->assertEquals(Order::first()->product_page_url, $sessionCart['product']->canonicalPageUrl());

        $this->assertEquals(60.00, Order::all()->first()->cart_total_cod_amount);

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->location, route('orderPlacedCodSuccess'));
    }

    /** @test */
    function buyer_receives_an_email_of_the_order_placed()
    {
        Mail::fake();

        $user = auth()->user();

        $sessionCart = $this->addProductsInSessionCart();

        $formValues = $this->getCodOrderProductsFormData(['payment_method' => 'cod']);

        $response = $this->withoutExceptionHandling()
                         ->post(route('checkout.proceedCod'), $formValues);

        $result = json_decode($response->getContent());

        $this->assertCount(1, Order::all());
        $this->assertCount(1, OrderAddress::all());
        $this->assertCount(1, OrderHistory::all());
        $this->assertEquals(9, $sessionCart['option']->stock);
        $this->assertNotNull(session('codOrders'));
        $this->assertNotNull(session('shippingCompany'));

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->location, route('orderPlacedCodSuccess'));

        Mail::assertSent(OrderPlaced::class);
    }

    /** @test */
    function super_administrator_receives_an_email_of_the_order_placed()
    {
        Mail::fake();

        $user = auth()->user();

        $sessionCart = $this->addProductsInSessionCart();

        $formValues = $this->getCodOrderProductsFormData(['payment_method' => 'cod']);

        $response = $this->withoutExceptionHandling()
                         ->post(route('checkout.proceedCod'), $formValues);

        $result = json_decode($response->getContent());

        $this->assertCount(1, Order::all());
        $this->assertCount(1, OrderAddress::all());
        $this->assertCount(1, OrderHistory::all());
        $this->assertEquals(9, $sessionCart['option']->stock);
        $this->assertNotNull(session('codOrders'));
        $this->assertNotNull(session('shippingCompany'));

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->location, route('orderPlacedCodSuccess'));

        Mail::assertSent(OrderReceived::class);
    }

    /** @test */
    function display_the_thank_you_page_on_placing_the_order()
    {
        $user = auth()->user();

        $sessionCart = $this->addProductsInSessionCart();

        $formValues = $this->getCodOrderProductsFormData();

        $response = $this->withoutExceptionHandling()
                         ->post(route('checkout.proceedCod'), $formValues);

        $result = json_decode($response->getContent());

        $this->withoutExceptionHandling()
             ->get(route('orderPlacedCodSuccess'))
             ->assertViewIs('orders.placed_cod_success')
             ->assertSee('Thank You for placing order with us.');
    }

    /** @test */
    function cart_should_get_emptied_on_successfully_completing_checkout_process()
    {
        $user = auth()->user();

        $sessionCart = $this->addProductsInSessionCart();

        $formValues = $this->getCodOrderProductsFormData(['payment_method' => 'cod']);

        $response = $this->withoutExceptionHandling()
                         ->post(route('checkout.proceedCod'), $formValues);

        $this->withoutExceptionHandling()
             ->get(route('orderPlacedCodSuccess'));

        $this->assertNull(session('cart'));
        $this->assertNull(session('cartTotalAmounts'));
        $this->assertNull(session('appliedDiscount'));
        $this->assertNull(session('shippingRateRecord'));
        $this->assertNull(session('codCharges'));
        $this->assertNull(session('shippingCompany'));

        $this->assertNotNull(session('codOrders'));
    }

    /**
     * Get the default address details for the user.
     *
     * @param   array  $attributes
     * @return  array
     */
    protected function getCodOrderProductsFormData($attributes = [])
    {
        return array_merge([
            'full_name'               => auth()->user()->getFullName(),
            'address_line_1'          => 'A 705, Golden Nest Building',
            'address_line_2'          => 'Sector 9 Charkop',
            'area'                    => 'Kandivali West',
            'landmark'                => 'Swami Samarth Temple',
            'city'                    => 'Mumbai',
            'pin_code'                => '400067',
            'state'                   => 'Maharashtra',
            'country'                 => 'India',
            'contact_number'          => '9876543210',

            'sameAsBillingAddress'    => 'yes',
            'payment_method'          => 'offline',

            'shipping_full_name'      => auth()->user()->getFullName(),
            'shipping_address_line_1' => 'A 705, Golden Nest Building',
            'shipping_address_line_2' => 'Sector 9 Charkop',
            'shipping_area'           => 'Kandivali West',
            'shipping_landmark'       => 'Swami Samarth Temple',
            'shipping_city'           => 'Mumbai',
            'shipping_pin_code'       => '400067',
            'shipping_state'          => 'Maharashtra',
            'shipping_country'        => 'India',
            'shipping_contact_number' => '9876543210',
        ], $attributes);
    }

    /**
     * Add products in cart.
     *
     * @return  array
     */
    protected function addProductsInSessionCart()
    {
        $shippingRate = factory(\IndianIra\ShippingRate::class)->create();
        session(['shippingCompany' => $shippingRate]);

        $product = factory(Product::class)->create(['number_of_options' => 0, 'display' => 'Enabled']);
        $option = factory(ProductPriceAndOption::class)->create([
            'product_id' => $product->id,
            'display' => 'Enabled',
            'selling_price' => 250.00,
            'discount_price' => 0.0,
            'stock' => 10,
            'image' => '/images/default-product-image-cart.jpg; /images/default-product-image-catalog.jpg; /images/default-product-image-zoomed.jpg',
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

        return [
            'product'  => $product,
            'option'   => $option,
            'category' => $category
        ];
    }
}
