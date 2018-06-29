<?php

namespace Tests\Feature\Users;

use IndianIra\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BillingAddressTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->generateSuperAdministrator();

        $this->user = $this->signInUser();
        $this->user->fresh()->billingAddress()->create();
    }

    /** @test */
    function guest_user_cannot_access_the_billing_address_section()
    {
        auth()->logout();

        $this->withoutExceptionHandling()
             ->get(route('users.billingAddress'))
             ->assertStatus(302)
             ->assertRedirect(route('users.login'));
    }

    /** @test */
    function user_sees_the_billing_address_section()
    {
        $this->withoutExceptionHandling()
             ->get(route('users.billingAddress'))
             ->assertSee('Edit Billing Address')
             ->assertViewIs('users.billing_address');
    }

    /** @test */
    function user_can_update_their_billing_address_details()
    {
        $formValues = $this->mergeAddress();

        $response = $this->withoutExceptionHandling()
                        ->post(route('users.billingAddress.update'), $formValues);

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Billing Address updated successfully! Reloading...');
        $this->assertEquals($result->location, route('users.billingAddress'));

        $this->assertEquals($this->user->billingAddress->fresh()->address_line_1, '4/39, HAMOCHS');
    }

    /** @test */
    function only_logged_in_user_can_submit_their_billing_address_details()
    {
        auth()->logout();

        $this->withExceptionHandling()
            ->post(route('users.billingAddress.update'), $this->mergeAddress())
            ->assertStatus(302)
            ->assertRedirect(route('users.login'));
    }

    /** @test */
    function address_line_1_field_is_required()
    {
        $formValues = $this->mergeAddress(['address_line_1' => '']);

        $this->post(route('users.billingAddress.update'), $formValues)
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
        $formValues = $this->mergeAddress(['address_line_1' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam tempora sunt quae rem aspernatur labore corporis']);

        $this->post(route('users.billingAddress.update'), $formValues)
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
        $formValues = $this->mergeAddress(['address_line_1' => '@#$%sdu  sleiyf *>']);

        $this->post(route('users.billingAddress.update'), $formValues)
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
        $formValues = $this->mergeAddress(['address_line_2' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam tempora sunt quae rem aspernatur labore corporis']);

        $this->post(route('users.billingAddress.update'), $formValues)
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
        $formValues = $this->mergeAddress(['address_line_2' => '@#$%sdu  sleiyf *>']);

        $this->post(route('users.billingAddress.update'), $formValues)
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
        $formValues = $this->mergeAddress(['area' => '']);

        $this->post(route('users.billingAddress.update'), $formValues)
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
        $formValues = $this->mergeAddress(['area' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam tempora sunt quae rem aspernatur labore corporis']);

        $this->post(route('users.billingAddress.update'), $formValues)
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
        $formValues = $this->mergeAddress(['area' => '@#$%sdu  sleiyf *>']);

        $this->post(route('users.billingAddress.update'), $formValues)
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
        $formValues = $this->mergeAddress(['landmark' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam tempora sunt quae rem aspernatur labore corporis']);

        $this->post(route('users.billingAddress.update'), $formValues)
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
        $formValues = $this->mergeAddress(['landmark' => '@#$%sdu  sleiyf *>']);

        $this->post(route('users.billingAddress.update'), $formValues)
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
        $formValues = $this->mergeAddress(['city' => '']);

        $this->post(route('users.billingAddress.update'), $formValues)
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
        $formValues = $this->mergeAddress(['city' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam tempora sunt quae rem aspernatur labore corporis']);

        $this->post(route('users.billingAddress.update'), $formValues)
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
        $formValues = $this->mergeAddress(['city' => '@#$%sdu  sleiyf *>']);

        $this->post(route('users.billingAddress.update'), $formValues)
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
        $formValues = $this->mergeAddress(['pin_code' => '']);

        $this->post(route('users.billingAddress.update'), $formValues)
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
        $formValues = $this->mergeAddress(['pin_code' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam tempora sunt quae rem aspernatur labore corporis']);

        $this->post(route('users.billingAddress.update'), $formValues)
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
        $formValues = $this->mergeAddress(['pin_code' => '@#$%sdu  sleiyf *>']);

        $this->post(route('users.billingAddress.update'), $formValues)
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
        $formValues = $this->mergeAddress(['state' => '']);

        $this->post(route('users.billingAddress.update'), $formValues)
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
        $formValues = $this->mergeAddress(['state' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam tempora sunt quae rem aspernatur labore corporis']);

        $this->post(route('users.billingAddress.update'), $formValues)
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
        $formValues = $this->mergeAddress(['state' => '@#$%sdu  sleiyf *>']);

        $this->post(route('users.billingAddress.update'), $formValues)
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
        $formValues = $this->mergeAddress(['country' => '']);

        $this->post(route('users.billingAddress.update'), $formValues)
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
        $formValues = $this->mergeAddress(['country' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam tempora sunt quae rem aspernatur labore corporis']);

        $this->post(route('users.billingAddress.update'), $formValues)
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
        $formValues = $this->mergeAddress(['country' => '@#$%sdu  sleiyf *>']);

        $this->post(route('users.billingAddress.update'), $formValues)
             ->assertSessionHasErrors('country');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('country'),
            'The country has got invalid characters.'
        );
    }

    /**
     * Merge the user's adress.
     *
     * @param   array  $address
     * @return  array
     */
    protected function mergeAddress($address = [])
    {
        return array_merge([
            'address_line_1' => '4/39, HAMOCHS',
            'address_line_2' => 'Government Colony',
            'area'           => 'Haji Ali',
            'landmark'       => 'Behind Lala Lajpatrai College',
            'city'           => 'Mumbai',
            'pin_code'       => '400034',
            'state'          => 'Maharashtra',
            'country'        => 'India',
        ], $address);
    }
}
