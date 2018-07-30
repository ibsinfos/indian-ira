<?php

namespace Tests\Feature\Admin\Products;

use Tests\TestCase;
use IndianIra\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use IndianIra\ProductPriceAndOption;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductPriceAndOptionsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->signInSuperAdministrator();
    }

    /** @test */
    function only_logged_in_super_administrator_can_access_the_products_price_and_options_section()
    {
        $product = factory(Product::class)->create();

        // Logout the Super Administrator
        auth()->logout();

        $this->withoutExceptionHandling()
             ->get(route('admin.products.priceAndOptions', $product->id))
             ->assertStatus(302)
             ->assertRedirect(route('admin.login'));
    }

    /** @test */
    function super_administrator_can_view_the_products_price_and_options_section()
    {
        $product = factory(Product::class)->create();
        $product->categories(factory(Category::class)->create(['display' => 'Enabled'])->id);

        $this->withoutExceptionHandling()
             ->get(route('admin.products.priceAndOptions', $product->id))
             ->assertViewIs('admin.products-price-and-options.index')
             ->assertSee($product->name . ' - Prices and Options');
    }

    /** @test */
    function super_administrator_cannot_view_the_products_price_and_options_section_if_product_not_found()
    {
        $product = factory(Product::class)->create();

        $this->withExceptionHandling()
             ->get(route('admin.products.priceAndOptions', 50))
             ->assertStatus(404);
    }

    /** @test */
    function super_administrator_can_add_products_price_and_options_to_a_product()
    {
        $product = factory(Product::class)->create(['number_of_options' => 2]);

        $response = $this->withoutExceptionHandling()
                        ->post(route('admin.products.priceAndOptions.store', $product->id), [
                            'option_code'      => 'PRD-' . $product->id . '-OPT-' . time() . '-' . mt_rand(1000, 9999),
                            'option_1_heading' => 'Header 1',
                            'option_1_value'   => 'Value 1',
                            'option_2_heading' => 'Header 2',
                            'option_2_value'   => 'Value 2',
                            'discount_price'   => 0.0,
                            'selling_price'    => 0.0,
                            'stock'            => 0,
                            'sort_number'      => 0,
                            'weight'           => 0.0,
                            'display'          => 'Enabled',
                            'image'            => null
                        ]);

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Product prices and options added successfully!');
        $this->assertEquals($result->location, route('admin.products.priceAndOptions', $product->id));

        $this->assertNotNull(ProductPriceAndOption::first());
    }

    /** @test */
    function super_administrator_can_update_products_price_and_options_of_existing_record()
    {
        $option = factory(ProductPriceAndOption::class)->create();
        $option2 = factory(ProductPriceAndOption::class)->make();

        $formValues = array_merge($option2->toArray(), [
            'display' => 'Disabled',
            'selling_price' => 0.0,
            'image_file' => UploadedFile::fake()->image('image.jpg', 600, 600)
        ]);

        $response = $this->withoutExceptionHandling()
                        ->postJson(route('admin.products.priceAndOptions.update', [$option->product_id, $option->id]), $formValues);

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Product price and option updated successfully!');

        $this->assertEquals(number_format(ProductPriceAndOption::first()->selling_price, 2), "0.00");
        $this->assertEquals(ProductPriceAndOption::first()->display, 'Disabled');

        $this->assertNotEquals($option->selling_price, ProductPriceAndOption::first()->selling_price);
        $this->assertNotEquals($option->display, ProductPriceAndOption::first()->display);
    }

    /** @test */
    function super_administrator_cannot_update_products_price_and_options_of_non_existing_record()
    {
        $option = factory(ProductPriceAndOption::class)->create();
        $option2 = factory(ProductPriceAndOption::class)->make();

        $formValues = array_merge($option2->toArray(), [
            'display' => 'Disabled',
            'selling_price' => 0.0,
            'image_file' => UploadedFile::fake()->image('image.jpg', 600, 600)
        ]);

        $response = $this->withoutExceptionHandling()
                        ->post(route('admin.products.priceAndOptions.update', [$option->product_id, 58]), $formValues);

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Product price and option with that id could not be found!');
    }

    /** @test */
    function super_administrator_can_permanently_delete_a_product_price_and_option_data()
    {
        $options = factory(ProductPriceAndOption::class, 5)->create();

        $this->assertCount(5, ProductPriceAndOption::all());

        $productAndOptionId = [$options->last()->product_id, $options->last()->id];
        $response = $this->withoutExceptionHandling()
                        ->get(route('admin.products.priceAndOptions.destroy', $productAndOptionId));

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Product price and option destroyed successfully!');

        $this->assertCount(4, ProductPriceAndOption::all());
    }

    /** @test */
    function super_administrator_cannot_delete_products_price_and_options_of_non_existing_record()
    {
        $options = factory(ProductPriceAndOption::class, 5)->create();

        $response = $this->withoutExceptionHandling()
                        ->post(route('admin.products.priceAndOptions.update', [$options->first()->product_id, 15]));

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Product price and option with that id could not be found!');

        $this->assertCount(5, ProductPriceAndOption::all());
    }

    /** @test */
    function option_code_field_is_required()
    {
        $option = factory(ProductPriceAndOption::class)->make();
        $formValues = array_merge($option->toArray(), ['option_code' => '']);

        $this->post(route('admin.products.priceAndOptions.store', [$option->product_id, $option->id]), $formValues)
            ->assertSessionHasErrors('option_code');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('option_code'),
            'The option code field is required.'
        );
    }

    /** @test */
    function option_code_should_contain_only_alphabets_or_numbers_or_hyphens_or_underscores()
    {
        $option = factory(ProductPriceAndOption::class)->make();
        $formValues = array_merge($option->toArray(), ['option_code' => 'JDF*/*@$%dzfv jayfed']);

        $this->post(route('admin.products.priceAndOptions.store', [$option->product_id, $option->id]), $formValues)
            ->assertSessionHasErrors('option_code');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('option_code'),
            'The option code may only contain letters, numbers, dashes and underscores.'
        );
    }

    /** @test */
    function option_code_should_be_unique()
    {
        $alreadyTaken = factory(ProductPriceAndOption::class)->create();
        $option = factory(ProductPriceAndOption::class)->make();
        $formValues = array_merge($option->toArray(), ['option_code' => $alreadyTaken->option_code]);

        $this->post(route('admin.products.priceAndOptions.store', [$option->product_id, $option->id]), $formValues)
            ->assertSessionHasErrors('option_code');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('option_code'),
            'The option code has already been taken.'
        );
    }

    /** @test */
    function option_1_heading_field_should_contain_less_than_100_characters()
    {
        $option = factory(ProductPriceAndOption::class)->make();
        $formValues = array_merge($option->toArray(), ['option_1_heading' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repellendus corporis possimus explicabo ut odio reiciendis beatae consequuntur consectetur?']);

        $this->post(route('admin.products.priceAndOptions.store', [$option->product_id, $option->id]), $formValues)
            ->assertSessionHasErrors('option_1_heading');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('option_1_heading'),
            'The option 1 heading may not be greater than 100 characters.'
        );
    }

    /** @test */
    function option_1_value_field_should_contain_less_than_100_characters()
    {
        $option = factory(ProductPriceAndOption::class)->make();
        $formValues = array_merge($option->toArray(), ['option_1_value' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repellendus corporis possimus explicabo ut odio reiciendis beatae consequuntur consectetur?']);

        $this->post(route('admin.products.priceAndOptions.store', [$option->product_id, $option->id]), $formValues)
            ->assertSessionHasErrors('option_1_value');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('option_1_value'),
            'The option 1 value may not be greater than 100 characters.'
        );
    }

    /** @test */
    function option_2_heading_field_should_contain_less_than_100_characters()
    {
        $option = factory(ProductPriceAndOption::class)->make();
        $formValues = array_merge($option->toArray(), ['option_2_heading' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repellendus corporis possimus explicabo ut odio reiciendis beatae consequuntur consectetur?']);

        $this->post(route('admin.products.priceAndOptions.store', [$option->product_id, $option->id]), $formValues)
            ->assertSessionHasErrors('option_2_heading');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('option_2_heading'),
            'The option 2 heading may not be greater than 100 characters.'
        );
    }

    /** @test */
    function option_2_value_field_should_contain_less_than_100_characters()
    {
        $option = factory(ProductPriceAndOption::class)->make();
        $formValues = array_merge($option->toArray(), ['option_2_value' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repellendus corporis possimus explicabo ut odio reiciendis beatae consequuntur consectetur?']);

        $this->post(route('admin.products.priceAndOptions.store', [$option->product_id, $option->id]), $formValues)
            ->assertSessionHasErrors('option_2_value');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('option_2_value'),
            'The option 2 value may not be greater than 100 characters.'
        );
    }

    /** @test */
    function selling_price_field_is_required()
    {
        $option = factory(ProductPriceAndOption::class)->make();
        $formValues = array_merge($option->toArray(), ['selling_price' => '']);

        $this->post(route('admin.products.priceAndOptions.store', [$option->product_id, $option->id]), $formValues)
            ->assertSessionHasErrors('selling_price');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('selling_price'),
            'The selling price field is required.'
        );
    }

    /** @test */
    function selling_price_field_should_contain_only_nummbers_upto_2_decimals_only()
    {
        $option = factory(ProductPriceAndOption::class)->make();
        $formValues = array_merge($option->toArray(), ['selling_price' => '28413.52841']);

        $this->post(route('admin.products.priceAndOptions.store', [$option->product_id, $option->id]), $formValues)
            ->assertSessionHasErrors('selling_price');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('selling_price'),
            'The selling price should contain only numbers upto 2 precisions.'
        );
    }

    /** @test */
    function discount_price_field_should_contain_only_nummbers_upto_2_decimals_only()
    {
        $option = factory(ProductPriceAndOption::class)->make();
        $formValues = array_merge($option->toArray(), ['discount_price' => '28413.52841']);

        $this->post(route('admin.products.priceAndOptions.store', [$option->product_id, $option->id]), $formValues)
            ->assertSessionHasErrors('discount_price');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('discount_price'),
            'The discount price should contain only numbers upto 2 precisions.'
        );
    }

    /** @test */
    function stock_field_is_required()
    {
        $option = factory(ProductPriceAndOption::class)->make();
        $formValues = array_merge($option->toArray(), ['stock' => '']);

        $this->post(route('admin.products.priceAndOptions.store', [$option->product_id, $option->id]), $formValues)
            ->assertSessionHasErrors('stock');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('stock'),
            'The stock field is required.'
        );
    }

    /** @test */
    function stock_should_be_an_integer()
    {
        $option = factory(ProductPriceAndOption::class)->make();
        $formValues = array_merge($option->toArray(), ['stock' => 'zdgbted']);

        $this->post(route('admin.products.priceAndOptions.store', [$option->product_id, $option->id]), $formValues)
            ->assertSessionHasErrors('stock');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('stock'),
            'The stock must be an integer.'
        );
    }

    /** @test */
    function stock_should_cannot_be_less_than_0()
    {
        $option = factory(ProductPriceAndOption::class)->make();
        $formValues = array_merge($option->toArray(), ['stock' => -5]);

        $this->post(route('admin.products.priceAndOptions.store', [$option->product_id, $option->id]), $formValues)
            ->assertSessionHasErrors('stock');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('stock'),
            'The stock must be at least 0.'
        );
    }

    /** @test */
    function weight_field_is_required()
    {
        $option = factory(ProductPriceAndOption::class)->make();
        $formValues = array_merge($option->toArray(), ['weight' => '']);

        $this->post(route('admin.products.priceAndOptions.store', [$option->product_id, $option->id]), $formValues)
            ->assertSessionHasErrors('weight');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('weight'),
            'The weight field is required.'
        );
    }

    /** @test */
    function weight_field_should_contain_only_nummbers_upto_2_decimals_only()
    {
        $option = factory(ProductPriceAndOption::class)->make();
        $formValues = array_merge($option->toArray(), ['weight' => '28413.52841']);

        $this->post(route('admin.products.priceAndOptions.store', [$option->product_id, $option->id]), $formValues)
            ->assertSessionHasErrors('weight');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('weight'),
            'The weight should contain only numbers upto 2 precisions.'
        );
    }

    /** @test */
    function display_field_is_required()
    {
        $option = factory(ProductPriceAndOption::class)->make();
        $formValues = array_merge($option->toArray(), ['display' => '']);

        $this->post(route('admin.products.priceAndOptions.store', [$option->product_id, $option->id]), $formValues)
            ->assertSessionHasErrors('display');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('display'),
            'The display field is required.'
        );
    }

    /** @test */
    function display_field_should_either_be_enabled_or_disabled()
    {
        $option = factory(ProductPriceAndOption::class)->make();
        $formValues = array_merge($option->toArray(), ['display' => 'kzxsugf,v']);

        $this->post(route('admin.products.priceAndOptions.store', [$option->product_id, $option->id]), $formValues)
            ->assertSessionHasErrors('display');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('display'),
            'The display should eiter be Enabled or Disabled.'
        );
    }

    /** @test */
    function option_sort_number_field_is_required()
    {
        $option = factory(ProductPriceAndOption::class)->make();

        $formValues = array_merge($option->toArray(), [
            'sort_number' => '',
        ]);

        $this->withExceptionHandling()
             ->post(route('admin.products.priceAndOptions.store', [$option->product_id, $option->id]), $formValues)
             ->assertSessionHasErrors('sort_number');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('sort_number'),
            'The sort number field is required.'
        );
    }

    /** @test */
    function option_sort_number_should_be_an_integer_only()
    {
        $option = factory(ProductPriceAndOption::class)->make();

        $formValues = array_merge($option->toArray(), [
            'sort_number' => 'szdsgefth@#%^',
        ]);

        $this->withExceptionHandling()
             ->post(route('admin.products.priceAndOptions.store', [$option->product_id, $option->id]), $formValues)
             ->assertSessionHasErrors('sort_number');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('sort_number'),
            'The sort number must be an integer.'
        );
    }

    /** @test */
    function option_sort_number_should_be_greater_than_zero()
    {
        $option = factory(ProductPriceAndOption::class)->make();

        $formValues = array_merge($option->toArray(), [
            'sort_number' => -5,
        ]);

        $this->withExceptionHandling()
             ->post(route('admin.products.priceAndOptions.store', [$option->product_id, $option->id]), $formValues)
             ->assertSessionHasErrors('sort_number');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('sort_number'),
            'The sort number must be at least 0.'
        );
    }

    /** @test */
    function image_file_should_be_an_actual_image_file()
    {
        $option = factory(ProductPriceAndOption::class)->make();

        $response = $this->withExceptionHandling()
                         ->post(route('admin.products.priceAndOptions.store', [$option->product_id, $option->id]), [
                            'image_file' => UploadedFile::fake()->create('image.txt')
                        ])
                         ->assertSessionHasErrors('image_file');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('image_file'),
            'The uploaded file should be an image.'
        );

        File::deleteDirectory(public_path('/images-products'));
    }

    /** @test */
    function image_file_should_have_extension_jpg_or_jpeg_or_png()
    {
        $option = factory(ProductPriceAndOption::class)->make();

        $response = $this->withExceptionHandling()
                         ->post(route('admin.products.priceAndOptions.store', [$option->product_id, $option->id]), [
                            'image_file' => UploadedFile::fake()->image('image.gif')
                        ])
                         ->assertSessionHasErrors('image_file');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('image_file'),
            'The uploaded image file must be a file of type: JPG, JPEG, PNG, jpg, jpeg, png.'
        );

        File::deleteDirectory(public_path('/images-products'));
    }

    /** @test */
    function image_file_should_be_less_than_600_kb_in_size()
    {
        $option = factory(ProductPriceAndOption::class)->make();

        $response = $this->withExceptionHandling()
                         ->post(route('admin.products.priceAndOptions.store', [$option->product_id, $option->id]), [
                            'image_file' => UploadedFile::fake()->create('image.jpg', 10000)
                        ])
                         ->assertSessionHasErrors('image_file');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('image_file'),
            'The uploaded image file may not be greater than 600 kilobytes.'
        );

        File::deleteDirectory(public_path('/images-products'));
    }

    /** @test */
    function the_image_should_be_between_500px_and_1280px_in_width_and_height()
    {
        $option = factory(ProductPriceAndOption::class)->make();

        $response = $this->withExceptionHandling()
                         ->post(route('admin.products.priceAndOptions.store', [$option->product_id, $option->id]), [
                            'image_file' => UploadedFile::fake()->image('image.png', 1500)
                        ])
                         ->assertSessionHasErrors('image_file');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('image_file'),
            'The uploaded image file should be between 500px and 1280px in width and height.'
        );

        File::deleteDirectory(public_path('/images-products'));
    }
}
