<?php

namespace Tests\Feature\Checkout;

use IndianIra\User;
use Tests\TestCase;
use IndianIra\Product;
use IndianIra\ProductPriceAndOption;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->generateSuperAdministrator();
    }

    /** @test */
    function redirect_to_home_page_if_no_products_are_added_in_cart()
    {
        $this->assertNull(session('cart'));

        $this->withoutExceptionHandling()
             ->get(route('checkout'))
             ->assertRedirect(route('homePage'));
    }

    /** @test */
    function products_are_already_added_in_cart()
    {
        $this->addProductsInCart();

        $this->assertNotNull(session('cart'));
        $this->assertCount(1, session('cart'));
    }

    /** @test */
    function display_the_register_form_if_user_is_a_guest()
    {
        $this->addProductsInCart();

        $this->withoutExceptionHandling()
             ->get(route('checkout'))
             ->assertViewIs('checkout.authentication')
             ->assertSee('New User: Register');
    }

    /** @test */
    function user_can_register_to_the_application_at_the_time_of_checkout()
    {
        $this->addProductsInCart();

        $formValues = [
            'first_name'       => 'User',
            'last_name'        => 'One',
            'username'         => 'user1',
            'email'            => 'user1@example.com',
            'password'         => 'Password',
            'confirm_password' => 'Password',
        ];

        $response = $this->withoutExceptionHandling()
                         ->post(route('checkout.register'), $formValues);

        $result = json_decode($response->getContent());

        $this->assertEquals('success', $result->status);
        $this->assertEquals('Success !', $result->title);
        $this->assertEquals('You have successfully registered.. Redirecting...', $result->message);
        $this->assertEquals(route('checkout.address'), $result->location);

        $this->assertTrue(auth()->id() == 2);
    }

    /** @test */
    function user_cannot_post_register_credentials_if_they_are_already_logged_in()
    {
        $this->addProductsInCart();

        $this->be($user = factory(User::class)->create());

        $response = $this->withoutExceptionHandling()
                        ->post(route('checkout.register'), []);

        $result = json_decode($response->getContent());

        $this->assertEquals('failed', $result->status);
        $this->assertEquals('Failed !', $result->title);
        $this->assertEquals('You are already logged in...', $result->message);

        $this->assertTrue($user->id == auth()->id());
    }

    /** @test */
    function first_name_field_is_required()
    {
        $this->addProductsInCart();

        $formValues = $this->registrationData(['first_name' => '']);

        $this->post(route('checkout.register'), $formValues)
            ->assertSessionHasErrors('first_name');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('first_name'),
            'The first name field is required.'
        );
    }

    /** @test */
    function first_name_field_should_contain_less_than_equal_to_100_characters()
    {
        $this->addProductsInCart();

        $formValues = $this->registrationData(['first_name' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.. Explicabo fugiat unde veritatis deserunt eos saepe!',]);

        $this->post(route('checkout.register'), $formValues)
            ->assertSessionHasErrors('first_name');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('first_name'),
            'The first name may not be greater than 100 characters.'
        );
    }

    /** @test */
    function last_name_field_is_required()
    {
        $this->addProductsInCart();

        $formValues = $this->registrationData(['last_name' => '']);

        $this->post(route('checkout.register'), $formValues)
            ->assertSessionHasErrors('last_name');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('last_name'),
            'The last name field is required.'
        );
    }

    /** @test */
    function last_name_field_should_contain_less_than_equal_to_100_characters()
    {
        $this->addProductsInCart();

        $formValues = $this->registrationData(['last_name' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.. Explicabo fugiat unde veritatis deserunt eos saepe!']);

        $this->post(route('checkout.register'), $formValues)
            ->assertSessionHasErrors('last_name');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('last_name'),
            'The last name may not be greater than 100 characters.'
        );
    }

    /** @test */
    function username_field_is_required()
    {
        $this->addProductsInCart();

        $formValues = $this->registrationData(['username' => '']);

        $this->post(route('checkout.register'), $formValues)
            ->assertSessionHasErrors('username');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('username'),
            'The username field is required.'
        );
    }

    /** @test */
    function username_field_should_contain_less_than_equal_to_50_characters()
    {
        $this->addProductsInCart();

        $formValues = $this->registrationData([
            'username' => 'Lorem_ipsum_dolor_sit_amet_consectetur_adipisicing_elit'
        ]);

        $this->post(route('checkout.register'), $formValues)
            ->assertSessionHasErrors('username');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('username'),
            'The username may not be greater than 50 characters.'
        );
    }

    /** @test */
    function username_field_should_contain_only_numbers_alphaabets_underscores_and_hyphens()
    {
        $this->addProductsInCart();

        $formValues = $this->registrationData(['username' => '^Lorem_ipsum dolor!']);

        $this->post(route('checkout.register'), $formValues)
            ->assertSessionHasErrors('username');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('username'),
            'The username may only contain letters, numbers, dashes and underscores.'
        );
    }

    /** @test */
    function username_should_be_unique()
    {
        factory(User::class)->create(['username' => 'user1']);

        $this->addProductsInCart();

        $formValues = $this->registrationData(['username' => 'user1']);

        $this->post(route('checkout.register'), $formValues)
            ->assertSessionHasErrors('username');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('username'),
            'The username has already been taken.'
        );
    }

    /** @test */
    function email_field_is_required()
    {
        $this->addProductsInCart();

        $formValues = $this->registrationData(['email' => '']);

        $this->post(route('checkout.register'), $formValues)
            ->assertSessionHasErrors('email');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('email'),
            'The email field is required.'
        );
    }

    /** @test */
    function email_should_be_a_valid_email_address()
    {
        $this->addProductsInCart();

        $formValues = $this->registrationData([
            'email' => array_random($this->getInvalidEmailAddress())
        ]);

        $this->post(route('checkout.register'), $formValues)
            ->assertSessionHasErrors('email');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('email'),
            'The email must be a valid email address.'
        );
    }

    /** @test */
    function email_should_be_unique()
    {
        factory(User::class)->create(['email' => 'user1@example.com']);

        $this->addProductsInCart();

        $formValues = $this->registrationData(['email' => 'user1@example.com']);

        $this->post(route('checkout.register'), $formValues)
            ->assertSessionHasErrors('email');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('email'),
            'The email has already been taken.'
        );
    }

    /** @test */
    function password_field_is_required()
    {
        $this->addProductsInCart();

        $formValues = $this->registrationData(['password' => '']);

        $this->post(route('checkout.register'), $formValues)
            ->assertSessionHasErrors('password');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('password'),
            'The password field is required.'
        );
    }

    /** @test */
    function confirm_password_field_is_required()
    {
        $this->addProductsInCart();

        $formValues = $this->registrationData(['confirm_password' => '']);

        $this->post(route('checkout.register'), $formValues)
            ->assertSessionHasErrors('confirm_password');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('confirm_password'),
            'The confirm password field is required.'
        );
    }

    /** @test */
    function confirm_password_and_password_should_match()
    {
        $this->addProductsInCart();

        $formValues = $this->registrationData(['confirm_password' => 'Secret']);

        $this->post(route('checkout.register'), $formValues)
            ->assertSessionHasErrors('confirm_password');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('confirm_password'),
            'The confirm password and password must match.'
        );
    }

    /** @test */
    function contact_number_should_contain_only_numeric_values()
    {
        $this->addProductsInCart();

        $formValues = $this->registrationData(['contact_number' => 'tytjykujndzfv']);

        $this->post(route('checkout.register'), $formValues)
            ->assertSessionHasErrors('contact_number');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('contact_number'),
            'The contact number must be a number.'
        );
    }

    /**
     * Add the products in the cart.
     *
     */
    protected function addProductsInCart()
    {
        $product = factory(Product::class)->create(['number_of_options' => 0, 'display' => 'Enabled']);
        $option = factory(ProductPriceAndOption::class)->create([
            'product_id'    => $product->id,
            'display'       => 'Enabled',
            'selling_price' => 250.00,
        ]);

        session(['cart' => collect()->put($product->code, [
            'product'       => $product,
            'options'       => $option,
            'quantity'      => 1,
            'selling_price' => $option->selling_price,
            'product_total' => $option->selling_price,
        ])]);
    }

    /**
     * Fake user registration data.
     *
     * @param  array  $attributes
     */
    protected function registrationData($attributes = [])
    {
        return array_merge([
            'first_name'       => 'User',
            'last_name'        => 'One',
            'username'         => 'user1',
            'email'            => 'user1@example.com',
            'password'         => 'Password',
            'confirm_password' => 'Password',
            'contact_number'   => '9876543210'
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
