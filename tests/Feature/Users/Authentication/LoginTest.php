<?php

namespace Tests\Feature\Users\Authentication;

use IndianIra\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->generateSuperAdministrator();
    }

    /** @test */
    function visitor_can_see_the_user_login_page()
    {
        $this->withoutExceptionHandling()
             ->get(route('users.login'))
             ->assertViewIs('users.login')
             ->assertSee('Login User');
    }

    /** @test */
    function user_can_login_to_the_application()
    {
        $user = factory(User::class)->create();

        $response = $this->withoutExceptionHandling()
                         ->post(route('users.postLogin'), [
                            'usernameOrEmail' => array_random([$user->username, $user->email]),
                            'password'        => 'Password'
                         ]);

        $result = json_decode($response->getContent());

        $this->assertEquals('success', $result->status);
        $this->assertEquals('Success !', $result->title);
        $this->assertEquals('Logged in successfully. Redirecting...', $result->message);
        $this->assertEquals(route('users.dashboard'), $result->location);

        $this->assertTrue($user->id == auth()->id());
    }

    /** @test */
    function user_cannot_access_login_page_if_they_are_already_logged_in()
    {
        $user = factory(User::class)->create();
        $this->be($user);

        $this->withoutExceptionHandling()
             ->get(route('users.login'))
             ->assertStatus(302)
             ->assertRedirect(route('users.dashboard'));

        $this->assertTrue($user->id == auth()->id());
    }

    /** @test */
    function user_cannot_post_login_credentials_if_they_are_already_logged_in()
    {
        $user = factory(User::class)->create();
        $this->be($user);

        $response = $this->withoutExceptionHandling()
                         ->post(route('users.postLogin'), []);

        $result = json_decode($response->getContent());

        $this->assertEquals('failed', $result->status);
        $this->assertEquals('Failed !', $result->title);
        $this->assertEquals('You are already logged in...', $result->message);
        $this->assertEquals(route('users.dashboard'), $result->location);

        $this->assertTrue($user->id == auth()->id());
    }

    /** @test */
    function username_or_email_field_is_required()
    {
        factory(User::class)->create();

        $formValues = [
            'usernameOrEmail' => '',
            'password' => 'Password'
         ];

        $this->post(route('users.postLogin'), $formValues)
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
        $user = factory(User::class)->create();

        $formValues = [
            'usernameOrEmail' => array_random([$user->username, $user->email]),
            'password' => ''
        ];

        $this->post(route('users.postLogin'), $formValues)
            ->assertSessionHasErrors('password');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('password'),
            'The password field is required.'
        );
    }
}
