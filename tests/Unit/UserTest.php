<?php

namespace Tests\Unit;

use IndianIra\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->generateSuperAdministrator();
    }

    /** @test */
    public function user_is_a_verified_user()
    {
        $user = factory(User::class)->create();

        $this->assertTrue($user->isVerified());
    }
}
