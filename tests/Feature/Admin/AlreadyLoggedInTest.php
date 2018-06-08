<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AlreadyLoggedInTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    public function setUp()
    {
        parent::setUp();

        $this->admin = $this->generateSuperAdministrator();
    }

    /** @test */
    function already_logged_in_administrator_cannot_view_the_login_page()
    {
        $this->be($this->admin);

        $this->withoutExceptionHandling()
             ->get(route('admin.login'))
             ->assertStatus(302)
             ->assertRedirect(route('admin.dashboard'));

        $this->assertTrue($this->admin->id == auth()->id());
    }

    /** @test */
    function already_logged_in_administrator_cannot_submit_login_credentials()
    {
        $this->be($this->admin);

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.postLogin'), []);

        $result = json_decode($response->getContent());

        $this->assertEquals('failed', $result->status);
        $this->assertEquals('Failed !', $result->title);
        $this->assertEquals('You are already logged in...', $result->message);
        $this->assertEquals(route('admin.dashboard'), $result->location);

        $this->assertTrue($this->admin->id == auth()->id());
    }

    /** @test */
    function logged_in_administrator_can_view_the_admin_dashboard()
    {
        $this->be($this->admin);

        $this->withoutExceptionHandling()
             ->get(route('admin.dashboard'))
             ->assertViewIs('admin.dashboard')
             ->assertSee('Welcome ' . $this->admin->getFullName());

        $this->assertTrue($this->admin->id == auth()->id());
    }

    /** @test */
    function logged_in_administrator_can_logout()
    {
        $this->be($this->admin);

        $this->withoutExceptionHandling()
             ->get(route('admin.logout'))
             ->assertStatus(302)
             ->assertRedirect(route('homePage'));

        $this->assertFalse($this->admin->id == auth()->id());
    }
}
