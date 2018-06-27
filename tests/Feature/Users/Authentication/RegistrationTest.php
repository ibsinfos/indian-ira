<?php

namespace Tests\Feature\Users\Authentication;

use IndianIra\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithFaker;
use IndianIra\Mail\Users\ConfirmRegistration;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->generateSuperAdministrator();
    }

    /** @test */
    function visitor_can_access_the_registration_page()
    {
        $this->withoutExceptionHandling()
             ->get(route('users.register'))
             ->assertStatus(200)
             ->assertViewIs('users.register')
             ->assertSee('New User: Register');
    }

    /** @test */
    function logged_in_user_cannot_access_the_registration_page()
    {
        $this->be(factory(User::class)->create());

        $this->withoutExceptionHandling()
             ->get(route('users.register'))
             ->assertStatus(302)
             ->assertRedirect(route('homePage'));
    }

    /** @test */
    function visitor_may_become_a_registered_user()
    {
        $formValues = [
            'first_name'       => 'User',
            'last_name'        => 'One',
            'username'         => 'user1',
            'email'            => 'user1@example.com',
            'password'         => 'Password',
            'confirm_password' => 'Password',
        ];

        $response = $this->withoutExceptionHandling()
                        ->post(route('users.register.store'), $formValues);

        $result = json_decode($response->getContent());

        $this->assertEquals('success', $result->status);
        $this->assertEquals('Success !', $result->title);
        $this->assertEquals('You have successfully registered.. Redirecting...', $result->message);
        $this->assertEquals(route('users.showConfirmRegistrationPage'), $result->location);
    }

    /** @test */
    function logged_in_user_cannot_submit_the_registration_form_data()
    {
        $this->be(factory(User::class)->create());

        $response = $this->withoutExceptionHandling()
                        ->post(route('users.register.store'), []);

        $result = json_decode($response->getContent());

        $this->assertEquals('failed', $result->status);
        $this->assertEquals('Failed !', $result->title);
        $this->assertEquals('You are already logged in.', $result->message);
    }

    /** @test */
    function user_recieves_a_confirmation_email_on_submitting_the_registration_form()
    {
        Mail::fake();

        $formValues = [
            'first_name'       => 'User',
            'last_name'        => 'One',
            'username'         => 'user1',
            'email'            => 'user1@example.com',
            'password'         => 'Password',
            'confirm_password' => 'Password',
        ];

        $response = $this->withoutExceptionHandling()
                        ->post(route('users.register.store'), $formValues);

        $result = json_decode($response->getContent());

        $this->assertEquals('success', $result->status);
        $this->assertEquals('Success !', $result->title);
        $this->assertEquals('You have successfully registered.. Redirecting...', $result->message);
        $this->assertEquals(route('users.showConfirmRegistrationPage'), $result->location);

        Mail::assertSent(ConfirmRegistration::class);
    }

    /** @test */
    function first_name_field_is_required()
    {
        $formValues = [
            'first_name'       => '',
            'last_name'        => 'One',
            'username'         => 'user1',
            'email'            => 'user1@example.com',
            'password'         => 'Password',
            'confirm_password' => 'Password',
        ];

        $this->post(route('users.register.store'), $formValues)
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
        $formValues = [
            'first_name'       => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.. Explicabo fugiat unde veritatis deserunt eos saepe!',
            'last_name'        => 'One',
            'username'         => 'user1',
            'email'            => 'user1@example.com',
            'password'         => 'Password',
            'confirm_password' => 'Password',
        ];

        $this->post(route('users.register.store'), $formValues)
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
        $formValues = [
            'first_name'       => 'User',
            'last_name'        => '',
            'username'         => 'user1',
            'email'            => 'user1@example.com',
            'password'         => 'Password',
            'confirm_password' => 'Password',
        ];

        $this->post(route('users.register.store'), $formValues)
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
        $formValues = [
            'first_name'       => 'User',
            'last_name'        => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.. Explicabo fugiat unde veritatis deserunt eos saepe!',
            'username'         => 'user1',
            'email'            => 'user1@example.com',
            'password'         => 'Password',
            'confirm_password' => 'Password',
        ];

        $this->post(route('users.register.store'), $formValues)
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
        $formValues = [
            'first_name'       => 'User',
            'last_name'        => 'One',
            'username'         => '',
            'email'            => 'user1@example.com',
            'password'         => 'Password',
            'confirm_password' => 'Password',
        ];

        $this->post(route('users.register.store'), $formValues)
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
        $formValues = [
            'first_name'       => 'User',
            'last_name'        => 'One',
            'username'         => 'Lorem_ipsum_dolor_sit_amet_consectetur_adipisicing_elit',
            'email'            => 'user1@example.com',
            'password'         => 'Password',
            'confirm_password' => 'Password',
        ];

        $this->post(route('users.register.store'), $formValues)
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
        $formValues = [
            'first_name'       => 'User',
            'last_name'        => 'One',
            'username'         => '^Lorem_ipsum dolor!',
            'email'            => 'user1@example.com',
            'password'         => 'Password',
            'confirm_password' => 'Password',
        ];

        $this->post(route('users.register.store'), $formValues)
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

        $formValues = [
            'first_name'       => 'User',
            'last_name'        => 'One',
            'username'         => 'user1',
            'email'            => 'user1@example.com',
            'password'         => 'Password',
            'confirm_password' => 'Password',
        ];

        $this->post(route('users.register.store'), $formValues)
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
        $formValues = [
            'first_name'       => 'User',
            'last_name'        => 'One',
            'username'         => 'user1',
            'email'            => '',
            'password'         => 'Password',
            'confirm_password' => 'Password',
        ];

        $this->post(route('users.register.store'), $formValues)
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
        $formValues = [
            'first_name'       => 'User',
            'last_name'        => 'One',
            'username'         => 'user1',
            'email'            => array_random($this->getInvalidEmailAddress()),
            'password'         => 'Password',
            'confirm_password' => 'Password',
        ];

        $this->post(route('users.register.store'), $formValues)
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

        $formValues = [
            'first_name'       => 'User',
            'last_name'        => 'One',
            'username'         => 'user1',
            'email'            => 'user1@example.com',
            'password'         => 'Password',
            'confirm_password' => 'Password',
        ];

        $this->post(route('users.register.store'), $formValues)
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
        $formValues = [
            'first_name'       => 'User',
            'last_name'        => 'One',
            'username'         => 'user1',
            'email'            => 'user1@examle.com',
            'password'         => '',
            'confirm_password' => 'Password',
        ];

        $this->post(route('users.register.store'), $formValues)
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
        $formValues = [
            'first_name'       => 'User',
            'last_name'        => 'One',
            'username'         => 'user1',
            'email'            => 'user1@examle.com',
            'password'         => 'Password',
            'confirm_password' => '',
        ];

        $this->post(route('users.register.store'), $formValues)
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
        $formValues = [
            'first_name'       => 'User',
            'last_name'        => 'One',
            'username'         => 'user1',
            'email'            => 'user1@examle.com',
            'password'         => 'Password',
            'confirm_password' => 'Secret',
        ];

        $this->post(route('users.register.store'), $formValues)
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
        $formValues = [
            'first_name'       => 'User',
            'last_name'        => 'One',
            'username'         => 'user1',
            'email'            => 'user1@examle.com',
            'password'         => 'Password',
            'confirm_password' => 'Secret',
            'contact_number'   => 'tytjykujndzfv'
        ];

        $this->post(route('users.register.store'), $formValues)
            ->assertSessionHasErrors('contact_number');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('contact_number'),
            'The contact number must be a number.'
        );
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
