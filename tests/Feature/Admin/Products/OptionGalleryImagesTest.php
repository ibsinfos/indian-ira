<?php

namespace Tests\Feature\Admin\Products;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use IndianIra\ProductPriceAndOption;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OptionGalleryImagesTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp()
    {
        parent::setUp();

        $this->signInSuperAdministrator();
    }

    /** @test */
    function super_administrator_may_add_gallery_image_file_1()
    {
        $option = factory(ProductPriceAndOption::class)->make();

        $formValues = array_merge($option->toArray(), [
            'image'                => UploadedFile::fake()->image('image.jpg', 1000, 1000),
            'gallery_image_file_1' => UploadedFile::fake()->image('gallery-image.jpg', 1000, 1000),
            'gallery_image_file_2' => null,
            'gallery_image_file_3' => null,
        ]);

        $response = $this->withoutExceptionHandling()
             ->post(
                route('admin.products.priceAndOptions.store', [$option->product_id, $option->id]),
                $formValues
            );
        $result = json_decode($response->getContent());

        $imageNames = explode('; ', ProductPriceAndOption::first()->gallery_image_1);

        $this->assertEquals('/images-products/gallery-image-cart.jpg', $imageNames[0]);
        $this->assertEquals('/images-products/gallery-image-zoomed.jpg', $imageNames[1]);

        $this->assertFileExists(public_path().$imageNames[0]);
        $this->assertFileExists(public_path().$imageNames[1]);

        File::deleteDirectory(public_path('/images-products'));

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Product prices and options added successfully!');
    }

    /** @test */
    function super_administrator_may_add_gallery_image_file_2()
    {
        $option = factory(ProductPriceAndOption::class)->make();

        $formValues = array_merge($option->toArray(), [
            'gallery_image_file_2' => UploadedFile::fake()->image('gallery-image.jpg', 1000, 1000),
        ]);

        $response = $this->withoutExceptionHandling()
             ->post(
                route('admin.products.priceAndOptions.store', [$option->product_id, $option->id]),
                $formValues
            );
        $result = json_decode($response->getContent());

        $imageNames = explode('; ', ProductPriceAndOption::first()->gallery_image_2);

        $this->assertEquals('/images-products/gallery-image-cart.jpg', $imageNames[0]);
        $this->assertEquals('/images-products/gallery-image-zoomed.jpg', $imageNames[1]);

        $this->assertFileExists(public_path().$imageNames[0]);
        $this->assertFileExists(public_path().$imageNames[1]);

        File::deleteDirectory(public_path('/images-products'));

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Product prices and options added successfully!');
    }

    /** @test */
    function super_administrator_may_add_gallery_image_file_3()
    {
        $option = factory(ProductPriceAndOption::class)->make();

        $formValues = array_merge($option->toArray(), [
            'gallery_image_file_3' => UploadedFile::fake()->image('gallery-image.jpg', 1000, 1000),
        ]);

        $response = $this->withoutExceptionHandling()
             ->post(
                route('admin.products.priceAndOptions.store', [$option->product_id, $option->id]),
                $formValues
            );
        $result = json_decode($response->getContent());

        $imageNames = explode('; ', ProductPriceAndOption::first()->gallery_image_3);

        $this->assertEquals('/images-products/gallery-image-cart.jpg', $imageNames[0]);
        $this->assertEquals('/images-products/gallery-image-zoomed.jpg', $imageNames[1]);

        $this->assertFileExists(public_path().$imageNames[0]);
        $this->assertFileExists(public_path().$imageNames[1]);

        File::deleteDirectory(public_path('/images-products'));

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Product prices and options added successfully!');
    }

    /** @test */
    function gallery_image_file_1_should_be_an_actual_image_file()
    {
        $option = factory(ProductPriceAndOption::class)->make();

        $formValues = array_merge($option->toArray(), [
            'gallery_image_file_1' => UploadedFile::fake()->create('image.txt')
        ]);

        $response = $this->withExceptionHandling()
                         ->post(
                            route('admin.products.priceAndOptions.store', [$option->product_id, $option->id]),
                            $formValues
                        )
                         ->assertSessionHasErrors('gallery_image_file_1');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('gallery_image_file_1'),
            'The uploaded gallery image file 1 should be an image.'
        );

        File::deleteDirectory(public_path('/images-products'));
    }

    /** @test */
    function gallery_image_file_1_should_have_extension_jpg_or_jpeg_or_png()
    {
        $option = factory(ProductPriceAndOption::class)->make();

        $formValues = array_merge($option->toArray(), [
            'gallery_image_file_1' => UploadedFile::fake()->create('image.gif')
        ]);

        $response = $this->withExceptionHandling()
                         ->post(
                            route('admin.products.priceAndOptions.store', [$option->product_id, $option->id]),
                            $formValues
                        )
                         ->assertSessionHasErrors('gallery_image_file_1');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('gallery_image_file_1'),
            'The uploaded gallery image file 1 must be a file of type: JPG, JPEG, PNG, jpg, jpeg, png.'
        );

        File::deleteDirectory(public_path('/images-products'));
    }

    /** @test */
    function gallery_image_file_1_should_be_less_than_600_kb_in_size()
    {
        $option = factory(ProductPriceAndOption::class)->make();

        $formValues = array_merge($option->toArray(), [
            'gallery_image_file_1' => UploadedFile::fake()->create('image.jpg', 10000)
        ]);

        $response = $this->withExceptionHandling()
                         ->post(
                            route('admin.products.priceAndOptions.store', [$option->product_id, $option->id]),
                            $formValues
                        )
                         ->assertSessionHasErrors('gallery_image_file_1');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('gallery_image_file_1'),
            'The uploaded gallery image file 1 may not be greater than 600 kilobytes.'
        );

        File::deleteDirectory(public_path('/images-products'));
    }

    /** @test */
    function the_gallery_image_file_1_should_be_between_500px_and_1280px_in_width_and_height()
    {
        $option = factory(ProductPriceAndOption::class)->make();

        $formValues = array_merge($option->toArray(), [
            'gallery_image_file_1' => UploadedFile::fake()->image('image.png', 1500)
        ]);

        $response = $this->withExceptionHandling()
                         ->post(
                            route('admin.products.priceAndOptions.store', [$option->product_id, $option->id]),
                            $formValues
                        )
                         ->assertSessionHasErrors('gallery_image_file_1');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('gallery_image_file_1'),
            'The uploaded gallery image file 1 should be between 500px and 1280px in width and height.'
        );

        File::deleteDirectory(public_path('/images-products'));
    }

    /** @test */
    function gallery_image_file_2_should_be_an_actual_image_file()
    {
        $option = factory(ProductPriceAndOption::class)->make();

        $formValues = array_merge($option->toArray(), [
            'gallery_image_file_2' => UploadedFile::fake()->create('image.txt')
        ]);

        $response = $this->withExceptionHandling()
                         ->post(
                            route('admin.products.priceAndOptions.store', [$option->product_id, $option->id]),
                            $formValues
                        )
                         ->assertSessionHasErrors('gallery_image_file_2');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('gallery_image_file_2'),
            'The uploaded gallery image file 2 should be an image.'
        );

        File::deleteDirectory(public_path('/images-products'));
    }

    /** @test */
    function gallery_image_file_2_should_have_extension_jpg_or_jpeg_or_png()
    {
        $option = factory(ProductPriceAndOption::class)->make();

        $formValues = array_merge($option->toArray(), [
            'gallery_image_file_2' => UploadedFile::fake()->create('image.gif')
        ]);

        $response = $this->withExceptionHandling()
                         ->post(
                            route('admin.products.priceAndOptions.store', [$option->product_id, $option->id]),
                            $formValues
                        )
                         ->assertSessionHasErrors('gallery_image_file_2');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('gallery_image_file_2'),
            'The uploaded gallery image file 2 must be a file of type: JPG, JPEG, PNG, jpg, jpeg, png.'
        );

        File::deleteDirectory(public_path('/images-products'));
    }

    /** @test */
    function gallery_image_file_2_should_be_less_than_600_kb_in_size()
    {
        $option = factory(ProductPriceAndOption::class)->make();

        $formValues = array_merge($option->toArray(), [
            'gallery_image_file_2' => UploadedFile::fake()->create('image.jpg', 10000)
        ]);

        $response = $this->withExceptionHandling()
                         ->post(
                            route('admin.products.priceAndOptions.store', [$option->product_id, $option->id]),
                            $formValues
                        )
                         ->assertSessionHasErrors('gallery_image_file_2');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('gallery_image_file_2'),
            'The uploaded gallery image file 2 may not be greater than 600 kilobytes.'
        );

        File::deleteDirectory(public_path('/images-products'));
    }

    /** @test */
    function the_gallery_image_file_2_should_be_between_500px_and_1280px_in_width_and_height()
    {
        $option = factory(ProductPriceAndOption::class)->make();

        $formValues = array_merge($option->toArray(), [
            'gallery_image_file_2' => UploadedFile::fake()->image('image.png', 1500)
        ]);

        $response = $this->withExceptionHandling()
                         ->post(
                            route('admin.products.priceAndOptions.store', [$option->product_id, $option->id]),
                            $formValues
                        )
                         ->assertSessionHasErrors('gallery_image_file_2');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('gallery_image_file_2'),
            'The uploaded gallery image file 2 should be between 500px and 1280px in width and height.'
        );

        File::deleteDirectory(public_path('/images-products'));
    }
}
