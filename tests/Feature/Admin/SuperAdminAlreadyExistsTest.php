<?php

namespace Tests\Feature\Admin;

use IndianIra\User;
use Tests\TestCase;
use IndianIra\Mail\AdminGenerated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SuperAdminAlreadyExistsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->generateSuperAdministrator();
    }

    /** @test */
    function super_administrator_does_exists()
    {
        $this->assertCount(1, User::all());
    }

    /** @test */
    function page_cannot_be_accessible_if_super_administrator_already_exists()
    {
        $this->withoutExceptionHandling()->get(route('admin.generate'))
            ->assertStatus(302)
            ->assertRedirect(route('homePage'));
    }

    /** @test */
    function user_cannot_submit_the_form_data_if_super_administrator_already_exists()
    {
        $response = $this->withExceptionHandling()
                        ->post(route('admin.generate.store'), []);

        $result = json_decode($response->getContent());

        $this->assertEquals('failed', $result->status);
        $this->assertEquals('Failed !', $result->title);
        $this->assertEquals('Super Administrator already exists', $result->message);
        $this->assertEquals(route('homePage'), $result->location);
    }
}
