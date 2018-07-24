<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use IndianIra\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductUpdateImageTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->signInSuperAdministrator();
    }

    /** @test */
    function super_administrator_sees_the_product_image_editing_editing_page()
    {
        $product = factory(Product::class)->create();

        $this->withoutExceptionHandling()
             ->get(route('admin.products.edit', $product->id) . '?image')
             ->assertStatus(200)
             ->assertViewIs('admin.products.edit')
             ->assertSee('Edit Image of Product: ' . $product->name);

        $this->assertTrue('image' == request()->exists('image'));
    }

    /** @test */
    function super_administrator_can_update_the_image_of_the_product()
    {
        $product = factory(Product::class)->create();

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.products.updateImage', $product->id), [
                            'image' => UploadedFile::fake()->image('image.jpg', 1000, 1000)
                        ]);
        $result = json_decode($response->getContent());

        $imageNames = explode('; ', Product::first()->images);

        $this->assertEquals('/images-products/image-cart.jpg', $imageNames[0]);
        $this->assertEquals('/images-products/image-catalog.jpg', $imageNames[1]);
        $this->assertEquals('/images-products/image-zoomed.jpg', $imageNames[2]);

        $this->assertFileExists(public_path().$imageNames[0]);
        $this->assertFileExists(public_path().$imageNames[1]);
        $this->assertFileExists(public_path().$imageNames[2]);

        File::deleteDirectory(public_path('/images-products'));

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Product image updated successfully!');
    }

    /** @test */
    function redirect_to_inter_related_products_page_if_it_related_products_is_empty()
    {
        $product = factory(Product::class)->create();

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.products.updateImage', $product->id), [
                            'image' => UploadedFile::fake()->image('image.jpg', 1000, 1000)
                        ]);
        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Product image updated successfully!');
        $this->assertEquals($result->location,  route('admin.products.edit', $product->id) . '?inter-related');
    }

    /** @test */
    function redirect_to_inter_related_products_page_if_related_products_is_not_empty()
    {
        $product = factory(Product::class)->create();
        $products = factory(Product::class, 5)->create(['display' => 'Enabled']);

        $product->interRelated()->sync($products->pluck('id')->toArray());

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.products.updateImage', $product->id), [
                            'image' => UploadedFile::fake()->image('image.jpg', 1000, 1000)
                        ]);
        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Product image updated successfully!');
        $this->assertEquals($result->location,  route('admin.products'));
    }

    /** @test */
    function image_file_field_is_required()
    {
        $product = factory(Product::class)->create();

        $response = $this->withExceptionHandling()
                         ->post(route('admin.products.updateImage', $product->id), [
                            'image' => ''
                        ])
                         ->assertSessionHasErrors('image');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('image'),
            'The image field is required.'
        );

        File::deleteDirectory(public_path('/images-products'));
    }

    /** @test */
    function image_file_should_be_an_actual_image_file()
    {
        $product = factory(Product::class)->create();

        $response = $this->withExceptionHandling()
                         ->post(route('admin.products.updateImage', $product->id), [
                            'image' => UploadedFile::fake()->create('image.txt')
                        ])
                         ->assertSessionHasErrors('image');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('image'),
            'The uploaded file should be an image.'
        );

        File::deleteDirectory(public_path('/images-products'));
    }

    /** @test */
    function image_file_should_have_extension_jpg_or_jpeg_or_png()
    {
        $product = factory(Product::class)->create();

        $response = $this->withExceptionHandling()
                         ->post(route('admin.products.updateImage', $product->id), [
                            'image' => UploadedFile::fake()->image('image.gif')
                        ])
                         ->assertSessionHasErrors('image');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('image'),
            'The uploaded image file must be a file of type: JPG, JPEG, PNG, jpg, jpeg, png.'
        );

        File::deleteDirectory(public_path('/images-products'));
    }

    /** @test */
    function image_file_should_be_less_than_600_kb_in_size()
    {
        $product = factory(Product::class)->create();

        $response = $this->withExceptionHandling()
                         ->post(route('admin.products.updateImage', $product->id), [
                            'image' => UploadedFile::fake()->create('image.jpg', 10000)
                        ])
                         ->assertSessionHasErrors('image');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('image'),
            'The uploaded image file may not be greater than 600 kilobytes.'
        );

        File::deleteDirectory(public_path('/images-products'));
    }

    /** @test */
    function the_image_should_be_between_500px_and_1280px_in_width_and_height()
    {
        $product = factory(Product::class)->create();

        $response = $this->withExceptionHandling()
                         ->post(route('admin.products.updateImage', $product->id), [
                            'image' => UploadedFile::fake()->image('image.png', 1500)
                        ])
                         ->assertSessionHasErrors('image');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('image'),
            'The uploaded image file should be between 500px and 1280px in width and height.'
        );

        File::deleteDirectory(public_path('/images-products'));
    }
}
