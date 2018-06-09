<?php

namespace Tests\Feature\Admin\GlobalSettings;

use Tests\TestCase;
use IndianIra\GlobalSettingBankDetail;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BankDetailsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function no_data_found_in_global_settings_bank_details_table()
    {
        $this->assertNull(GlobalSettingBankDetail::first());
    }

    /** @test */
    function only_logged_in_super_administrator_can_access_the_global_settings_bank_details_section()
    {
        $this->generateSuperAdministrator();

        $this->withoutExceptionHandling()
             ->get(route('admin.globalSettings.bank'))
             ->assertStatus(302)
             ->assertRedirect(route('admin.login'));
    }

    /** @test */
    function super_administrator_can_view_the_global_settings_bank_details_section()
    {
        $this->signInSuperAdministrator();

        $this->withoutExceptionHandling()
             ->get(route('admin.globalSettings.bank'))
             ->assertViewIs('admin.global-settings.bank-details')
             ->assertSee('Bank Details');
    }

    /** @test */
    function super_administrator_can_submit_the_bank_details()
    {
        $this->signInSuperAdministrator();

        $formValues = factory(GlobalSettingBankDetail::class)->make()->toArray();

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.globalSettings.bank.update'), $formValues);

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Bank details updated successfully! Redirecting...');
        $this->assertEquals($result->location, route('admin.dashboard'));

        $this->assertNotNull(GlobalSettingBankDetail::first());
    }

    /** @test */
    function super_administrator_can_update_the_bank_details()
    {
        $this->signInSuperAdministrator();

        $bankDetails = factory(GlobalSettingBankDetail::class)->create();

        $formValues = array_merge($bankDetails->toArray(), ['bank_name' => 'Bank of Baroda']);

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.globalSettings.bank.update'), $formValues);

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Bank details updated successfully! Redirecting...');
        $this->assertEquals($result->location, route('admin.dashboard'));

        $this->assertEquals(GlobalSettingBankDetail::first()->bank_name, 'Bank of Baroda');
        $this->assertNotEquals(GlobalSettingBankDetail::first()->bank_name, $bankDetails->bank_name);
    }

    /** @test */
    function account_holder_name_field_is_required()
    {
        $this->signInSuperAdministrator();

        $bankDetails = factory(GlobalSettingBankDetail::class)->make();
        $formValues = array_merge($bankDetails->toArray(), ['account_holder_name' => '']);

        $this->post(route('admin.globalSettings.bank.update'), $formValues)
            ->assertSessionHasErrors('account_holder_name');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('account_holder_name'),
            'The account holder name field is required.'
        );
    }

    /** @test */
    function account_holder_name_should_be_less_than_200_characters()
    {
        $this->signInSuperAdministrator();

        $bankDetails = factory(GlobalSettingBankDetail::class)->make();
        $formValues = array_merge($bankDetails->toArray(), ['account_holder_name' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nemo iusto iure saepe recusandae aut placeat dolorum beatae, iste qui! Dicta nihil magni excepturi nostrum eveniet dolore distinctio mollitia ipsam aut.']);

        $this->post(route('admin.globalSettings.bank.update'), $formValues)
            ->assertSessionHasErrors('account_holder_name');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('account_holder_name'),
            'The account holder name may not be greater than 200 characters.'
        );
    }

    /** @test */
    function account_type_field_is_required()
    {
        $this->signInSuperAdministrator();

        $bankDetails = factory(GlobalSettingBankDetail::class)->make();
        $formValues = array_merge($bankDetails->toArray(), ['account_type' => '']);

        $this->post(route('admin.globalSettings.bank.update'), $formValues)
            ->assertSessionHasErrors('account_type');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('account_type'),
            'The account type field is required.'
        );
    }

    /** @test */
    function account_type_should_be_less_than_200_characters()
    {
        $this->signInSuperAdministrator();

        $bankDetails = factory(GlobalSettingBankDetail::class)->make();
        $formValues = array_merge($bankDetails->toArray(), ['account_type' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nemo iusto iure saepe recusandae aut placeat dolorum beatae, iste qui! Dicta nihil magni excepturi nostrum eveniet dolore distinctio mollitia ipsam aut.']);

        $this->post(route('admin.globalSettings.bank.update'), $formValues)
            ->assertSessionHasErrors('account_type');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('account_type'),
            'The account type may not be greater than 200 characters.'
        );
    }

    /** @test */
    function account_number_field_is_required()
    {
        $this->signInSuperAdministrator();

        $bankDetails = factory(GlobalSettingBankDetail::class)->make();
        $formValues = array_merge($bankDetails->toArray(), ['account_number' => '']);

        $this->post(route('admin.globalSettings.bank.update'), $formValues)
            ->assertSessionHasErrors('account_number');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('account_number'),
            'The account number field is required.'
        );
    }

    /** @test */
    function account_number_should_contain_only_alpha_numeric_characters()
    {
        $this->signInSuperAdministrator();

        $bankDetails = factory(GlobalSettingBankDetail::class)->make();
        $formValues = array_merge($bankDetails->toArray(), ['account_number' => 'sld58srv- * si234#$^&']);

        $this->post(route('admin.globalSettings.bank.update'), $formValues)
            ->assertSessionHasErrors('account_number');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('account_number'),
            'The account number may only contain letters and numbers.'
        );
    }

    /** @test */
    function account_number_should_be_less_than_50_characters()
    {
        $this->signInSuperAdministrator();

        $bankDetails = factory(GlobalSettingBankDetail::class)->make();
        $formValues = array_merge($bankDetails->toArray(), ['account_number' => strtoupper(str_random(55))]);

        $this->post(route('admin.globalSettings.bank.update'), $formValues)
            ->assertSessionHasErrors('account_number');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('account_number'),
            'The account number may not be greater than 50 characters.'
        );
    }

    /** @test */
    function bank_name_field_is_required()
    {
        $this->signInSuperAdministrator();

        $bankDetails = factory(GlobalSettingBankDetail::class)->make();
        $formValues = array_merge($bankDetails->toArray(), ['bank_name' => '']);

        $this->post(route('admin.globalSettings.bank.update'), $formValues)
            ->assertSessionHasErrors('bank_name');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('bank_name'),
            'The bank name field is required.'
        );
    }

    /** @test */
    function bank_name_should_be_less_than_200_characters()
    {
        $this->signInSuperAdministrator();

        $bankDetails = factory(GlobalSettingBankDetail::class)->make();
        $formValues = array_merge($bankDetails->toArray(), ['bank_name' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nemo iusto iure saepe recusandae aut placeat dolorum beatae, iste qui! Dicta nihil magni excepturi nostrum eveniet dolore distinctio mollitia ipsam aut.']);

        $this->post(route('admin.globalSettings.bank.update'), $formValues)
            ->assertSessionHasErrors('bank_name');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('bank_name'),
            'The bank name may not be greater than 200 characters.'
        );
    }

    /** @test */
    function bank_branch_and_city_field_is_required()
    {
        $this->signInSuperAdministrator();

        $bankDetails = factory(GlobalSettingBankDetail::class)->make();
        $formValues = array_merge($bankDetails->toArray(), ['bank_branch_and_city' => '']);

        $this->post(route('admin.globalSettings.bank.update'), $formValues)
            ->assertSessionHasErrors('bank_branch_and_city');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('bank_branch_and_city'),
            'The bank branch and city field is required.'
        );
    }

    /** @test */
    function bank_branch_and_city_should_be_less_than_200_characters()
    {
        $this->signInSuperAdministrator();

        $bankDetails = factory(GlobalSettingBankDetail::class)->make();
        $formValues = array_merge($bankDetails->toArray(), ['bank_branch_and_city' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nemo iusto iure saepe recusandae aut placeat dolorum beatae, iste qui! Dicta nihil magni excepturi nostrum eveniet dolore distinctio mollitia ipsam aut.']);

        $this->post(route('admin.globalSettings.bank.update'), $formValues)
            ->assertSessionHasErrors('bank_branch_and_city');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('bank_branch_and_city'),
            'The bank branch and city may not be greater than 200 characters.'
        );
    }

    /** @test */
    function bank_ifsc_code_field_is_required()
    {
        $this->signInSuperAdministrator();

        $bankDetails = factory(GlobalSettingBankDetail::class)->make();
        $formValues = array_merge($bankDetails->toArray(), ['bank_ifsc_code' => '']);

        $this->post(route('admin.globalSettings.bank.update'), $formValues)
            ->assertSessionHasErrors('bank_ifsc_code');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('bank_ifsc_code'),
            'The bank ifsc code field is required.'
        );
    }

    /** @test */
    function bank_ifsc_code_should_contain_only_alpha_numeric_characters()
    {
        $this->signInSuperAdministrator();

        $bankDetails = factory(GlobalSettingBankDetail::class)->make();
        $formValues = array_merge($bankDetails->toArray(), ['bank_ifsc_code' => 'sld58srv- * si234#$^&']);

        $this->post(route('admin.globalSettings.bank.update'), $formValues)
            ->assertSessionHasErrors('bank_ifsc_code');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('bank_ifsc_code'),
            'The bank ifsc code may only contain letters and numbers.'
        );
    }

    /** @test */
    function bank_ifsc_code_should_be_less_than_20_characters()
    {
        $this->signInSuperAdministrator();

        $bankDetails = factory(GlobalSettingBankDetail::class)->make();
        $formValues = array_merge($bankDetails->toArray(), ['bank_ifsc_code' => str_random(50)]);

        $this->post(route('admin.globalSettings.bank.update'), $formValues)
            ->assertSessionHasErrors('bank_ifsc_code');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('bank_ifsc_code'),
            'The bank ifsc code may not be greater than 20 characters.'
        );
    }
}
