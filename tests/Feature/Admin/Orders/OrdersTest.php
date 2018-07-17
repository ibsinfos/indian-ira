<?php

namespace Tests\Feature\Admin\Orders;

use Tests\TestCase;
use IndianIra\Order;
use IndianIra\OrderAddress;
use IndianIra\OrderHistory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrdersTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->signInSuperAdministrator();
    }

    /** @test */
    function only_logged_in_super_administrator_can_access_the_orders_section()
    {
        auth()->logout();

        $this->withoutExceptionHandling()
             ->get(route('admin.orders'))
             ->assertStatus(302)
             ->assertRedirect(route('admin.login'));
    }

    /** @test */
    function super_administrator_can_view_the_orders_section()
    {
        $this->withoutExceptionHandling()
             ->get(route('admin.orders'))
             ->assertViewIs('admin.orders.index')
             ->assertSee('List of All Orders');
    }

    /** @test */
    function no_orders_data_exists()
    {
        $this->assertCount(0, Order::all());
    }

    /** @test */
    function super_administrator_can_view_specific_orders_products_details()
    {
        $address = factory(OrderAddress::class)->create();

        $this->withoutExceptionHandling()
             ->get(route('admin.orders.showProducts', $address->order_code))
             ->assertViewIs('admin.orders.show_products')
             ->assertSee('Viewing Product Order Details: '. $address->order_code);
    }

    /** @test */
    function super_administrator_can_view_specific_orders_address_details()
    {
        $address = factory(OrderAddress::class)->create();

        $this->withoutExceptionHandling()
             ->get(route('admin.orders.showAddress', $address->order_code))
             ->assertViewIs('admin.orders.show_address')
             ->assertSee('Viewing Address Details: '. $address->order_code);
    }

    /** @test */
    function super_administrator_can_view_specific_orders_history_details()
    {
        $history = factory(OrderHistory::class)->create();

        $this->withoutExceptionHandling()
             ->get(route('admin.orders.showHistory', $history->order_code))
             ->assertViewIs('admin.orders.show_history')
             ->assertSee('Viewing History Details: '. $history->order_code);
    }

    /** @test */
    function super_administrator_can_add_new_order_history_to_specific_order()
    {
        $history = factory(OrderHistory::class)->create();

        $formValues = [
            'notes'            => 'Updated status',
            'select_products'  => $history->product_id,
            'status'           => array_random(["Shipped", "Completed", "Cancelled"]),
        ];

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.orders.storeHistory', $history->order_code), $formValues);

        $result = json_decode($response->getContent());

        $history = $this->getAllOrders()->first()->history()->orderBy('id', 'DESC')->get();

        $this->assertCount(2, $history);

        $this->assertTrue($history->first()->status != 'Processing');
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Order History added successfully...');
        $this->assertEquals($result->htmlResult, view('admin.orders._history_table', compact('history'))->render());
    }

    /** @test */
    function super_administrator_can_temporarily_delete_an_order()
    {
        $address = factory(OrderAddress::class)->create();

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.orders.delete', $address->order_code));

        $result = json_decode($response->getContent());

        $allOrders = $this->getAllOrders()->groupBy('order_code');

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Order deleted successfully !');
        $this->assertEquals($result->htmlResult, view('admin.orders.table', compact('allOrders'))->render());
    }

    /** @test */
    function super_administrator_cannot_temporarily_delete_an_order_that_does_not_exists()
    {
        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.orders.delete', 'some-code'));

        $result = json_decode($response->getContent());

        $allOrders = $this->getAllOrders()->groupBy('order_code');

        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Order with that code cannot be found!');
    }

    /** @test */
    function super_administrator_can_restore_a_temporarily_deleted_order()
    {
        $address = factory(Order::class)->create(['deleted_at' => now()]);

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.orders.restore', $address->order_code));

        $result = json_decode($response->getContent());

        $allOrders = $this->getAllOrders()->groupBy('order_code');

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Order restored successfully !');
        $this->assertEquals($result->htmlResult, view('admin.orders.table', compact('allOrders'))->render());
    }

    /** @test */
    function super_administrator_cannot_restore_a_temporarily_deleted_order_that_does_not_exists()
    {
        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.orders.restore', 'some-code'));

        $result = json_decode($response->getContent());

        $allOrders = $this->getAllOrders()->groupBy('order_code');

        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Order with that code cannot be found!');
    }

    /** @test */
    function super_administrator_can_permanently_delete_a_temporarily_deleted_order()
    {
        $address = factory(Order::class)->create(['deleted_at' => now()]);
        factory(OrderAddress::class)->create(['order_code' => $address->order_code]);
        factory(OrderHistory::class)->create(['order_code' => $address->order_code]);

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.orders.destroy', $address->order_code));

        $result = json_decode($response->getContent());

        $allOrders = $this->getAllOrders()->groupBy('order_code');

        $this->assertNull(OrderAddress::first());
        $this->assertNull(OrderHistory::first());

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Order destroyed successfully !');
        $this->assertEquals($result->htmlResult, view('admin.orders.table', compact('allOrders'))->render());
    }

    /** @test */
    function super_administrator_cannot_permanently_delete_a_temporarily_deleted_order_that_does_not_exists()
    {
        $address = factory(Order::class)->create(['deleted_at' => now()]);

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.orders.destroy', 'some-code'));

        $result = json_decode($response->getContent());

        $allOrders = $this->getAllOrders()->groupBy('order_code');

        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Order with that code cannot be found!');
    }

    /**
     * Get all the orders.
     *
     * @return  \Illuminate\Database\Eloquent\Collection
     */
    protected function getAllOrders()
    {
        return Order::withTrashed()
                    ->with(['address', 'user'])
                    ->orderBy('id', 'DESC')
                    ->get();
    }
}
