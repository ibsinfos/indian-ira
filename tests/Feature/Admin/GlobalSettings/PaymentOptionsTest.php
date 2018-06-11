<?php

namespace Tests\Feature\Admin\GlobalSettings;

use Tests\TestCase;
use IndianIra\GlobalSettingPaymentOption;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentOptionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function no_data_found_in_global_settings_payment_options_table()
    {
        $this->assertNull(GlobalSettingPaymentOption::first());
    }

    /** @test */
    function only_logged_in_super_administrator_can_access_the_global_settings_payment_options_section()
    {
        $this->generateSuperAdministrator();

        $this->withoutExceptionHandling()
             ->get(route('admin.globalSettings.paymentOptions'))
             ->assertStatus(302)
             ->assertRedirect(route('admin.login'));
    }

    /** @test */
    function super_administrator_can_view_the_global_settings_payment_options_section()
    {
        $this->signInSuperAdministrator();

        $this->withoutExceptionHandling()
             ->get(route('admin.globalSettings.paymentOptions'))
             ->assertViewIs('admin.global-settings.payment-options')
             ->assertSee('Payment Options');
    }

    /** @test */
    function super_administrator_can_submit_the_payment_options()
    {
        $this->signInSuperAdministrator();

        $formValues = factory(GlobalSettingPaymentOption::class)->make()->toArray();

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.globalSettings.paymentOptions.update'), $formValues);

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Payment Options updated successfully! Redirecting...');
        $this->assertEquals($result->location, route('admin.dashboard'));

        $this->assertNotNull(GlobalSettingPaymentOption::first());
    }

    /** @test */
    function super_administrator_can_update_the_payment_options()
    {
        $this->signInSuperAdministrator();

        $paymentOptions = factory(GlobalSettingPaymentOption::class)->create();

        $formValues = array_merge($paymentOptions->toArray(), ['chosen' => 'online; offline']);

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.globalSettings.paymentOptions.update'), $formValues);

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Payment Options updated successfully! Redirecting...');
        $this->assertEquals($result->location, route('admin.dashboard'));

        $this->assertEquals(GlobalSettingPaymentOption::first()->chosen, 'online; offline');
        $this->assertNotEquals(GlobalSettingPaymentOption::first()->chosen, $paymentOptions->chosen);
    }

    /** @test */
    function chosen_field_is_required()
    {
        $this->signInSuperAdministrator();

        $bankDetails = factory(GlobalSettingPaymentOption::class)->make();
        $formValues = array_merge($bankDetails->toArray(), ['chosen' => '']);

        $this->post(route('admin.globalSettings.paymentOptions.update'), $formValues)
            ->assertSessionHasErrors('chosen');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('chosen'),
            'The chosen field is required.'
        );
    }
}
