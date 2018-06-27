<?php

namespace Tests\Feature\Users\Authentication;

use IndianIra\User;
use Tests\TestCase;
use IndianIra\ForgotPassword;
use Illuminate\Support\Facades\Mail;
use IndianIra\Mail\Users\ResetPassword;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->generateSuperAdministrator();
    }

    /** @test */
    public function user_has_registered_but_not_confirmed_their_account()
    {
        $user = factory(User::class)->create([
            'is_verified'        => false,
            'verified_on'        => null,
            'verification_token' => str_random(60),
        ]);

        $this->assertFalse($user->isVerified());
    }

    /** @test */
    function user_can_access_forgot_password_page_if_they_are_not_logged_in()
    {
        $user = factory(User::class)->create();

        $this->withoutExceptionHandling()
             ->get(route('users.forgotPassword'))
             ->assertViewIs('users.forgot_password')
             ->assertSee('Forgot Password');

        $this->assertFalse($user->id == auth()->id());
    }

    /** @test */
    function user_cannot_access_forgot_password_page_if_they_are_already_logged_in()
    {
        $user = factory(User::class)->create();
        $this->be($user);

        $this->withoutExceptionHandling()
             ->get(route('users.forgotPassword'))
             ->assertStatus(302)
             ->assertRedirect(route('users.dashboard'));

        $this->assertTrue($user->id == auth()->id());
    }

    /** @test */
    function user_can_submit_their_email_address_in_order_to_receive_the_password_reset_link()
    {
        $this->assertCount(0, ForgotPassword::all());

        $response = $this->withoutExceptionHandling()
                        ->post(route('users.forgotPassword.store'), [
                            'email' => factory(User::class)->create()->email
                        ]);

        $result = json_decode($response->getContent());

        $this->assertEquals('success', $result->status);
        $this->assertEquals('Success !', $result->title);
        $this->assertEquals('We have sent an E-Mail to the given mail address to reset your password', $result->message);
        $this->assertEquals(route('users.forgotPassword'), $result->location);
    }

    /** @test */
    public function user_receives_the_password_reset_link_in_mail()
    {
        Mail::fake();

        $this->assertCount(0, ForgotPassword::all());

        $response = $this->withoutExceptionHandling()
                        ->post(route('users.forgotPassword.store'), [
                            'email' => factory(User::class)->create()->email
                        ]);

        $result = json_decode($response->getContent());

        $this->assertEquals('success', $result->status);
        $this->assertEquals('Success !', $result->title);
        $this->assertEquals('We have sent an E-Mail to the given mail address to reset your password', $result->message);
        $this->assertEquals(route('users.forgotPassword'), $result->location);

        Mail::assertSent(ResetPassword::class);
    }

    /** @test */
    public function email_field_is_required()
    {
        $this->assertCount(0, ForgotPassword::all());

        $this->withExceptionHandling()
             ->post(route('users.forgotPassword.store'), ['email' => ''])
             ->assertSessionHasErrors('email');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('email'),
            'The email field is required.'
        );
    }

    /** @test */
    public function email_should_be_a_valid_email_address()
    {
        $this->assertCount(0, ForgotPassword::all());

        $this->withExceptionHandling()
             ->post(route('users.forgotPassword.store'), [
                'email' => array_random($this->getInvalidEmailAddress())
            ])
             ->assertSessionHasErrors('email');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('email'),
            'The email must be a valid email address.'
        );
    }

    /** @test */
    public function email_should_exists_in_the_database()
    {
        $this->assertCount(0, ForgotPassword::all());

        $this->withExceptionHandling()
             ->post(route('users.forgotPassword.store'), [
                'email' => 'user1@gmail.com'
            ])
             ->assertSessionHasErrors('email');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('email'),
            'That email does not exists in our system.'
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
