<?php

namespace Tests\Feature\Users\Authentication;

use IndianIra\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->generateSuperAdministrator();
    }

    /** @test */
    public function only_authenticated_users_can_see_their_dashboard()
    {
        $this->be($user = factory(User::class)->create());

        $this->withoutExceptionHandling()
             ->get(route('users.dashboard'))
             ->assertStatus(200)
             ->assertViewIs('users.dashboard')
             ->assertSeeText('Welcome ' . $user->getFullName());
    }

    /** @test */
    public function non_authenticated_users_are_redirected_to_login_page()
    {
        factory(User::class)->create();

        $this->withoutExceptionHandling()
             ->get(route('users.dashboard'))
             ->assertStatus(302)
             ->assertRedirect(route('users.login'));
    }

    /** @test */
    public function an_authenticated_user_can_logout_from_the_application()
    {
        $this->be($user = factory(User::class)->create());

        $this->withoutExceptionHandling()
             ->get(route('users.logout'))
             ->assertStatus(302)
             ->assertRedirect(route('users.login'));
    }
}
