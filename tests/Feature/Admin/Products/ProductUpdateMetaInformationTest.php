<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use IndianIra\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductUpdateMetaInformationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->signInSuperAdministrator();
    }

    /** @test */
    function super_administrator_sees_the_meta_information_editing_page()
    {
        $product = factory(Product::class)->create();

        $this->withoutExceptionHandling()
             ->get(route('admin.products.edit', $product->id) . '?meta-information')
             ->assertStatus(200)
             ->assertViewIs('admin.products.edit')
             ->assertSee('Edit Meta Information of Product: ' . $product->name);

        $this->assertTrue('meta-information' == request()->exists('meta-information'));
    }

    /** @test */
    function super_administrator_can_update_the_detailed_information_of_the_product()
    {
        $product = factory(Product::class)->create();

        $formValues = array_merge($product->toArray(), [
            'meta_title' => 'Product 1 ka meta title',
            'meta_description' => 'Product 1 ka meta description',
            'meta_keywords' => 'Product 1 k meta keywords',
        ]);

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.products.updateMetaInformation', $product->id), $formValues);

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Product meta information updated successfully!');

        $this->assertEquals('Product 1 ka meta title', Product::first()->meta_title);
        $this->assertEquals('Product 1 ka meta description', Product::first()->meta_description);
        $this->assertEquals('Product 1 k meta keywords', Product::first()->meta_keywords);
        $this->assertNotEquals('Product 1 ka meta title', $product->meta_title);
        $this->assertNotEquals('Product 1 ka meta description', $product->meta_description);
        $this->assertNotEquals('Product 1 k meta keywords', $product->meta_keywords);
    }

    /** @test */
    function meta_title_field_is_required()
    {
        $product = factory(Product::class)->create();
        $formValues = array_merge($product->toArray(), ['meta_title' => '']);

        $this->post(route('admin.products.updateMetaInformation', $product->id), $formValues)
            ->assertSessionHasErrors('meta_title');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('meta_title'),
            'The meta title field is required.'
        );
    }

    /** @test */
    function meta_title_should_be_less_than_60_characters()
    {
        $product = factory(Product::class)->create();
        $formValues = array_merge(
            $product->toArray(), [
                'meta_title' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit pariatur quos deserunt adipisci cumque.'
            ]
        );

        $this->post(route('admin.products.updateMetaInformation', $product->id), $formValues)
            ->assertSessionHasErrors('meta_title');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('meta_title'),
            'The meta title may not be greater than 60 characters.'
        );
    }

    /** @test */
    function meta_description_field_is_required()
    {
        $product = factory(Product::class)->create();
        $formValues = array_merge($product->toArray(), ['meta_description' => '']);

        $this->post(route('admin.products.updateMetaInformation', $product->id), $formValues)
            ->assertSessionHasErrors('meta_description');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('meta_description'),
            'The meta description field is required.'
        );
    }

    /** @test */
    function meta_description_should_be_less_than_160_characters()
    {
        $product = factory(Product::class)->create();
        $formValues = array_merge(
            $product->toArray(), [
                'meta_description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad reiciendis ab illo veritatis quaerat omnis accusamus enim excepturi voluptatibus, sapiente rem labore quasi magnam eos aliquid numquam eum ipsa, atque.'
            ]
        );

        $this->post(route('admin.products.updateMetaInformation', $product->id), $formValues)
            ->assertSessionHasErrors('meta_description');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('meta_description'),
            'The meta description may not be greater than 160 characters.'
        );
    }

    /** @test */
    function meta_keywords_should_be_less_than_250_characters()
    {
        $product = factory(Product::class)->create();
        $formValues = array_merge(
            $product->toArray(), [
                'meta_keywords' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit pariatur quos deserunt adipisci cumque, iure omnis modi, blanditiis vero, eveniet explicabo quae. Inventore adipisci repudiandae cumque laudantium ratione qui odio. Lorem ipsum dolor sit amet, consectetur adipisicing elit.'
            ]
        );

        $this->post(route('admin.products.updateMetaInformation', $product->id), $formValues)
            ->assertSessionHasErrors('meta_keywords');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('meta_keywords'),
            'The meta keywords may not be greater than 150 characters.'
        );
    }
}
