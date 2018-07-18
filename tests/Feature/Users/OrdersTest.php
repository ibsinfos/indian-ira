<?php

namespace Tests\Feature\Users;

use Tests\TestCase;
use IndianIra\Order;
use IndianIra\OrderAddress;
use IndianIra\OrderHistory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrdersTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->generateSuperAdministrator();

        $this->user = $this->signInUser();
    }

    /** @test */
    function guest_user_cannot_access_the_orders_section()
    {
        auth()->logout();

        $this->withoutExceptionHandling()
             ->get(route('users.orders'))
             ->assertStatus(302)
             ->assertRedirect(route('users.login'));
    }

    /** @test */
    function user_sees_the_billing_address_section()
    {
        $this->withoutExceptionHandling()
             ->get(route('users.orders'))
             ->assertSee('List of All Orders')
             ->assertViewIs('users.orders.index');
    }

    /** @test */
    function user_can_view_the_products_details_for_the_given_order_code()
    {
        $order = factory(Order::class)->create(['user_id' => $this->user->id]);

        $this->withoutExceptionHandling()
             ->get(route('users.orders.products', $order->order_code))
             ->assertViewIs('users.orders.show_products')
             ->assertSee('Viewing Product Order Details: '. $order->order_code);
    }

    /** @test */
    function user_can_view_the_address_details_for_the_given_order_code()
    {
        $order = factory(Order::class)->create(['user_id' => $this->user->id]);
        $address = factory(OrderAddress::class)->create([
            'order_id' => $order->id,
            'order_code' => $order->order_code,
        ]);

        $this->withoutExceptionHandling()
             ->get(route('users.orders.address', $order->order_code))
             ->assertViewIs('users.orders.show_address')
             ->assertSee('Viewing Address Details: '. $order->order_code);
    }

    /** @test */
    function user_can_view_specific_orders_history_details()
    {
        $order = factory(Order::class)->create(['user_id' => $this->user->id]);
        $history = factory(OrderHistory::class)->create([
            'order_id'   => $order->id,
            'order_code' => $order->order_code,
            'user_id'    => $this->user->id,
        ]);

        $this->withoutExceptionHandling()
             ->get(route('users.orders.history', $history->order_code))
             ->assertViewIs('users.orders.show_history')
             ->assertSee('Viewing History Details: '. $history->order_code);
    }
}
