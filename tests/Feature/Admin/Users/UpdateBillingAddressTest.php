<?php

namespace Tests\Feature\Admin\Users;

use IndianIra\User;
use Tests\TestCase;
use IndianIra\UserBillingAddress;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithFaker;
use IndianIra\Mail\Users\RegistrationSuccessful;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateBillingAddressTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->signInSuperAdministrator();
    }

    /** @test */
    function super_administrator_can_access_the_billing_address_of_the_user()
    {
        $user = factory(User::class)->create();
        $billingAddress = factory(UserBillingAddress::class)->create(['user_id' => $user->id]);

        $this->withoutExceptionHandling()
             ->get(route('admin.users.edit', $billingAddress->user_id) . '?billing-address')
             ->assertViewIs('admin.users.edit')
             ->assertSee('Edit Billing Address: ' . $user->getFullName())
             ->assertDontSee('Edit General Details: ' . $user->getFullName());
    }

    /** @test */
    function super_administrator_can_update_the_billing_address_of_the_user()
    {
        $formValues = $this->mergeBilling([
            'address_line_1' => '4/39, HAMOCHS, Haji Ali',
        ]);

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.users.edit.updateBilling', $formValues['user_id']), $formValues);

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Billing Address updated successfully! Reloading...');
        $this->assertEquals($result->location, route('admin.users.edit', $formValues['user_id']) . '?billing-address');
    }

    /** @test */
    function super_administrator_cannot_update_the_users_billing_address_it_they_dont_exist()
    {
        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.users.edit.updateGeneral', 10), $this->mergeBilling());

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'User with that id cannot be found!');
    }

    /** @test */
    function address_line_1_field_is_required()
    {
        $formValues = $this->mergeBilling(['address_line_1' => '']);

        $this->post(route('admin.users.edit.updateBilling', $formValues['user_id']), $formValues)
             ->assertSessionHasErrors('address_line_1');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('address_line_1'),
            'The address line 1 field is required.'
        );
    }

    /** @test */
    function address_line_1_field_cannot_be_more_than_100_characters()
    {
        $formValues = $this->mergeBilling(['address_line_1' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam tempora sunt quae rem aspernatur labore corporis']);

        $this->post(route('admin.users.edit.updateBilling', $formValues['user_id']), $formValues)
             ->assertSessionHasErrors('address_line_1');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('address_line_1'),
            'The address line 1 may not be greater than 100 characters.'
        );
    }

    /** @test */
    function address_line_1_should_not_contain_any_special_characters_other_than_allowed_characters()
    {
        // allowed special characters: /_.,-'
        $formValues = $this->mergeBilling(['address_line_1' => '@#$%sdu  sleiyf *>']);

        $this->post(route('admin.users.edit.updateBilling', $formValues['user_id']), $formValues)
             ->assertSessionHasErrors('address_line_1');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('address_line_1'),
            'The address line 1 has got invalid characters.'
        );
    }

    /** @test */
    function address_line_2_field_cannot_be_more_than_100_characters()
    {
        $formValues = $this->mergeBilling(['address_line_2' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam tempora sunt quae rem aspernatur labore corporis']);

        $this->post(route('admin.users.edit.updateBilling', $formValues['user_id']), $formValues)
             ->assertSessionHasErrors('address_line_2');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('address_line_2'),
            'The address line 2 may not be greater than 100 characters.'
        );
    }

    /** @test */
    function address_line_2_should_not_contain_any_special_characters_other_than_allowed_characters()
    {
        // allowed special characters: /_.,-'
        $formValues = $this->mergeBilling(['address_line_2' => '@#$%sdu  sleiyf *>']);

        $this->post(route('admin.users.edit.updateBilling', $formValues['user_id']), $formValues)
             ->assertSessionHasErrors('address_line_2');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('address_line_2'),
            'The address line 2 has got invalid characters.'
        );
    }

    /** @test */
    function area_field_is_required()
    {
        $formValues = $this->mergeBilling(['area' => '']);

        $this->post(route('admin.users.edit.updateBilling', $formValues['user_id']), $formValues)
             ->assertSessionHasErrors('area');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('area'),
            'The area field is required.'
        );
    }

    /** @test */
    function area_field_cannot_be_more_than_100_characters()
    {
        $formValues = $this->mergeBilling(['area' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam tempora sunt quae rem aspernatur labore corporis']);

        $this->post(route('admin.users.edit.updateBilling', $formValues['user_id']), $formValues)
             ->assertSessionHasErrors('area');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('area'),
            'The area may not be greater than 100 characters.'
        );
    }

    /** @test */
    function area_should_not_contain_any_special_characters_other_than_allowed_characters()
    {
        // allowed special characters: /_.,-'
        $formValues = $this->mergeBilling(['area' => '@#$%sdu  sleiyf *>']);

        $this->post(route('admin.users.edit.updateBilling', $formValues['user_id']), $formValues)
             ->assertSessionHasErrors('area');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('area'),
            'The area has got invalid characters.'
        );
    }

    /** @test */
    function landmark_field_cannot_be_more_than_100_characters()
    {
        $formValues = $this->mergeBilling(['landmark' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam tempora sunt quae rem aspernatur labore corporis']);

        $this->post(route('admin.users.edit.updateBilling', $formValues['user_id']), $formValues)
             ->assertSessionHasErrors('landmark');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('landmark'),
            'The landmark may not be greater than 100 characters.'
        );
    }

    /** @test */
    function landmark_should_not_contain_any_special_characters_other_than_allowed_characters()
    {
        // allowed special characters: /_.,-'
        $formValues = $this->mergeBilling(['landmark' => '@#$%sdu  sleiyf *>']);

        $this->post(route('admin.users.edit.updateBilling', $formValues['user_id']), $formValues)
             ->assertSessionHasErrors('landmark');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('landmark'),
            'The landmark has got invalid characters.'
        );
    }

    /** @test */
    function city_field_is_required()
    {
        $formValues = $this->mergeBilling(['city' => '']);

        $this->post(route('admin.users.edit.updateBilling', $formValues['user_id']), $formValues)
             ->assertSessionHasErrors('city');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('city'),
            'The city field is required.'
        );
    }

    /** @test */
    function city_field_cannot_be_more_than_100_characters()
    {
        $formValues = $this->mergeBilling(['city' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam tempora sunt quae rem aspernatur labore corporis']);

        $this->post(route('admin.users.edit.updateBilling', $formValues['user_id']), $formValues)
             ->assertSessionHasErrors('city');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('city'),
            'The city may not be greater than 100 characters.'
        );
    }

    /** @test */
    function city_should_not_contain_any_special_characters_other_than_allowed_characters()
    {
        // allowed special characters: /_.,-'
        $formValues = $this->mergeBilling(['city' => '@#$%sdu  sleiyf *>']);

        $this->post(route('admin.users.edit.updateBilling', $formValues['user_id']), $formValues)
             ->assertSessionHasErrors('city');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('city'),
            'The city has got invalid characters.'
        );
    }

    /** @test */
    function pin_code_field_is_required()
    {
        $formValues = $this->mergeBilling(['pin_code' => '']);

        $this->post(route('admin.users.edit.updateBilling', $formValues['user_id']), $formValues)
             ->assertSessionHasErrors('pin_code');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('pin_code'),
            'The pin code field is required.'
        );
    }

    /** @test */
    function pin_code_field_cannot_be_more_than_100_characters()
    {
        $formValues = $this->mergeBilling(['pin_code' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam tempora sunt quae rem aspernatur labore corporis']);

        $this->post(route('admin.users.edit.updateBilling', $formValues['user_id']), $formValues)
             ->assertSessionHasErrors('pin_code');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('pin_code'),
            'The pin code may not be greater than 100 characters.'
        );
    }

    /** @test */
    function pin_code_should_not_contain_any_special_characters_other_than_allowed_characters()
    {
        // allowed special characters: /_.,-'
        $formValues = $this->mergeBilling(['pin_code' => '@#$%sdu  sleiyf *>']);

        $this->post(route('admin.users.edit.updateBilling', $formValues['user_id']), $formValues)
             ->assertSessionHasErrors('pin_code');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('pin_code'),
            'The pin code has got invalid characters.'
        );
    }

    /** @test */
    function state_field_is_required()
    {
        $formValues = $this->mergeBilling(['state' => '']);

        $this->post(route('admin.users.edit.updateBilling', $formValues['user_id']), $formValues)
             ->assertSessionHasErrors('state');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('state'),
            'The state field is required.'
        );
    }

    /** @test */
    function state_field_cannot_be_more_than_100_characters()
    {
        $formValues = $this->mergeBilling(['state' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam tempora sunt quae rem aspernatur labore corporis']);

        $this->post(route('admin.users.edit.updateBilling', $formValues['user_id']), $formValues)
             ->assertSessionHasErrors('state');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('state'),
            'The state may not be greater than 100 characters.'
        );
    }

    /** @test */
    function state_should_not_contain_any_special_characters_other_than_allowed_characters()
    {
        // allowed special characters: /_.,-'
        $formValues = $this->mergeBilling(['state' => '@#$%sdu  sleiyf *>']);

        $this->post(route('admin.users.edit.updateBilling', $formValues['user_id']), $formValues)
             ->assertSessionHasErrors('state');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('state'),
            'The state has got invalid characters.'
        );
    }

    /** @test */
    function country_field_is_required()
    {
        $formValues = $this->mergeBilling(['country' => '']);

        $this->post(route('admin.users.edit.updateBilling', $formValues['user_id']), $formValues)
             ->assertSessionHasErrors('country');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('country'),
            'The country field is required.'
        );
    }

    /** @test */
    function country_field_cannot_be_more_than_100_characters()
    {
        $formValues = $this->mergeBilling(['country' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam tempora sunt quae rem aspernatur labore corporis']);

        $this->post(route('admin.users.edit.updateBilling', $formValues['user_id']), $formValues)
             ->assertSessionHasErrors('country');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('country'),
            'The country may not be greater than 100 characters.'
        );
    }

    /** @test */
    function country_should_not_contain_any_special_characters_other_than_allowed_characters()
    {
        // allowed special characters: /_.,-'
        $formValues = $this->mergeBilling(['country' => '@#$%sdu  sleiyf *>']);

        $this->post(route('admin.users.edit.updateBilling', $formValues['user_id']), $formValues)
             ->assertSessionHasErrors('country');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('country'),
            'The country has got invalid characters.'
        );
    }

    /**
     * Merge the general details of the user.
     *
     * @param   array  $attributes
     * @return  array
     */
    protected function mergeBilling($attributes = [])
    {
        $billing = factory(UserBillingAddress::class)->create([
            'address_line_1' => '4/39, HAMOCHS',
            'address_line_2' => 'Government Colony',
            'area'           => 'Haji Ali',
            'landmark'       => 'Behind Lala Lajpatrai College',
            'city'           => 'Mumbai',
            'pin_code'       => '400034',
            'state'          => 'Maharashtra',
            'country'        => 'India',
        ]);

        return array_merge($billing->toArray(), $attributes);
    }
}
