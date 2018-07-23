<?php

namespace Tests\Feature\Admin\GlobalSettings;

use Tests\TestCase;
use IndianIra\GlobalSettingCompanyAddress;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyAddressTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->signInSuperAdministrator();
    }

    /** @test */
    function no_data_found_in_global_settings_company_address_table()
    {
        $this->assertNull(GlobalSettingCompanyAddress::first());
    }

    /** @test */
    function only_logged_in_super_administrator_can_access_the_global_settings_company_address_section()
    {
        auth()->logout();

        $this->withoutExceptionHandling()
             ->get(route('admin.globalSettings.companyAddress'))
             ->assertStatus(302)
             ->assertRedirect(route('admin.login'));
    }

    /** @test */
    function super_administrator_can_view_the_global_settings_company_address_section()
    {
        $this->withoutExceptionHandling()
             ->get(route('admin.globalSettings.companyAddress'))
             ->assertViewIs('admin.global-settings.company-address')
             ->assertSee('Company Address');
    }

    /** @test */
    function super_administrator_can_submit_the_company_address()
    {
        $formValues = factory(GlobalSettingCompanyAddress::class)->make()->toArray();

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.globalSettings.companyAddress.update'), $formValues);

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Company Address updated successfully! Redirecting...');
        $this->assertEquals($result->location, route('admin.dashboard'));

        $this->assertNotNull(GlobalSettingCompanyAddress::first());
    }

    /** @test */
    function super_administrator_can_update_the_company_address()
    {
        $formValues = $this->mergeCompanyAddressData(['address_line_1' => 'Bank of Baroda']);

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.globalSettings.companyAddress.update'), $formValues);

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Company Address updated successfully! Redirecting...');
        $this->assertEquals($result->location, route('admin.dashboard'));

        $this->assertEquals(GlobalSettingCompanyAddress::first()->address_line_1, 'Bank of Baroda');
        $this->assertNotEquals($formValues['address_line_1'], '4/39, HAMOCHS');
    }

    /** @test */
    function only_super_administrator_can_submit_their_billing_address_details()
    {
        auth()->logout();

        $this->withExceptionHandling()
            ->post(route('admin.globalSettings.companyAddress.update'), $this->mergeCompanyAddressData())
            ->assertStatus(302)
            ->assertRedirect(route('admin.login'));
    }

    /** @test */
    function address_line_1_field_is_required()
    {
        $formValues = $this->mergeCompanyAddressData(['address_line_1' => '']);

        $this->post(route('admin.globalSettings.companyAddress.update'), $formValues)
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
        $formValues = $this->mergeCompanyAddressData(['address_line_1' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam tempora sunt quae rem aspernatur labore corporis']);

        $this->post(route('admin.globalSettings.companyAddress.update'), $formValues)
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
        $formValues = $this->mergeCompanyAddressData(['address_line_1' => '@#$%sdu  sleiyf *>']);

        $this->post(route('admin.globalSettings.companyAddress.update'), $formValues)
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
        $formValues = $this->mergeCompanyAddressData(['address_line_2' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam tempora sunt quae rem aspernatur labore corporis']);

        $this->post(route('admin.globalSettings.companyAddress.update'), $formValues)
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
        $formValues = $this->mergeCompanyAddressData(['address_line_2' => '@#$%sdu  sleiyf *>']);

        $this->post(route('admin.globalSettings.companyAddress.update'), $formValues)
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
        $formValues = $this->mergeCompanyAddressData(['area' => '']);

        $this->post(route('admin.globalSettings.companyAddress.update'), $formValues)
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
        $formValues = $this->mergeCompanyAddressData(['area' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam tempora sunt quae rem aspernatur labore corporis']);

        $this->post(route('admin.globalSettings.companyAddress.update'), $formValues)
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
        $formValues = $this->mergeCompanyAddressData(['area' => '@#$%sdu  sleiyf *>']);

        $this->post(route('admin.globalSettings.companyAddress.update'), $formValues)
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
        $formValues = $this->mergeCompanyAddressData(['landmark' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam tempora sunt quae rem aspernatur labore corporis']);

        $this->post(route('admin.globalSettings.companyAddress.update'), $formValues)
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
        $formValues = $this->mergeCompanyAddressData(['landmark' => '@#$%sdu  sleiyf *>']);

        $this->post(route('admin.globalSettings.companyAddress.update'), $formValues)
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
        $formValues = $this->mergeCompanyAddressData(['city' => '']);

        $this->post(route('admin.globalSettings.companyAddress.update'), $formValues)
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
        $formValues = $this->mergeCompanyAddressData(['city' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam tempora sunt quae rem aspernatur labore corporis']);

        $this->post(route('admin.globalSettings.companyAddress.update'), $formValues)
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
        $formValues = $this->mergeCompanyAddressData(['city' => '@#$%sdu  sleiyf *>']);

        $this->post(route('admin.globalSettings.companyAddress.update'), $formValues)
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
        $formValues = $this->mergeCompanyAddressData(['pin_code' => '']);

        $this->post(route('admin.globalSettings.companyAddress.update'), $formValues)
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
        $formValues = $this->mergeCompanyAddressData(['pin_code' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam tempora sunt quae rem aspernatur labore corporis']);

        $this->post(route('admin.globalSettings.companyAddress.update'), $formValues)
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
        $formValues = $this->mergeCompanyAddressData(['pin_code' => '@#$%sdu  sleiyf *>']);

        $this->post(route('admin.globalSettings.companyAddress.update'), $formValues)
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
        $formValues = $this->mergeCompanyAddressData(['state' => '']);

        $this->post(route('admin.globalSettings.companyAddress.update'), $formValues)
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
        $formValues = $this->mergeCompanyAddressData(['state' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam tempora sunt quae rem aspernatur labore corporis']);

        $this->post(route('admin.globalSettings.companyAddress.update'), $formValues)
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
        $formValues = $this->mergeCompanyAddressData(['state' => '@#$%sdu  sleiyf *>']);

        $this->post(route('admin.globalSettings.companyAddress.update'), $formValues)
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
        $formValues = $this->mergeCompanyAddressData(['country' => '']);

        $this->post(route('admin.globalSettings.companyAddress.update'), $formValues)
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
        $formValues = $this->mergeCompanyAddressData(['country' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam tempora sunt quae rem aspernatur labore corporis']);

        $this->post(route('admin.globalSettings.companyAddress.update'), $formValues)
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
        $formValues = $this->mergeCompanyAddressData(['country' => '@#$%sdu  sleiyf *>']);

        $this->post(route('admin.globalSettings.companyAddress.update'), $formValues)
             ->assertSessionHasErrors('country');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('country'),
            'The country has got invalid characters.'
        );
    }

    public function mergeCompanyAddressData($attributes = [])
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
        ], $attributes);
    }
}
