<?php

namespace Tests\Feature\Users\Settings;

use IndianIra\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChangePasswordTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->generateSuperAdministrator();

        $this->user = $this->signInUser();
    }

    /** @test */
    function guest_user_cannot_access_the_change_password_section()
    {
        auth()->logout();

        $this->withoutExceptionHandling()
             ->get(route('users.settings.password'))
             ->assertStatus(302)
             ->assertRedirect(route('users.login'));
    }

    /** @test */
    function user_sees_the_change_password_section()
    {
        $this->withoutExceptionHandling()
             ->get(route('users.settings.password'))
             ->assertSee('Change Password')
             ->assertViewIs('users.settings.change_password');
    }

    /** @test */
    function user_can_update_their_password()
    {
        $formValues = $this->mergePassword();

        $response = $this->withoutExceptionHandling()
                        ->post(route('users.settings.password.update'), $formValues);

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Password updated successfully! Reloading...');
        $this->assertEquals($result->location, route('users.settings.password'));
    }

    /** @test */
    function only_logged_in_user_can_submit_their_change_password_details()
    {
        auth()->logout();

        $this->withExceptionHandling()
            ->post(route('users.settings.password.update'), $this->mergePassword())
            ->assertStatus(302)
            ->assertRedirect(route('users.login'));
    }

    /** @test */
    function cannot_update_password_if_wrong_current_password()
    {
        $formValues = $this->mergePassword(['current_password' => 'QwerTy']);

        $response = $this->withoutExceptionHandling()
                        ->post(route('users.settings.password.update'), $formValues);

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Invalid Current Password');
    }

    /** @test */
    function current_password_field_is_required()
    {
        $formValues = $this->mergePassword(['current_password' => '']);

        $this->post(route('users.settings.password.update'), $formValues)
             ->assertSessionHasErrors('current_password');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('current_password'),
            'The current password field is required.'
        );
    }

    /** @test */
    function new_password_field_is_required()
    {
        $formValues = $this->mergePassword(['new_password' => '']);

        $this->post(route('users.settings.password.update'), $formValues)
             ->assertSessionHasErrors('new_password');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('new_password'),
            'The new password field is required.'
        );
    }

    /** @test */
    function repeat_new_password_field_is_required()
    {
        $formValues = $this->mergePassword(['repeat_new_password' => '']);

        $this->post(route('users.settings.password.update'), $formValues)
             ->assertSessionHasErrors('repeat_new_password');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('repeat_new_password'),
            'The repeat new password field is required.'
        );
    }

    /** @test */
    function repeat_new_password_should_match_new_password()
    {
        $formValues = $this->mergePassword(['repeat_new_password' => 'elloWord']);

        $this->post(route('users.settings.password.update'), $formValues)
             ->assertSessionHasErrors('repeat_new_password');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('repeat_new_password'),
            'The repeat new password and new password must match.'
        );
    }

    /**
     * Merge the user's passwords.
     *
     * @param   array  $details
     * @return  array
     */
    protected function mergePassword($details = [])
    {
        return array_merge([
            'current_password'    => 'Password',
            'new_password'        => 'Secret',
            'repeat_new_password' => 'Secret',
        ], $details);
    }
}
