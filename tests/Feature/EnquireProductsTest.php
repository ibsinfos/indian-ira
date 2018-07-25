<?php

namespace Tests\Feature;

use Tests\TestCase;
use IndianIra\Product;
use IndianIra\EnquireProduct;
use Illuminate\Support\Facades\Mail;
use IndianIra\ProductPriceAndOption;
use IndianIra\Mail\ProductEnquiryReceived;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EnquireProductsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp()
    {
        parent::setUp();

        $this->generateSuperAdministrator();
    }

    /** @test */
    function no_enquiry_products_data_found()
    {
        $this->assertCount(0, EnquireProduct::all());
    }

    /** @test */
    function user_can_submit_an_enquiry_for_the_product()
    {
        $product = factory(Product::class)->create(['display' => 'Enabled']);
        $option = factory(ProductPriceAndOption::class)->create([
            'product_id' => $product->id, 'display' => 'Enabled'
        ]);
        $product->categories()->attach(factory(\IndianIra\Category::class)->create(['display' => 'Enabled'])->id);

        $formValues = $this->mergeEnquireData();

        $response = $this->withoutExceptionHandling()
                         ->post(
                            route('products.enquiry', [$product->code, $option->option_code]),
                            $formValues
                        );

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Product enquiry submitted successfully !');

        $this->assertNotNull(EnquireProduct::first());
        $this->assertCount(1, EnquireProduct::all());
    }

    /** @test */
    function admin_receives_the_mail_on_submitting_the_product_enquiry()
    {
        Mail::fake();

        $product = factory(Product::class)->create(['display' => 'Enabled']);
        $option = factory(ProductPriceAndOption::class)->create([
            'product_id' => $product->id, 'display' => 'Enabled'
        ]);
        $product->categories()->attach(factory(\IndianIra\Category::class)->create(['display' => 'Enabled'])->id);

        $this->withoutExceptionHandling()
             ->post(
                route('products.enquiry', [$product->code, $option->option_code]),
                $this->mergeEnquireData()
        );

        Mail::assertSent(ProductEnquiryReceived::class);
    }

    /** @test */
    function user_full_name_field_is_required()
    {
        $product = factory(Product::class)->create(['display' => 'Enabled']);
        $option = factory(ProductPriceAndOption::class)->create([
            'product_id' => $product->id, 'display' => 'Enabled'
        ]);
        $product->categories()->attach(factory(\IndianIra\Category::class)->create(['display' => 'Enabled'])->id);

        $formValues = $this->mergeEnquireData(['user_full_name' => '']);

        $this->withExceptionHandling()
             ->post(
                route('products.enquiry', [$product->code, $option->option_code]),
                $formValues)
             ->assertSessionHasErrors('user_full_name');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('user_full_name'),
            'The user full name field is required.'
        );
    }

    /** @test */
    function user_full_name_field_cannot_be_more_than_200_characters()
    {
        $product = factory(Product::class)->create(['display' => 'Enabled']);
        $option = factory(ProductPriceAndOption::class)->create([
            'product_id' => $product->id, 'display' => 'Enabled'
        ]);
        $product->categories()->attach(factory(\IndianIra\Category::class)->create(['display' => 'Enabled'])->id);

        $formValues = $this->mergeEnquireData(['user_full_name' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Tenetur necessitatibus aspernatur asperiores quam, nesciunt temporibus facere, magnam similique placeat rerum a mollitia, adipisci nulla, ea voluptatibus. Ratione, explicabo aut sapiente.']);

        $this->withExceptionHandling()
             ->post(
                route('products.enquiry', [$product->code, $option->option_code]),
                $formValues)
             ->assertSessionHasErrors('user_full_name');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('user_full_name'),
            'The user full name may not be greater than 200 characters.'
        );
    }

    /** @test */
    function user_email_field_is_required()
    {
        $product = factory(Product::class)->create(['display' => 'Enabled']);
        $option = factory(ProductPriceAndOption::class)->create([
            'product_id' => $product->id, 'display' => 'Enabled'
        ]);
        $product->categories()->attach(factory(\IndianIra\Category::class)->create(['display' => 'Enabled'])->id);

        $formValues = $this->mergeEnquireData(['user_email' => '']);

        $this->withExceptionHandling()
             ->post(
                route('products.enquiry', [$product->code, $option->option_code]),
                $formValues)
             ->assertSessionHasErrors('user_email');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('user_email'),
            'The user email field is required.'
        );
    }

    /** @test */
    function user_email_field_cannot_be_more_than_200_characters()
    {
        $product = factory(Product::class)->create(['display' => 'Enabled']);
        $option = factory(ProductPriceAndOption::class)->create([
            'product_id' => $product->id, 'display' => 'Enabled'
        ]);
        $product->categories()->attach(factory(\IndianIra\Category::class)->create(['display' => 'Enabled'])->id);

        $formValues = $this->mergeEnquireData([
            'user_email' => array_random($this->getInvalidEmailAddress())
        ]);

        $this->withExceptionHandling()
             ->post(
                route('products.enquiry', [$product->code, $option->option_code]),
                $formValues)
             ->assertSessionHasErrors('user_email');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('user_email'),
            'The user email must be a valid email address.'
        );
    }

    /** @test */
    function user_contact_number_field_is_required()
    {
        $product = factory(Product::class)->create(['display' => 'Enabled']);
        $option = factory(ProductPriceAndOption::class)->create([
            'product_id' => $product->id, 'display' => 'Enabled'
        ]);
        $product->categories()->attach(factory(\IndianIra\Category::class)->create(['display' => 'Enabled'])->id);

        $formValues = $this->mergeEnquireData([
            'user_contact_number' => ''
        ]);

        $this->withExceptionHandling()
             ->post(
                route('products.enquiry', [$product->code, $option->option_code]),
                $formValues)
             ->assertSessionHasErrors('user_contact_number');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('user_contact_number'),
            'The user contact number field is required.'
        );
    }

    /** @test */
    function user_contact_number_should_contain_only_numeric_values()
    {
        $product = factory(Product::class)->create(['display' => 'Enabled']);
        $option = factory(ProductPriceAndOption::class)->create([
            'product_id' => $product->id, 'display' => 'Enabled'
        ]);
        $product->categories()->attach(factory(\IndianIra\Category::class)->create(['display' => 'Enabled'])->id);

        $formValues = $this->mergeEnquireData([
            'user_contact_number' => 'sdkugz,hvn'
        ]);

        $this->withExceptionHandling()
             ->post(
                route('products.enquiry', [$product->code, $option->option_code]),
                $formValues)
             ->assertSessionHasErrors('user_contact_number');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('user_contact_number'),
            'The user contact number must be a number.'
        );
    }

    /** @test */
    function message_body_field_is_required()
    {
        $product = factory(Product::class)->create(['display' => 'Enabled']);
        $option = factory(ProductPriceAndOption::class)->create([
            'product_id' => $product->id, 'display' => 'Enabled'
        ]);
        $product->categories()->attach(factory(\IndianIra\Category::class)->create(['display' => 'Enabled'])->id);

        $formValues = $this->mergeEnquireData([
            'message_body' => ''
        ]);

        $this->withExceptionHandling()
             ->post(
                route('products.enquiry', [$product->code, $option->option_code]),
                $formValues)
             ->assertSessionHasErrors('message_body');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('message_body'),
            'The message body field is required.'
        );
    }

    /** @test */
    function message_body_field_cannot_be_more_than_1000_characters()
    {
        $product = factory(Product::class)->create(['display' => 'Enabled']);
        $option = factory(ProductPriceAndOption::class)->create([
            'product_id' => $product->id, 'display' => 'Enabled'
        ]);
        $product->categories()->attach(factory(\IndianIra\Category::class)->create(['display' => 'Enabled'])->id);

        $formValues = $this->mergeEnquireData([
            'message_body' => $this->faker()->paragraphs(10, true)
        ]);

        $this->withExceptionHandling()
             ->post(
                route('products.enquiry', [$product->code, $option->option_code]),
                $formValues)
             ->assertSessionHasErrors('message_body');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('message_body'),
            'The message body may not be greater than 1000 characters.'
        );
    }

    /**
     * Merge the form data for enquiry.
     *
     * @param   array  $attributes
     * @return  array
     */
    public function mergeEnquireData($attributes = [])
    {
        return array_merge([
            'user_full_name'      => 'John Doe',
            'user_email'          => 'john@example.com',
            'user_contact_number' => 9876543210,
            'message_body'        => 'Product enquire',
        ], $attributes);
    }

    /**
     * Get the list of invalid E-Mail Addresses.
     *
     * @return  array
     */
    private function getInvalidEmailAddress()
    {
        return [
            "plainaddress", "#@%^%#$@#$@#.com", "@example.com", "Joe Smith <email@example.com>",
            "email.example.com", "email@example@example.com", ".email@example.com", "email.@example.com",
            "email..email@example.com", "あいうえお@example.com", "email@example.com (Joe Smith)", "email@example",
            "email@-example.com", "email@111.222.333.44444", "email@example..com",
            "Abc..123@example.com"
        ];
    }
}
