<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use IndianIra\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductUpdateDetailedInformationTest extends TestCase
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
             ->get(route('admin.products.edit', $product->id) . '?detailed-information')
             ->assertStatus(200)
             ->assertViewIs('admin.products.edit')
             ->assertSee('Edit Detailed Information of Product: ' . $product->name);

        $this->assertTrue('detailed-information' == request()->exists('detailed-information'));
    }

    /** @test */
    function super_administrator_can_update_the_detailed_information_of_the_product()
    {
        $product = factory(Product::class)->create();

        $desc = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum, eligendi, et! Dolorum id deleniti in soluta facere perferendis tempora, quis a ducimus quae similique asperiores esse eaque animi, sint aperiam.';

        $formValues = array_merge($product->toArray(), [
            'description' => $desc
        ]);

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.products.updateDetailedInformation', $product->id), $formValues);

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Product detailed information updated successfully! Redirecting...');

        $this->assertEquals($desc, Product::first()->description);
        $this->assertNotEquals($desc, $product->description);
    }

    /** @test */
    function super_administrator_is_redirected_to_edit_the_meta_information_if_it_is_not_filled()
    {
        $product = factory(Product::class)->create();

        $desc = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum, eligendi, et! Dolorum id deleniti in soluta facere perferendis tempora, quis a ducimus quae similique asperiores esse eaque animi, sint aperiam.';

        $formValues = array_merge($product->toArray(), [
            'description' => $desc,
            'meta_title' => null,
            'meta_description' => null,
        ]);

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.products.updateDetailedInformation', $product->id), $formValues);

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Product detailed information updated successfully! Redirecting...');
        $this->assertEquals($result->location, route('admin.products.edit', $product->id) . '?meta-information');

        $this->assertNull(Product::first()->meta_title);
        $this->assertNull(Product::first()->meta_description);
    }

    /** @test */
    function super_administrator_is_redirected_to_products_index_if_it_is_meta_information_is_filled()
    {
        $product = factory(Product::class)->create();

        $desc = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum, eligendi, et! Dolorum id deleniti in soluta facere perferendis tempora, quis a ducimus quae similique asperiores esse eaque animi, sint aperiam.';

        $formValues = array_merge($product->toArray(), [
            'description' => $desc,
            'meta_title' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.',
            'meta_description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cupiditate nisi ut iusto velit quibusdam at fugit similique quia earum incidunt',
        ]);

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.products.updateDetailedInformation', $product->id), $formValues);

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Product detailed information updated successfully! Redirecting...');
        $this->assertEquals($result->location, route('admin.products'));

        $this->assertNotNull(Product::first()->meta_title);
        $this->assertNotNull(Product::first()->meta_description);
    }

    /** @test */
    function product_description_field_is_required()
    {
        $product = factory(Product::class)->create();

        $formValues = array_merge($product->toArray(), [
            'description' => ''
        ]);

        $this->withExceptionHandling()
             ->post(route('admin.products.updateDetailedInformation', $product->id), $formValues)
             ->assertSessionHasErrors('description');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('description'),
            'The description field is required.'
        );
    }

    /** @test */
    function product_additional_notes_should_not_be_greater_than_3000_characters()
    {
        $product = factory(Product::class)->create();

        $notes = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste blanditiis sed suscipit nesciunt placeat veniam facere omnis, commodi quisquam rem repudiandae, saepe officiis dolores impedit expedita, sapiente assumenda voluptas tenetur. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste blanditiis sed suscipit nesciunt placeat veniam facere omnis, commodi quisquam rem repudiandae, saepe officiis dolores impedit expedita, sapiente assumenda voluptas tenetur. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste blanditiis sed suscipit nesciunt placeat veniam facere omnis, commodi quisquam rem repudiandae, saepe officiis dolores impedit expedita, sapiente assumenda voluptas tenetur. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste blanditiis sed suscipit nesciunt placeat veniam facere omnis, commodi quisquam rem repudiandae, saepe officiis dolores impedit expedita, sapiente assumenda voluptas tenetur. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste blanditiis sed suscipit nesciunt placeat veniam facere omnis, commodi quisquam rem repudiandae, saepe officiis dolores impedit expedita, sapiente assumenda voluptas tenetur. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste blanditiis sed suscipit nesciunt placeat veniam facere omnis, commodi quisquam rem repudiandae, saepe officiis dolores impedit expedita, sapiente assumenda voluptas tenetur. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste blanditiis sed suscipit nesciunt placeat veniam facere omnis, commodi quisquam rem repudiandae, saepe officiis dolores impedit expedita, sapiente assumenda voluptas tenetur. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste blanditiis sed suscipit nesciunt placeat veniam facere omnis, commodi quisquam rem repudiandae, saepe officiis dolores impedit expedita, sapiente assumenda voluptas tenetur. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste blanditiis sed suscipit nesciunt placeat veniam facere omnis, commodi quisquam rem repudiandae, saepe officiis dolores impedit expedita, sapiente assumenda voluptas tenetur. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste blanditiis sed suscipit nesciunt placeat veniam facere omnis, commodi quisquam rem repudiandae, saepe officiis dolores impedit expedita, sapiente assumenda voluptas tenetur. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste blanditiis sed suscipit nesciunt placeat veniam facere omnis, commodi quisquam rem repudiandae, saepe officiis dolores impedit expedita, sapiente assumenda voluptas tenetur. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste blanditiis sed suscipit nesciunt placeat veniam facere omnis, commodi quisquam rem repudiandae, saepe officiis dolores impedit expedita, sapiente assumenda voluptas tenetur. Iste blanditiis sed suscipit nesciunt placeat veniam facere omnis, commodi quisquam rem repudiandae, saepe officiis dolores impedit expedita, sapiente assumenda voluptas tenetur. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste blanditiis sed suscipit nesciunt placeat veniam facere omnis, commodi quisquam rem repudiandae, saepe officiis dolores impedit expedita, sapiente assumenda voluptas tenetur.';

        $formValues = array_merge($product->toArray(), [
            'additional_notes' => $notes
        ]);

        $this->withExceptionHandling()
             ->post(route('admin.products.updateDetailedInformation', $product->id), $formValues)
             ->assertSessionHasErrors('additional_notes');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('additional_notes'),
            'The additional notes may not be greater than 3000 characters.'
        );
    }

    /** @test */
    function product_terms_field_should_not_be_greater_than_3000_characters()
    {
        $product = factory(Product::class)->create();

        $terms = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste blanditiis sed suscipit nesciunt placeat veniam facere omnis, commodi quisquam rem repudiandae, saepe officiis dolores impedit expedita, sapiente assumenda voluptas tenetur. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste blanditiis sed suscipit nesciunt placeat veniam facere omnis, commodi quisquam rem repudiandae, saepe officiis dolores impedit expedita, sapiente assumenda voluptas tenetur. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste blanditiis sed suscipit nesciunt placeat veniam facere omnis, commodi quisquam rem repudiandae, saepe officiis dolores impedit expedita, sapiente assumenda voluptas tenetur. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste blanditiis sed suscipit nesciunt placeat veniam facere omnis, commodi quisquam rem repudiandae, saepe officiis dolores impedit expedita, sapiente assumenda voluptas tenetur. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste blanditiis sed suscipit nesciunt placeat veniam facere omnis, commodi quisquam rem repudiandae, saepe officiis dolores impedit expedita, sapiente assumenda voluptas tenetur. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste blanditiis sed suscipit nesciunt placeat veniam facere omnis, commodi quisquam rem repudiandae, saepe officiis dolores impedit expedita, sapiente assumenda voluptas tenetur. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste blanditiis sed suscipit nesciunt placeat veniam facere omnis, commodi quisquam rem repudiandae, saepe officiis dolores impedit expedita, sapiente assumenda voluptas tenetur. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste blanditiis sed suscipit nesciunt placeat veniam facere omnis, commodi quisquam rem repudiandae, saepe officiis dolores impedit expedita, sapiente assumenda voluptas tenetur. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste blanditiis sed suscipit nesciunt placeat veniam facere omnis, commodi quisquam rem repudiandae, saepe officiis dolores impedit expedita, sapiente assumenda voluptas tenetur. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste blanditiis sed suscipit nesciunt placeat veniam facere omnis, commodi quisquam rem repudiandae, saepe officiis dolores impedit expedita, sapiente assumenda voluptas tenetur. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste blanditiis sed suscipit nesciunt placeat veniam facere omnis, commodi quisquam rem repudiandae, saepe officiis dolores impedit expedita, sapiente assumenda voluptas tenetur. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste blanditiis sed suscipit nesciunt placeat veniam facere omnis, commodi quisquam rem repudiandae, saepe officiis dolores impedit expedita, sapiente assumenda voluptas tenetur. Iste blanditiis sed suscipit nesciunt placeat veniam facere omnis, commodi quisquam rem repudiandae, saepe officiis dolores impedit expedita, sapiente assumenda voluptas tenetur. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste blanditiis sed suscipit nesciunt placeat veniam facere omnis, commodi quisquam rem repudiandae, saepe officiis dolores impedit expedita, sapiente assumenda voluptas tenetur.';

        $formValues = array_merge($product->toArray(), [
            'terms' => $terms
        ]);

        $this->withExceptionHandling()
             ->post(route('admin.products.updateDetailedInformation', $product->id), $formValues)
             ->assertSessionHasErrors('terms');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('terms'),
            'The terms may not be greater than 3000 characters.'
        );
    }
}
