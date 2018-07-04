<?php

namespace Tests\Feature\Admin\Users;

use IndianIra\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithFaker;
use IndianIra\Mail\Users\RegistrationSuccessful;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VerifiesUserTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->signInSuperAdministrator();
    }

    /** @test */
    function super_administrator_can_verify_the_user_if_they_are_unverified()
    {
        $user = factory(User::class)->create([
            'verification_token' => str_random(60),
            'is_verified' => false,
            'verified_on' => null
        ]);

        $this->assertFalse($user->isVerified());

        $response = $this->withoutExceptionHandling()
                        ->get(route('admin.users.verify', $user->id));

        $result = json_decode($response->getContent());

        $users = User::withTrashed()->where('id', '<>', 1)->orderBy('id', 'DESC')->get();

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'User verified successfully...');
        $this->assertEquals($result->htmlResult, view('admin.users.table', compact('users'))->render());

        $this->assertTrue($user->fresh()->isVerified());
    }

    /** @test */
    function registered_user_receives_the_success_email_on_verifying_by_super_administrator()
    {
        Mail::fake();

        $user = factory(User::class)->create([
            'verification_token' => str_random(60),
            'is_verified' => false,
            'verified_on' => null
        ]);

        $this->assertFalse($user->isVerified());

        $response = $this->withoutExceptionHandling()
                        ->get(route('admin.users.verify', $user->id));

        $result = json_decode($response->getContent());

        $users = User::withTrashed()->where('id', '<>', 1)->orderBy('id', 'DESC')->get();

        $this->assertTrue($user->fresh()->isVerified());

        Mail::assertSent(RegistrationSuccessful::class);
    }
}
