<?php

namespace Tests\Feature\Users\Settings;

use IndianIra\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GeneralSettingsTest extends TestCase
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
    function guest_user_cannot_access_the_general_settings_section()
    {
        auth()->logout();

        $this->withoutExceptionHandling()
             ->get(route('users.settings.general'))
             ->assertStatus(302)
             ->assertRedirect(route('users.login'));
    }

    /** @test */
    function user_sees_the_general_settings_section()
    {
        $this->withoutExceptionHandling()
             ->get(route('users.settings.general'))
             ->assertSee('Edit General Details')
             ->assertViewIs('users.settings.general');
    }

    /** @test */
    function user_can_update_their_general_settings_details()
    {
        $formValues = $this->mergeGeneralDetails();

        $response = $this->withoutExceptionHandling()
                        ->post(route('users.settings.general.update'), $formValues);

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'General Details updated successfully! Reloading...');
        $this->assertEquals($result->location, route('users.settings.general'));

        $this->assertNotEquals($this->user->getFullName(), 'John Doe');
        $this->assertEquals($this->user->fresh()->getFullName(), 'John Doe');
    }

    /** @test */
    function only_logged_in_user_can_submit_their_general_settings_details()
    {
        auth()->logout();

        $this->withExceptionHandling()
            ->post(route('users.settings.general.update'), $this->mergeGeneralDetails())
            ->assertStatus(302)
            ->assertRedirect(route('users.login'));
    }

    /** @test */
    function first_name_field_is_required()
    {
        $formValues = $this->mergeGeneralDetails(['first_name' => '']);

        $this->post(route('users.settings.general.update'), $formValues)
             ->assertSessionHasErrors('first_name');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('first_name'),
            'The first name field is required.'
        );
    }

    /** @test */
    function first_name_field_cannot_be_more_than_100_characters()
    {
        $formValues = $this->mergeGeneralDetails(['first_name' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam tempora sunt quae rem aspernatur labore corporis']);

        $this->post(route('users.settings.general.update'), $formValues)
             ->assertSessionHasErrors('first_name');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('first_name'),
            'The first name may not be greater than 100 characters.'
        );
    }

    /** @test */
    function last_name_field_is_required()
    {
        $formValues = $this->mergeGeneralDetails(['last_name' => '']);

        $this->post(route('users.settings.general.update'), $formValues)
             ->assertSessionHasErrors('last_name');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('last_name'),
            'The last name field is required.'
        );
    }

    /** @test */
    function last_name_field_cannot_be_more_than_100_characters()
    {
        $formValues = $this->mergeGeneralDetails(['last_name' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam tempora sunt quae rem aspernatur labore corporis']);

        $this->post(route('users.settings.general.update'), $formValues)
             ->assertSessionHasErrors('last_name');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('last_name'),
            'The last name may not be greater than 100 characters.'
        );
    }

    /** @test */
    function contact_number_field_cannot_be_more_than_50_characters()
    {
        $formValues = $this->mergeGeneralDetails(['contact_number' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.']);

        $this->post(route('users.settings.general.update'), $formValues)
             ->assertSessionHasErrors('contact_number');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('contact_number'),
            'The contact number may not be greater than 50 characters.'
        );
    }

    /**
     * Merge the user's general details.
     *
     * @param   array  $details
     * @return  array
     */
    protected function mergeGeneralDetails($details = [])
    {
        return array_merge([
            'first_name'     => 'John',
            'last_name'      => 'Doe',
            'contact_number' => '9876543210',
        ], $details);
    }
}
