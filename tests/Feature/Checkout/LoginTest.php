<?php

namespace Tests\Feature\Checkout;

use IndianIra\User;
use Tests\TestCase;
use IndianIra\Product;
use IndianIra\ProductPriceAndOption;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
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
    function display_the_login_form_if_user_is_a_guest()
    {
        $this->addProductsInCart();

        $this->withoutExceptionHandling()
             ->get(route('checkout'))
             ->assertViewIs('checkout.authentication')
             ->assertSee('Existing User: Login');
    }

    /** @test */
    function user_can_login_to_the_application_at_the_time_of_checkout()
    {
        $this->addProductsInCart();

        $user = factory(User::class)->create();

        $response = $this->withoutExceptionHandling()
                         ->post(route('checkout.postLogin'), [
                            'usernameOrEmail' => array_random([$user->username, $user->email]),
                            'password'        => 'Password'
                         ]);

        $result = json_decode($response->getContent());

        $this->assertEquals('success', $result->status);
        $this->assertEquals('Success !', $result->title);
        $this->assertEquals('Logged in successfully. Redirecting...', $result->message);
        $this->assertEquals(route('checkout.address'), $result->location);

        $this->assertTrue($user->id == auth()->id());
    }

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

    /** @test */
    function user_cannot_post_login_credentials_if_they_are_already_logged_in()
    {
        $this->addProductsInCart();

        $user = factory(User::class)->create();
        $this->be($user);

        $response = $this->withoutExceptionHandling()
                         ->post(route('checkout.postLogin'), []);

        $result = json_decode($response->getContent());

        $this->assertEquals('failed', $result->status);
        $this->assertEquals('Failed !', $result->title);
        $this->assertEquals('You are already logged in...', $result->message);
        $this->assertEquals(route('checkout.address'), $result->location);

        $this->assertTrue($user->id == auth()->id());
    }

    /** @test */
    function username_or_email_field_is_required()
    {
        $this->addProductsInCart();

        factory(User::class)->create();

        $formValues = [
            'usernameOrEmail' => '',
            'password' => 'Password'
         ];

        $this->post(route('checkout.postLogin'), $formValues)
            ->assertSessionHasErrors('usernameOrEmail');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('usernameOrEmail'),
            'The username or email field is required.'
        );
    }

    /** @test */
    function password_field_is_required()
    {
        $this->addProductsInCart();

        $user = factory(User::class)->create();

        $formValues = [
            'usernameOrEmail' => array_random([$user->username, $user->email]),
            'password' => ''
        ];

        $this->post(route('checkout.postLogin'), $formValues)
            ->assertSessionHasErrors('password');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('password'),
            'The password field is required.'
        );
    }
}
