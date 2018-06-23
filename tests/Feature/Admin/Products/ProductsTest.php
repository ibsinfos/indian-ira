<?php

namespace Tests\Feature\Admin\Products;

use Tests\TestCase;
use IndianIra\Product;
use IndianIra\Utilities\Directories;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductsTest extends TestCase
{
    use RefreshDatabase, Directories;

    public function setUp()
    {
        parent::setUp();

        $this->signInSuperAdministrator();
    }

    /** @test */
    function only_logged_in_super_administrator_can_access_the_products_section()
    {
        // Logout the Super Administrator
        auth()->logout();

        $this->withoutExceptionHandling()
             ->get(route('admin.products'))
             ->assertStatus(302)
             ->assertRedirect(route('admin.login'));
    }

    /** @test */
    function super_administrator_can_view_the_products_section()
    {
        $this->withoutExceptionHandling()
             ->get(route('admin.products'))
             ->assertViewIs('admin.products.index')
             ->assertSee('List of All Products');
    }

    /** @test */
    function no_products_data_exists()
    {
        $this->assertCount(0, Product::all());
    }

    /** @test */
    function super_administrator_can_add_new_product()
    {
        $productsData = [
            'code'              => 'PRD-'. time() . '-' . rand(1000, 9999),
            'name'              => 'Product 1',
            'gst_percent'       => 18.00,
            'number_of_options' => 0,
        ];

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.products.store'), $productsData);

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Product added successfully! Redirecting to edit...');
        $this->assertEquals($result->location, route('admin.products.edit', 1) . '?general');

        $this->assertNotNull(Product::first());
    }

    /** @test */
    function super_administrator_sees_the_general_editing_page()
    {
        $product = factory(Product::class)->create();

        $this->withoutExceptionHandling()
             ->get(route('admin.products.edit', $product->id) . '?general')
             ->assertStatus(200)
             ->assertViewIs('admin.products.edit')
             ->assertSee('Edit General Details of Product: ' . $product->name);

        $this->assertTrue('general' == request()->exists('general'));
    }

    /** @test */
    function super_administrator_can_update_the_general_details_of_the_product()
    {
        $product = factory(Product::class)->create();

        $formValues = array_merge($product->toArray(), [
            'code' => 'PRD-1',
            'name' => 'Product 1',
            'display' => 'Enabled',
            'gst_percent' => 18.00,
            'number_of_options' => 2
        ]);

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.products.updateGeneral', $product->id), $formValues);

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Product general details updated successfully!');

        $this->assertEquals('PRD-1', Product::first()->code);
        $this->assertEquals('Product 1', Product::first()->name);
        $this->assertNotEquals('PRD-1', $product->code);
        $this->assertNotEquals('Product 1', $product->name);
    }

    /** @test */
    function super_administrator_can_temporarily_delete_a_product()
    {
        $products = factory(Product::class, 3)->create();

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.products.delete', $products->first()->id));

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Product deleted temporarily!');
        $this->assertEquals($result->location, route('admin.products'));

        $this->assertCount(3, Product::withTrashed()->get());
        $this->assertCount(2, Product::all());
        $this->assertCount(1, Product::onlyTrashed()->get());
    }

    /** @test */
    function super_administrator_cannot_delete_a_product_that_does_not_exists()
    {
        $products = factory(Product::class, 3)->create();

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.products.delete', $products->last()->id + 10));

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Product with that id cannot be found.');

        $this->assertCount(3, Product::withTrashed()->get());
        $this->assertCount(3, Product::all());
        $this->assertCount(0, Product::onlyTrashed()->get());
    }

    /** @test */
    function super_administrator_can_restore_a_temporarily_deleted_product()
    {
        factory(Product::class, 3)->create();
        $product = factory(Product::class)->create(['deleted_at' => \Carbon\Carbon::now()]);

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.products.restore', $product->id));

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Product restored successfully!');
        $this->assertEquals($result->location, route('admin.products'));

        $this->assertCount(4, Product::withTrashed()->get());
        $this->assertCount(4, Product::all());
        $this->assertCount(0, Product::onlyTrashed()->get());
    }

    /** @test */
    function super_administrator_cannot_restore_a_temporarily_deleted_product_that_does_not_exists()
    {
        factory(Product::class, 3)->create();
        $product = factory(Product::class)->create(['deleted_at' => \Carbon\Carbon::now()]);

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.products.restore', 10));

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Product with that id cannot be found.');

        $this->assertCount(4, Product::withTrashed()->get());
        $this->assertCount(3, Product::all());
        $this->assertCount(1, Product::onlyTrashed()->get());
    }

    /** @test */
    function super_administrator_can_permanently_delete_a_temporarily_deleted_category()
    {
        factory(Product::class, 3)->create();
        $product = factory(Product::class)->create(['deleted_at' => \Carbon\Carbon::now()]);

        $this->assertCount(4, Product::withTrashed()->get());

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.products.destroy', $product->id));

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Product destroyed successfully!');
        $this->assertEquals($result->location, route('admin.products'));

        $this->assertCount(3, Product::withTrashed()->get());
        $this->assertCount(3, Product::all());
        $this->assertCount(0, Product::onlyTrashed()->get());
    }

    /** @test */
    function super_administrator_cannot_permanently_delete_a_temporarily_deleted_product_that_does_not_exists()
    {
        $product = factory(Product::class, 3)->create();
        $product = factory(Product::class)->create(['deleted_at' => \Carbon\Carbon::now()]);

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.products.destroy', 10));

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Product with that id cannot be found.');

        $this->assertCount(4, Product::withTrashed()->get());
        $this->assertCount(3, Product::all());
        $this->assertCount(1, Product::onlyTrashed()->get());
    }
}
