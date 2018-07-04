<?php

namespace Tests\Feature\Admin\Users;

use IndianIra\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithFaker;
use IndianIra\Mail\Users\RegistrationSuccessful;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateGeneralDetailsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->signInSuperAdministrator();
    }

    /** @test */
    function super_administrator_can_access_the_general_details_of_the_user()
    {
        $user = factory(User::class)->create();

        $this->withoutExceptionHandling()
             ->get(route('admin.users.edit', $user->id) . '?general-details')
             ->assertViewIs('admin.users.edit')
             ->assertSee('Edit General Details: ' . $user->getFullName())
             ->assertDontSee('Change Password: ' . $user->getFullName());
    }

    /** @test */
    function super_administrator_can_update_the_general_details_of_the_user()
    {
        $user = factory(User::class)->create();

        $formValues = $this->mergeGeneral($user, [
            'first_name' => 'John',
            'last_name' => 'Doe'
        ]);

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.users.edit.updateGeneral', $user->id), $formValues);

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'General details updated successfully! Reloading...');
        $this->assertEquals($result->location, route('admin.users.edit', $user->id) . '?general-details');

        $this->assertEquals($user->fresh()->getFullName(), 'John Doe');
        $this->assertNotEquals($user->getFullName(), User::find($user->id)->getFullName());
    }

    /** @test */
    function super_administrator_cannot_update_the_users_general_details_it_they_dont_exist()
    {
        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.users.edit.updateGeneral', 10), $this->mergeGeneral());

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'User with that id cannot be found!');
    }

    /** @test */
    function first_name_field_is_required()
    {
        $formValues = $this->mergeGeneral(null, ['first_name' => '']);

        $this->post(route('admin.users.edit.updateGeneral', $formValues['id']), $formValues)
            ->assertSessionHasErrors('first_name');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('first_name'),
            'The first name field is required.'
        );
    }

    /** @test */
    function first_name_cannot_contain_more_than_100_characters()
    {
        $formValues = $this->mergeGeneral(null, ['first_name' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Reprehenderit, perspiciatis, aliquid! Voluptatem rem.']);

        $this->post(route('admin.users.edit.updateGeneral', $formValues['id']), $formValues)
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
        $formValues = $this->mergeGeneral(null, ['last_name' => '']);

        $this->post(route('admin.users.edit.updateGeneral', $formValues['id']), $formValues)
            ->assertSessionHasErrors('last_name');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('last_name'),
            'The last name field is required.'
        );
    }

    /** @test */
    function last_name_cannot_contain_more_than_100_characters()
    {
        $formValues = $this->mergeGeneral(null, ['last_name' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Reprehenderit, perspiciatis, aliquid! Voluptatem rem.']);

        $this->post(route('admin.users.edit.updateGeneral', $formValues['id']), $formValues)
            ->assertSessionHasErrors('last_name');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('last_name'),
            'The last name may not be greater than 100 characters.'
        );
    }

    /** @test */
    function username_field_is_required()
    {
        $formValues = $this->mergeGeneral(null, ['username' => '']);

        $this->post(route('admin.users.edit.updateGeneral', $formValues['id']), $formValues)
            ->assertSessionHasErrors('username');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('username'),
            'The username field is required.'
        );
    }

    /** @test */
    function username_cannot_contain_more_than_50_characters()
    {
        $formValues = $this->mergeGeneral(null, [
            'username' => 'Lorem_ipsum_dolor_sit_amet_consectetur_adipisicing_elit'
        ]);

        $this->post(route('admin.users.edit.updateGeneral', $formValues['id']), $formValues)
            ->assertSessionHasErrors('username');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('username'),
            'The username may not be greater than 50 characters.'
        );
    }

    /** @test */
    function username_field_should_contain_only_numbers_alphaabets_underscores_and_hyphens()
    {
        $formValues = $this->mergeGeneral(null, [
            'username' => '^Lorem_ipsum dolor!',
        ]);

        $this->post(route('admin.users.edit.updateGeneral', $formValues['id']), $formValues)
            ->assertSessionHasErrors('username');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('username'),
            'The username may only contain letters, numbers, dashes and underscores.'
        );
    }

    /** @test */
    function username_should_be_unique()
    {
        factory(User::class)->create(['username' => 'user1']);

        $formValues = $this->mergeGeneral(null, [
            'username' => 'user1',
        ]);

        $this->post(route('admin.users.edit.updateGeneral', $formValues['id']), $formValues)
            ->assertSessionHasErrors('username');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('username'),
            'The username has already been taken.'
        );
    }

    /** @test */
    function email_field_is_required()
    {
        $formValues = $this->mergeGeneral(null, [
            'email' => ''
        ]);

        $this->post(route('admin.users.edit.updateGeneral', $formValues['id']), $formValues)
            ->assertSessionHasErrors('email');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('email'),
            'The email field is required.'
        );
    }

    /** @test */
    function email_should_be_a_valid_email_address()
    {
        $formValues = $this->mergeGeneral(null, [
            'email' => array_random($this->getInvalidEmailAddress()),
        ]);

        $this->post(route('admin.users.edit.updateGeneral', $formValues['id']), $formValues)
            ->assertSessionHasErrors('email');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('email'),
            'The email must be a valid email address.'
        );
    }

    /** @test */
    function email_should_be_unique()
    {
        factory(User::class)->create(['email' => 'user1@example.com']);

        $formValues = $this->mergeGeneral(null, [
            'email' => 'user1@example.com',
        ]);

        $this->post(route('admin.users.edit.updateGeneral', $formValues['id']), $formValues)
            ->assertSessionHasErrors('email');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('email'),
            'The email has already been taken.'
        );
    }

    /** @test */
    function contact_number_should_contain_only_numeric_values()
    {
        $formValues = $this->mergeGeneral(null, [
            'contact_number' => 'tytjykujndzfv'
        ]);

        $this->post(route('admin.users.edit.updateGeneral', $formValues['id']), $formValues)
            ->assertSessionHasErrors('contact_number');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('contact_number'),
            'The contact number must be a number.'
        );
    }

    /**
     * Merge the general details of the user.
     *
     * @param   array  $attributes
     * @return  array
     */
    protected function mergeGeneral($user = null, $attributes = [])
    {
        if ($user == null) {
            $user = factory(User::class)->create();
        }

        return array_merge($user->toArray(), $attributes);
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
