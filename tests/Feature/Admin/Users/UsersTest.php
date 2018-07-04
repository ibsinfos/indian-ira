<?php

namespace Tests\Feature\Admin\Users;

use Tests\TestCase;
use IndianIra\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->signInSuperAdministrator();
    }

    /** @test */
    function only_logged_in_super_administrator_can_access_the_users_section()
    {
        // Logout the Super Administrator
        auth()->logout();

        $this->withoutExceptionHandling()
             ->get(route('admin.users'))
             ->assertStatus(302)
             ->assertRedirect(route('admin.login'));
    }

    /** @test */
    function super_administrator_can_view_the_users_section()
    {
        $this->withoutExceptionHandling()
             ->get(route('admin.users'))
             ->assertViewIs('admin.users.index')
             ->assertSee('List of All Users');
    }

    /** @test */
    function no_users_data_exists()
    {
        $this->assertCount(0, User::where('id', '<>', 1)->get());
    }

    /** @test */
    function super_administrator_can_temporarily_delete_a_user()
    {
        $users = factory(User::class, 3)->create();

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.users.delete', $users->last()->id));

        $result = json_decode($response->getContent());

        $users = User::withTrashed()->where('id', '<>', 1)->orderBy('id', 'DESC')->get();

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'User deleted temporarily!');
        $this->assertEquals($result->htmlResult, view('admin.users.table', compact('users'))->render());

        $this->assertCount(3, $users);
        $this->assertCount(2, $users->where('deleted_at', null));
        $this->assertCount(1, $users->where('deleted_at', '<>', null));
    }

    /** @test */
    function super_administrator_cannot_temporarily_delete_a_user_than_does_not_exist()
    {
        $users = factory(User::class, 3)->create();

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.users.delete', 15));

        $result = json_decode($response->getContent());

        $users = User::withTrashed()->where('id', '<>', 1)->orderBy('id', 'DESC')->get();

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'User with that id cannot be found!');

        $this->assertCount(3, $users);
        $this->assertCount(3, $users->where('deleted_at', null));
        $this->assertCount(0, $users->where('deleted_at', '<>', null));
    }

    /** @test */
    function super_administrator_can_restore_a_temporarily_deleted_user()
    {
        factory(User::class, 3)->create();
        $user = factory(User::class)->create(['deleted_at' => now()]);

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.users.restore', $user->id));

        $result = json_decode($response->getContent());

        $users = User::withTrashed()->where('id', '<>', 1)->orderBy('id', 'DESC')->get();

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'User restored successfully!');
        $this->assertEquals($result->htmlResult, view('admin.users.table', compact('users'))->render());

        $this->assertCount(4, $users);
        $this->assertCount(4, $users->where('deleted_at', null));
        $this->assertCount(0, $users->where('deleted_at', '<>', null));
    }

    /** @test */
    function super_administrator_cannot_restore_a_temporarily_deleted_user_that_does_not_exist()
    {
        factory(User::class, 3)->create();
        $user = factory(User::class)->create(['deleted_at' => now()]);

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.users.restore', 15));

        $result = json_decode($response->getContent());

        $users = User::withTrashed()->where('id', '<>', 1)->orderBy('id', 'DESC')->get();

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'User with that id cannot be found!');

        $this->assertCount(4, $users);
        $this->assertCount(3, $users->where('deleted_at', null));
        $this->assertCount(1, $users->where('deleted_at', '<>', null));
    }

    /** @test */
    function super_administrator_can_permanently_delete_a_temporarily_deleted_user()
    {
        factory(User::class, 3)->create();
        $user = factory(User::class)->create(['deleted_at' => now()]);

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.users.destroy', $user->id));

        $result = json_decode($response->getContent());

        $users = User::withTrashed()->where('id', '<>', 1)->orderBy('id', 'DESC')->get();

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'User destroyed successfully!');
        $this->assertEquals($result->htmlResult, view('admin.users.table', compact('users'))->render());

        $this->assertCount(3, $users);
        $this->assertCount(3, $users->where('deleted_at', null));
        $this->assertCount(0, $users->where('deleted_at', '<>', null));
    }

    /** @test */
    function super_administrator_cannot_permanently_delete_a_temporarily_deleted_user_that_does_not_exist()
    {
        factory(User::class, 3)->create();
        $user = factory(User::class)->create(['deleted_at' => now()]);

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.users.destroy', 15));

        $result = json_decode($response->getContent());

        $users = User::withTrashed()->where('id', '<>', 1)->orderBy('id', 'DESC')->get();

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'User with that id cannot be found!');

        $this->assertCount(4, $users);
        $this->assertCount(3, $users->where('deleted_at', null));
        $this->assertCount(1, $users->where('deleted_at', '<>', null));
    }
}
