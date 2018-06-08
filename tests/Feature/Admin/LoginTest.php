<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    public function setUp()
    {
        parent::setUp();

        $this->admin = $this->generateSuperAdministrator();
    }

    /** @test */
    function visitor_can_see_the_admin_login_page()
    {
        $this->withoutExceptionHandling()
             ->get(route('admin.login'))
             ->assertViewIs('admin.login')
             ->assertSee('Login Super Administrator');
    }

    /** @test */
    function admin_can_login_to_the_admin_panel()
    {
        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.postLogin'), [
                            'usernameOrEmail' => array_random(['admin', 'admin@example.com']),
                            'password'        => 'Password'
                         ]);

        $result = json_decode($response->getContent());

        $this->assertEquals('success', $result->status);
        $this->assertEquals('Success !', $result->title);
        $this->assertEquals('Logged in successfully. Redirecting...', $result->message);
        $this->assertEquals(route('admin.dashboard'), $result->location);

        $this->assertTrue($this->admin->id == auth()->id());
    }

    /** @test */
    function username_or_email_field_is_required()
    {
        $formValues = [
            'usernameOrEmail' => '',
            'password' => 'Password'
         ];

        $this->post(route('admin.postLogin'), $formValues)
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
        $formValues = [
            'usernameOrEmail' => array_random(['admin', 'admin@example.com']),
            'password' => ''
        ];

        $this->post(route('admin.postLogin'), $formValues)
            ->assertSessionHasErrors('password');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('password'),
            'The password field is required.'
        );
    }
}
