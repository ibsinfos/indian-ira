<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use IndianIra\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateInterRelatedProductsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->signInSuperAdministrator();
    }

    /** @test */
    function super_administrator_sees_the_inter_related_products_editing_page()
    {
        $product = factory(Product::class)->create();

        $this->withoutExceptionHandling()
             ->get(route('admin.products.edit', $product->id) . '?inter-related')
             ->assertStatus(200)
             ->assertViewIs('admin.products.edit')
             ->assertSee('Edit Inter-related Product: ' . $product->name);

        $this->assertTrue('inter-related' == request()->exists('inter-related'));
    }

    /** @test */
    function super_administrator_can_update_the_inter_related_products_of_the_product()
    {
        $product = factory(Product::class)->create();

        $products = factory(Product::class, 10)->create(['display' => 'Enabled']);

        $response = $this->withoutExceptionHandling()
                         ->post(
                            route('admin.products.updateInterRelatedProducts', $product->id),
                            ['product_id' => $products->shuffle()->take(5)->pluck('id')->toArray()]
                        );

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Inter related products updated successfully! Redirecting...');

        $this->assertCount(5, Product::first()->interRelated);
    }

    /** @test */
    function select_inter_related_products_field_is_required()
    {
        $product = factory(Product::class)->create();
        $formValues = ['product_id' => ''];

        $this->post(route('admin.products.updateInterRelatedProducts', $product->id), $formValues)
            ->assertSessionHasErrors('product_id');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('product_id'),
            'Select the products that you wish to relate / associate to this product.'
        );
    }

    /** @test */
    function select_inter_related_products_field_should_be_an_array()
    {
        $product = factory(Product::class)->create();
        $formValues = ['product_id' => 'zkdu,gjfvn'];

        $this->post(route('admin.products.updateInterRelatedProducts', $product->id), $formValues)
            ->assertSessionHasErrors('product_id');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('product_id'),
            'The products selected should be an array of products.'
        );
    }
}
