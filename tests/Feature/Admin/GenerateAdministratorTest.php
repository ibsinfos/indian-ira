<?php

namespace Tests\Feature\Admin;

use IndianIra\User;
use Tests\TestCase;
use IndianIra\Mail\AdminGenerated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GenerateAdministratorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function super_administrator_does_not_exists()
    {
        $this->assertCount(0, User::all());

        $this->assertNull(cache('superAdminExists'));
    }

    /** @test */
    function user_can_view_the_super_administrator_generator_form()
    {
        $this->assertCount(0, User::all());

        $this->withoutExceptionHandling()
            ->get(route('admin.generate'))
            ->assertStatus(200)
            ->assertViewIs('admin.generate')
            ->assertSee('Generate Super Administrator');
    }

    /** @test */
    function user_submits_the_form_data_to_become_super_administrator()
    {
        $this->assertCount(0, User::all());

        $formValues = [
            'first_name'       => 'Super',
            'last_name'        => 'Administrator',
            'username'         => 'admin',
            'email'            => 'admin@example.com',
            'password'         => 'Password',
            'confirm_password' => 'Password',
        ];

        $response = $this->withoutExceptionHandling()
                        ->post(route('admin.generate.store'), $formValues);

        $result = json_decode($response->getContent());

        $this->assertEquals('success', $result->status);
        $this->assertEquals('Success !', $result->title);
        $this->assertEquals('Super administrator generated successfully.. Redirecting...', $result->message);
        $this->assertEquals(route('homePage'), $result->location);
        $this->assertTrue(cache('superAdminExists'));
    }

    /** @test */
    function user_receives_the_mail_on_successfully_becoming_super_administrator()
    {
        Mail::fake();

        $this->assertCount(0, User::all());

        $formValues = [
            'first_name'       => 'Super',
            'last_name'        => 'Administrator',
            'username'         => 'admin',
            'email'            => 'admin@example.com',
            'password'         => 'Password',
            'confirm_password' => 'Password',
        ];

        $response = $this->withoutExceptionHandling()
                        ->post(route('admin.generate.store'), $formValues);

        $result = json_decode($response->getContent());

        $this->assertEquals('success', $result->status);
        $this->assertEquals('Success !', $result->title);
        $this->assertEquals('Super administrator generated successfully.. Redirecting...', $result->message);
        $this->assertEquals(route('homePage'), $result->location);
        $this->assertTrue(cache('superAdminExists'));

        Mail::assertSent(AdminGenerated::class);
    }

    /** @test */
    function first_name_field_is_required()
    {
        $formValues = [
            'first_name'       => '',
            'last_name'        => 'Administrator',
            'username'         => 'admin',
            'email'            => 'admin@example.com',
            'password'         => 'Password',
            'confirm_password' => 'Password',
        ];

        $this->post(route('admin.generate.store'), $formValues)
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
            'last_name'        => 'Administrator',
            'username'         => 'admin',
            'email'            => 'admin@example.com',
            'password'         => 'Password',
            'confirm_password' => 'Password',
        ];

        $this->post(route('admin.generate.store'), $formValues)
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
            'first_name'       => 'Super',
            'last_name'        => '',
            'username'         => 'admin',
            'email'            => 'admin@example.com',
            'password'         => 'Password',
            'confirm_password' => 'Password',
        ];

        $this->post(route('admin.generate.store'), $formValues)
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
            'first_name'       => 'Super',
            'last_name'        => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.. Explicabo fugiat unde veritatis deserunt eos saepe!',
            'username'         => 'admin',
            'email'            => 'admin@example.com',
            'password'         => 'Password',
            'confirm_password' => 'Password',
        ];

        $this->post(route('admin.generate.store'), $formValues)
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
            'first_name'       => 'Super',
            'last_name'        => 'Administrator',
            'username'         => '',
            'email'            => 'admin@example.com',
            'password'         => 'Password',
            'confirm_password' => 'Password',
        ];

        $this->post(route('admin.generate.store'), $formValues)
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
            'first_name'       => 'Super',
            'last_name'        => 'Administrator',
            'username'         => 'Lorem_ipsum_dolor_sit_amet_consectetur_adipisicing_elit',
            'email'            => 'admin@example.com',
            'password'         => 'Password',
            'confirm_password' => 'Password',
        ];

        $this->post(route('admin.generate.store'), $formValues)
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
            'first_name'       => 'Super',
            'last_name'        => 'Administrator',
            'username'         => '^Lorem_ipsum dolor!',
            'email'            => 'admin@example.com',
            'password'         => 'Password',
            'confirm_password' => 'Password',
        ];

        $this->post(route('admin.generate.store'), $formValues)
            ->assertSessionHasErrors('username');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('username'),
            'The username may only contain letters, numbers, dashes and underscores.'
        );
    }

    /** @test */
    function email_field_is_required()
    {
        $formValues = [
            'first_name'       => 'Super',
            'last_name'        => 'Administrator',
            'username'         => 'admin',
            'email'            => '',
            'password'         => 'Password',
            'confirm_password' => 'Password',
        ];

        $this->post(route('admin.generate.store'), $formValues)
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
            'first_name'       => 'Super',
            'last_name'        => 'Administrator',
            'username'         => 'admin',
            'email'            => array_random($this->getInvalidEmailAddress()),
            'password'         => 'Password',
            'confirm_password' => 'Password',
        ];

        $this->post(route('admin.generate.store'), $formValues)
            ->assertSessionHasErrors('email');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('email'),
            'The email must be a valid email address.'
        );
    }

    /** @test */
    function password_field_is_required()
    {
        $formValues = [
            'first_name'       => 'Super',
            'last_name'        => 'Administrator',
            'username'         => 'admin',
            'email'            => 'admin@examle.com',
            'password'         => '',
            'confirm_password' => 'Password',
        ];

        $this->post(route('admin.generate.store'), $formValues)
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
            'first_name'       => 'Super',
            'last_name'        => 'Administrator',
            'username'         => 'admin',
            'email'            => 'admin@examle.com',
            'password'         => 'Password',
            'confirm_password' => '',
        ];

        $this->post(route('admin.generate.store'), $formValues)
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
            'first_name'       => 'Super',
            'last_name'        => 'Administrator',
            'username'         => 'admin',
            'email'            => 'admin@examle.com',
            'password'         => 'Password',
            'confirm_password' => 'Secret',
        ];

        $this->post(route('admin.generate.store'), $formValues)
            ->assertSessionHasErrors('confirm_password');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('confirm_password'),
            'The confirm password and password must match.'
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
