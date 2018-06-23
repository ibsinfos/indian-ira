<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use IndianIra\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductUpdateGeneralDetailsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->signInSuperAdministrator();
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
    function product_code_field_is_required()
    {
        $product = factory(Product::class)->create();

        $formValues = array_merge($product->toArray(), [
            'code'              => '',
            'name'              => 'Product 1',
            'display'           => 'Enabled',
            'gst_percent'       => 10,
            'number_of_options' => 2
        ]);

        $this->withExceptionHandling()
             ->post(route('admin.products.updateGeneral', $product->id), $formValues)
             ->assertSessionHasErrors('code');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('code'),
            'The product code field is required.'
        );
    }

    /** @test */
    function product_code_field_should_only_contain_alphabets_or_numbers_or_dashes_or_underscores()
    {
        $product = factory(Product::class)->create();

        $formValues = array_merge($product->toArray(), [
            'code'              => 'JHGDF@#$%',
            'name'              => 'Product 1',
            'display'           => 'Enabled',
            'gst_percent'       => 10,
            'number_of_options' => 2
        ]);

        $this->withExceptionHandling()
             ->post(route('admin.products.updateGeneral', $product->id), $formValues)
             ->assertSessionHasErrors('code');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('code'),
            'The product code may only contain letters, numbers, dashes and underscores.'
        );
    }

    /** @test */
    function product_code_field_should_be_unique()
    {
        $product = factory(Product::class)->create();
        $product2 = factory(Product::class)->create();

        $formValues = array_merge($product->toArray(), [
            'code'              => $product2->code,
            'name'              => 'Product 1',
            'display'           => 'Enabled',
            'gst_percent'       => 10,
            'number_of_options' => 2
        ]);

        $this->withExceptionHandling()
             ->post(route('admin.products.updateGeneral', $product->id), $formValues)
             ->assertSessionHasErrors('code');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('code'),
            'The product code has already been taken.'
        );
    }

    /** @test */
    function product_name_field_is_required()
    {
        $product = factory(Product::class)->create();

        $formValues = array_merge($product->toArray(), [
            'name' => '',
        ]);

        $this->withExceptionHandling()
             ->post(route('admin.products.updateGeneral', $product->id), $formValues)
             ->assertSessionHasErrors('name');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('name'),
            'The product name field is required.'
        );
    }

    /** @test */
    function product_name_should_be_less_than_equal_to_100_characters()
    {
        $product = factory(Product::class)->create();

        $formValues = array_merge($product->toArray(), [
            'name' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Pariatur doloremque sint vel libero, tempora deleniti?',
        ]);

        $this->withExceptionHandling()
             ->post(route('admin.products.updateGeneral', $product->id), $formValues)
             ->assertSessionHasErrors('name');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('name'),
            'The product name should be less than equal to 100 characters.'
        );
    }

    /** @test */
    function product_display_field_is_required()
    {
        $product = factory(Product::class)->create();

        $formValues = array_merge($product->toArray(), [
            'display' => '',
        ]);

        $this->withExceptionHandling()
             ->post(route('admin.products.updateGeneral', $product->id), $formValues)
             ->assertSessionHasErrors('display');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('display'),
            'The product display field is required.'
        );
    }

    /** @test */
    function product_display_should_either_be_enabled_or_disabled()
    {
        $product = factory(Product::class)->create();

        $formValues = array_merge($product->toArray(), [
            'display' => 'lorem',
        ]);

        $this->withExceptionHandling()
             ->post(route('admin.products.updateGeneral', $product->id), $formValues)
             ->assertSessionHasErrors('display');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('display'),
            'The product display should either be Enabled or Disabled.'
        );
    }

    /** @test */
    function product_gst_percent_field_should_contain_only_numeric_values_upto_2_precisions_only()
    {
        $product = factory(Product::class)->create();

        $formValues = array_merge($product->toArray(), [
            'gst_percent' => '38941.05040',
        ]);

        $this->withExceptionHandling()
             ->post(route('admin.products.updateGeneral', $product->id), $formValues)
             ->assertSessionHasErrors('gst_percent');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('gst_percent'),
            'The product gst percent should contain only numeric values upto 2 precisions only.'
        );
    }

    /** @test */
    function product_number_of_options_field_is_required()
    {
        $product = factory(Product::class)->create();

        $formValues = array_merge($product->toArray(), [
            'number_of_options' => '',
        ]);

        $this->withExceptionHandling()
             ->post(route('admin.products.updateGeneral', $product->id), $formValues)
             ->assertSessionHasErrors('number_of_options');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('number_of_options'),
            'The product number of options field is required.'
        );
    }

    /** @test */
    function product_number_of_options_should_be_an_integer_only()
    {
        $product = factory(Product::class)->create();

        $formValues = array_merge($product->toArray(), [
            'number_of_options' => 'szdsgefth@#%^',
        ]);

        $this->withExceptionHandling()
             ->post(route('admin.products.updateGeneral', $product->id), $formValues)
             ->assertSessionHasErrors('number_of_options');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('number_of_options'),
            'The product number of options should be an integer.'
        );
    }

    /** @test */
    function product_number_of_options_should_be_between_0_and_2()
    {
        $product = factory(Product::class)->create();

        $formValues = array_merge($product->toArray(), [
            'number_of_options' => 10,
        ]);

        $this->withExceptionHandling()
             ->post(route('admin.products.updateGeneral', $product->id), $formValues)
             ->assertSessionHasErrors('number_of_options');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('number_of_options'),
            'The product number of options should be between 0 and 2.'
        );
    }
}
