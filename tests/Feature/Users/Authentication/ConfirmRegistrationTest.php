<?php

namespace Tests\Feature\Users\Authentication;

use IndianIra\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithFaker;
use IndianIra\Mail\Users\RegistrationSuccessful;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConfirmRegistrationTest extends TestCase
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
    public function redirect_user_if_their_verification_token_is_invalid()
    {
        $this->get(route('users.confirmRegistration', 'some-gibberish-text-here'))
             ->assertStatus(302)
             ->assertRedirect(route('homePage'));
    }

    /** @test */
    public function user_has_registered_and_now_confirms_their_account()
    {
        Mail::fake();

        $user = factory(User::class)->create([
            'is_verified'        => false,
            'verified_on'        => null,
            'verification_token' => str_random(60),
        ]);

        $this->withoutExceptionHandling()
             ->get(route('users.confirmRegistration', $user->verification_token))
             ->assertStatus(302)
             ->assertRedirect(route('users.login'));

        Mail::assertSent(RegistrationSuccessful::class);
    }

    /** @test */
    public function empty_user_billing_address_is_created_when_they_confirm_their_account()
    {
        $user = factory(User::class)->create([
            'is_verified'        => false,
            'verified_on'        => null,
            'verification_token' => str_random(60),
        ]);

        $this->assertFalse($user->fresh()->hasBillingAddress());

        $this->withoutExceptionHandling()
             ->get(route('users.confirmRegistration', $user->verification_token))
             ->assertStatus(302)
             ->assertRedirect(route('users.login'));

        $this->assertTrue($user->fresh()->hasBillingAddress());
    }
}
