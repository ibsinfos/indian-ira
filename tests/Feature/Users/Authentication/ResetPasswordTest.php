<?php

namespace Tests\Feature\Users\Authentication;

use IndianIra\User;
use Tests\TestCase;
use IndianIra\ForgotPassword;
use Illuminate\Support\Facades\Mail;
use IndianIra\Mail\Users\ResetPassword;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->generateSuperAdministrator();
    }

    /** @test */
    function user_can_access_reset_password_page_if_they_are_not_logged_in_and_their_token_exists_in_database()
    {
        $user = factory(User::class)->create();
        $password = factory(ForgotPassword::class)->create(['email' => $user->email]);

        $this->withoutExceptionHandling()
             ->get(route('users.resetPassword', $password->token))
             ->assertViewIs('users.reset_password')
             ->assertSee('Reset Password');

        $this->assertFalse($user->id == auth()->id());
    }

    /** @test */
    function user_cannot_access_reset_password_page_if_they_are_already_logged_in()
    {
        $user = factory(User::class)->create();
        $this->be($user);

        $this->withoutExceptionHandling()
             ->get(route('users.resetPassword', 'random-token'))
             ->assertStatus(302)
             ->assertRedirect(route('users.dashboard'));

        $this->assertTrue($user->id == auth()->id());
    }

    /** @test */
    function user_cannot_access_reset_password_page_if_their_token_does_not_exists_in_database()
    {
        factory(User::class)->create();
        factory(ForgotPassword::class)->create();

        $this->withExceptionHandling()
             ->get(route('users.resetPassword', 'some-gibberis-htext-here'))
             ->assertStatus(404);
    }

    /** @test */
    function user_can_update_their_password()
    {
        $password = factory(ForgotPassword::class)->create([
            'email' => factory(User::class)->create()->email
        ]);

        $this->assertCount(1, ForgotPassword::all());

        $this->withoutExceptionHandling()
             ->get(route('users.resetPassword', $password->token))
             ->assertViewIs('users.reset_password')
             ->assertSee('Reset Password');

        $response = $this->withoutExceptionHandling()
                        ->post(route('users.resetPassword.update', $password->token), [
                            'new_password'        => 'Password',
                            'repeat_new_password' => 'Password',
                        ]);

        $result = json_decode($response->getContent());

        $this->assertEquals('success', $result->status);
        $this->assertEquals('Success !', $result->title);
        $this->assertEquals('Password updated successfully. Redirecting to login page...', $result->message);
        $this->assertEquals(route('users.login'), $result->location);

        $this->assertCount(0, ForgotPassword::all());
    }

    /** @test */
    public function new_password_field_is_required()
    {
        $password = factory(ForgotPassword::class)->create([
            'email' => factory(User::class)->create()->email
        ]);

        $this->withExceptionHandling()
             ->post(route('users.resetPassword.update', $password->token), [
                'new_password' => '',
                'repeat_new_password' => 'Password',
            ])
             ->assertSessionHasErrors('new_password');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('new_password'),
            'The new password field is required.'
        );
    }

    /** @test */
    public function repeat_new_password_field_is_required()
    {
        $password = factory(ForgotPassword::class)->create([
            'email' => factory(User::class)->create()->email
        ]);

        $this->withExceptionHandling()
             ->post(route('users.resetPassword.update', $password->token), [
                'new_password' => 'Password',
                'repeat_new_password' => '',
            ])
             ->assertSessionHasErrors('repeat_new_password');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('repeat_new_password'),
            'The repeat new password field is required.'
        );
    }
}
