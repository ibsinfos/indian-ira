<?php

namespace Tests\Feature\Admin\Products;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductSortingTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $category;
    protected $products;

    public function setUp()
    {
        parent::setUp();

        $this->signInSuperAdministrator();

        $this->category = factory(\IndianIra\Category::class)->create(['display' => 'Enabled']);
        $this->products = factory(\IndianIra\Product::class, 10)->create(['display' => 'Enabled']);

        $this->category->products()->attach(
            $this->products->pluck('id')->toArray()
        );
    }

    /** @test */
    function non_administrator_cannot_access_the_category_products_section()
    {
        auth()->logout();

        $this->withoutExceptionHandling()
             ->get(route('admin.categories.products', $this->category->id))
             ->assertStatus(302)
             ->assertRedirect(route('admin.login'));
    }

    /** @test */
    function only_administrator_can_access_the_category_products_section()
    {
        $this->withoutExceptionHandling()
             ->get(route('admin.categories.products', $this->category->id))
             ->assertViewIs('admin.categories-products.index')
             ->assertSee('List of All Products in the '. title_case($this->category->name));
    }

    /** @test */
    function super_administrator_can_update_the_sort_number_of_the_product()
    {
        $product = $this->products->random();

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.categories.products.updateSort', [$this->category->id, $product->id]), [
                            'sort_number' => 50
                         ]);

        $result = json_decode($response->getContent());

        $category = $this->category;
        $products = $this->category->getAllProducts();

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Product sort number updated successfully!');

        $this->assertNotEquals(50, $product->sort_number);
        $this->assertEquals(50, $product->fresh()->sort_number);
    }

    /** @test */
    function super_administrator_cannot_update_the_sort_number_of_the_product_of_non_category()
    {
        $product = $this->products->random();

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.categories.products.updateSort', [50, $product->id]), [
                            'sort_number' => 50
                         ]);

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Category with that id cannot be found!');
    }

    /** @test */
    function super_administrator_cannot_update_the_sort_number_of_the_product_that_does_not_exists()
    {
        $product = $this->products->random();

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.categories.products.updateSort', [$this->category->id, 50]), [
                            'sort_number' => 50
                         ]);

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Product with that id cannot be found!');
    }

    /** @test */
    function product_sort_number_field_is_required()
    {
        $product = $this->products->random();

        $this->withExceptionHandling()
             ->post(route('admin.categories.products.updateSort', [$this->category->id, $product->id]), [
                'sort_number' => ''
             ]);

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('sort_number'),
            'The sort number field is required.'
        );
    }

    /** @test */
    function product_sort_number_should_be_an_integer_only()
    {
        $product = $this->products->random();

        $this->withExceptionHandling()
             ->post(route('admin.categories.products.updateSort', [$this->category->id, $product->id]), [
                'sort_number' => '6834.8451'
             ]);

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('sort_number'),
            'The sort number must be an integer.'
        );
    }

    /** @test */
    function product_sort_number_should_be_greater_than_zero()
    {
        $product = $this->products->random();

        $this->withExceptionHandling()
             ->post(route('admin.categories.products.updateSort', [$this->category->id, $product->id]), [
                'sort_number' => -82
             ]);

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('sort_number'),
            'The sort number must be at least 0.'
        );
    }
}
