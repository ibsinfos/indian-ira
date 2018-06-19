<?php

namespace Tests\Feature\Admin\GlobalSettings;

use Tests\TestCase;
use IndianIra\GlobalSettingCodCharge;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CodChargesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function no_data_found_in_global_settings_cod_charges_table()
    {
        $this->assertNull(GlobalSettingCodCharge::first());
    }

    /** @test */
    function only_logged_in_super_administrator_can_access_the_global_settings_cod_charges_section()
    {
        $this->generateSuperAdministrator();

        $this->withoutExceptionHandling()
             ->get(route('admin.globalSettings.codCharges'))
             ->assertStatus(302)
             ->assertRedirect(route('admin.login'));
    }

    /** @test */
    function super_administrator_can_view_the_global_settings_cod_charges_section()
    {
        $this->signInSuperAdministrator();

        $this->withoutExceptionHandling()
             ->get(route('admin.globalSettings.codCharges'))
             ->assertViewIs('admin.global-settings.cod-charges')
             ->assertSee('COD Charges');
    }

    /** @test */
    function super_administrator_can_submit_the_cod_charges()
    {
        $this->signInSuperAdministrator();

        $formValues = factory(GlobalSettingCodCharge::class)->make()->toArray();

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.globalSettings.codCharges.update'), $formValues);

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'COD Charges updated successfully! Redirecting...');
        $this->assertEquals($result->location, route('admin.dashboard'));

        $this->assertNotNull(GlobalSettingCodCharge::first());
    }

    /** @test */
    function super_administrator_can_update_the_cod_charges()
    {
        $this->signInSuperAdministrator();

        $codCharges = factory(GlobalSettingCodCharge::class)->create();

        $formValues = array_merge($codCharges->toArray(), ['amount' => '75.00']);

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.globalSettings.codCharges.update'), $formValues);

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'COD Charges updated successfully! Redirecting...');
        $this->assertEquals($result->location, route('admin.dashboard'));

        $this->assertEquals(number_format(GlobalSettingCodCharge::first()->amount, 2), '75.00');
        $this->assertNotEquals(GlobalSettingCodCharge::first()->amount, $codCharges->amount);
    }

    /** @test */
    function chosen_field_is_required()
    {
        $this->signInSuperAdministrator();

        $bankDetails = factory(GlobalSettingCodCharge::class)->make();
        $formValues = array_merge($bankDetails->toArray(), ['amount' => '']);

        $this->post(route('admin.globalSettings.codCharges.update'), $formValues)
            ->assertSessionHasErrors('amount');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('amount'),
            'The amount field is required.'
        );
    }

    /** @test */
    function chosen_field_is_should_contain_only_numeric_values_with_optional_decimal()
    {
        $this->signInSuperAdministrator();

        $bankDetails = factory(GlobalSettingCodCharge::class)->make();
        $formValues = array_merge($bankDetails->toArray(), ['amount' => 'jyfsdf68412124.2185qef']);

        $this->post(route('admin.globalSettings.codCharges.update'), $formValues)
            ->assertSessionHasErrors('amount');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('amount'),
            'The cod charges amount field should contain only numeric values with optional 2 digits after decimal.'
        );
    }
}
