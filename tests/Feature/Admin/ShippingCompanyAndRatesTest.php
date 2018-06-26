<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use IndianIra\ShippingRate;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShippingCompanyAndRatesTest extends TestCase
{
    use RefreshDatabase;

    public $admin;

    public function setUp()
    {
        parent::setUp();

        $this->admin = $this->generateSuperAdministrator();
    }

    /** @test */
    function only_logged_in_super_administrator_can_access_the_shipping_rates_section()
    {
        $this->withoutExceptionHandling()
             ->get(route('admin.shippingRates'))
             ->assertStatus(302)
             ->assertRedirect(route('admin.login'));
    }

    /** @test */
    function super_administrator_can_view_the_shipping_rates_section()
    {
        $this->signInSuperAdministrator($this->admin);

        $this->withoutExceptionHandling()
             ->get(route('admin.shippingRates'))
             ->assertViewIs('admin.shipping-rates.index')
             ->assertSee('Shipping Rates');
    }

    /** @test */
    function no_shipping_rates_data_exists()
    {
        $this->assertCount(0, ShippingRate::all());
    }

    /** @test */
    function super_administrator_can_add_new_shipping_rates()
    {
        $this->signInSuperAdministrator($this->admin);

        $formValues = factory(ShippingRate::class)->make([
            'weight_from' => 1,
            'weight_to' => 500,
            'amount' => 75.0
        ])->toArray();

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.shippingRates.store'), $formValues);

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Shipping Rates added successfully!');
        $this->assertEquals($result->location, route('admin.shippingRates'));

        $this->assertNotNull(ShippingRate::first());
    }

    /** @test */
    function super_administrator_can_update_an_existing_shipping_rate()
    {
        $this->signInSuperAdministrator($this->admin);

        $shippingRate = factory(ShippingRate::class)->create([
            'weight_from' => 1,
            'weight_to' => 500,
            'amount' => 75.0
        ]);
        $formValues = array_merge(
                        $shippingRate->toArray(),
                        ['shipping_company_name' => 'This Awesome Logistics Company']
                    );

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.shippingRates.update', $shippingRate->id), $formValues);

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Shipping Rate updated successfully!');
        $this->assertEquals($result->location, route('admin.shippingRates'));

        $this->assertEquals($shippingRate->fresh()->shipping_company_name, 'This Awesome Logistics Company');
        $this->assertNotEquals(ShippingRate::first()->shipping_company_name, $shippingRate->shipping_company_name);
    }

    /** @test */
    function super_administrator_cannot_update_a_shipping_rate_that_does_not_exist()
    {
        $this->signInSuperAdministrator($this->admin);

        $shippingRates = factory(ShippingRate::class, 3)->create();
        $formValues = factory(ShippingRate::class)->make([
            'weight_from' => 1,
            'weight_to' => 500,
            'amount' => 75.0
        ])->toArray();

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.shippingRates.update', 50), $formValues);

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Shipping Rate with that id cannot be found.');

        $this->assertCount(3, ShippingRate::all());
    }

    /** @test */
    function super_administrator_can_temporarily_delete_a_shipping_rate()
    {
        $this->signInSuperAdministrator($this->admin);

        $shippingRates = factory(ShippingRate::class, 3)->create();

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.shippingRates.delete', 1));

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Shipping Rate deleted temporarily!');
        $this->assertEquals($result->location, route('admin.shippingRates'));

        $this->assertCount(3, ShippingRate::withTrashed()->get());
        $this->assertCount(2, ShippingRate::all());
        $this->assertCount(1, ShippingRate::onlyTrashed()->get());
    }

    /** @test */
    function super_administrator_cannot_temporarily_delete_a_shipping_rate_that_does_not_exists()
    {
        $this->signInSuperAdministrator($this->admin);

        $shippingRates = factory(ShippingRate::class, 3)->create();

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.shippingRates.delete', 10));

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Shipping Rate with that id cannot be found.');

        $this->assertCount(3, ShippingRate::withTrashed()->get());
        $this->assertCount(3, ShippingRate::all());
        $this->assertCount(0, ShippingRate::onlyTrashed()->get());
    }

    /** @test */
    function super_administrator_can_restore_a_temporarily_deleted_shipping_rate()
    {
        $this->signInSuperAdministrator($this->admin);

        factory(ShippingRate::class, 3)->create();
        $shippingRate = factory(ShippingRate::class)->create(['deleted_at' => \Carbon\Carbon::now()]);

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.shippingRates.restore', $shippingRate->id));

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Shipping Rate restored successfully!');
        $this->assertEquals($result->location, route('admin.shippingRates'));

        $this->assertCount(4, ShippingRate::withTrashed()->get());
        $this->assertCount(4, ShippingRate::all());
        $this->assertCount(0, ShippingRate::onlyTrashed()->get());
    }

    /** @test */
    function super_administrator_cannot_restore_a_temporarily_deleted_shipping_rate_that_does_not_exists()
    {
        $this->signInSuperAdministrator($this->admin);

        $shippingRates = factory(ShippingRate::class, 3)->create();
        $shippingRate = factory(ShippingRate::class)->create(['deleted_at' => \Carbon\Carbon::now()]);

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.shippingRates.restore', 10));

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Shipping Rate with that id cannot be found.');

        $this->assertCount(4, ShippingRate::withTrashed()->get());
        $this->assertCount(3, ShippingRate::all());
        $this->assertCount(1, ShippingRate::onlyTrashed()->get());
    }

    /** @test */
    function super_administrator_can_permanently_delete_a_temporarily_deleted_shipping_rate()
    {
        $this->signInSuperAdministrator($this->admin);

        factory(ShippingRate::class, 3)->create();
        $shippingRate = factory(ShippingRate::class)->create(['deleted_at' => \Carbon\Carbon::now()]);

        $this->assertCount(4, ShippingRate::withTrashed()->get());

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.shippingRates.destroy', $shippingRate->id));

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Shipping Rate destroyed successfully!');
        $this->assertEquals($result->location, route('admin.shippingRates'));

        $this->assertCount(3, ShippingRate::withTrashed()->get());
        $this->assertCount(3, ShippingRate::all());
        $this->assertCount(0, ShippingRate::onlyTrashed()->get());
    }

    /** @test */
    function super_administrator_cannot_permanently_delete_a_temporarily_deleted_shipping_rate_that_does_not_exists()
    {
        $this->signInSuperAdministrator($this->admin);

        $shippingRates = factory(ShippingRate::class, 3)->create();
        $shippingRate = factory(ShippingRate::class)->create(['deleted_at' => \Carbon\Carbon::now()]);

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.shippingRates.destroy', 10));

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Shipping Rate with that id cannot be found.');

        $this->assertCount(4, ShippingRate::withTrashed()->get());
        $this->assertCount(3, ShippingRate::all());
        $this->assertCount(1, ShippingRate::onlyTrashed()->get());
    }

    /** @test */
    function shipping_company_name_field_is_required()
    {
        $this->signInSuperAdministrator($this->admin);

        $shippingRate = factory(ShippingRate::class)->make();
        $formValues = array_merge($shippingRate->toArray(), ['shipping_company_name' => '']);

        $this->post(route('admin.shippingRates.store'), $formValues)
            ->assertSessionHasErrors('shipping_company_name');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('shipping_company_name'),
            'The shipping company name field is required.'
        );
    }

    /** @test */
    function shipping_company_name_should_be_less_than_250_characters()
    {
        $this->signInSuperAdministrator($this->admin);

        $shippingRate = factory(ShippingRate::class)->make();
        $formValues = array_merge($shippingRate->toArray(), ['shipping_company_name' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam tempora sunt quae rem aspernatur labore corporis soluta earum, aliquam, culpa quis, repellendus enim nemo ducimus vitae iure molestiae. Temporibus, veritatis. Lorem ipsum dolor sit amet, consectetur adipisicing elit.']);

        $this->post(route('admin.shippingRates.store'), $formValues)
            ->assertSessionHasErrors('shipping_company_name');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('shipping_company_name'),
            'The shipping company name may not be greater than 250 characters.'
        );
    }

    /** @test */
    function shipping_company_tracking_url_field_is_required()
    {
        $this->signInSuperAdministrator($this->admin);

        $shippingRate = factory(ShippingRate::class)->make();
        $formValues = array_merge($shippingRate->toArray(), ['shipping_company_tracking_url' => '']);

        $this->post(route('admin.shippingRates.store'), $formValues)
            ->assertSessionHasErrors('shipping_company_tracking_url');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('shipping_company_tracking_url'),
            'The shipping company tracking url field is required.'
        );
    }

    /** @test */
    function shipping_company_tracking_url_should_be_a_proper_url()
    {
        $this->signInSuperAdministrator($this->admin);

        $shippingRate = factory(ShippingRate::class)->make();
        $formValues = array_merge($shippingRate->toArray(), ['shipping_company_tracking_url' => str_random(50)]);

        $this->post(route('admin.shippingRates.store'), $formValues)
            ->assertSessionHasErrors('shipping_company_tracking_url');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('shipping_company_tracking_url'),
            'The shipping company tracking url format is invalid.'
        );
    }

    /** @test */
    function shipping_company_tracking_url_should_be_less_than_250_characters()
    {
        $this->signInSuperAdministrator($this->admin);

        $shippingRate = factory(ShippingRate::class)->make();
        $formValues = array_merge(
            $shippingRate->toArray(), [
                'shipping_company_tracking_url' => 'http://my-site.com/' . str_slug('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit pariatur quos deserunt adipisci cumque, iure omnis modi, blanditiis vero, eveniet explicabo quae. Inventore adipisci repudiandae cumque laudantium ratione qui odio. Lorem ipsum dolor sit amet, consectetur adipisicing elit.')
            ]
        );

        $this->post(route('admin.shippingRates.store'), $formValues)
            ->assertSessionHasErrors('shipping_company_tracking_url');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('shipping_company_tracking_url'),
            'The shipping company tracking url may not be greater than 250 characters.'
        );
    }

    /** @test */
    function weight_from_field_is_required()
    {
        $this->signInSuperAdministrator($this->admin);

        $shippingRate = factory(ShippingRate::class)->make();
        $formValues = array_merge($shippingRate->toArray(), ['weight_from' => '']);

        $this->post(route('admin.shippingRates.store'), $formValues)
            ->assertSessionHasErrors('weight_from');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('weight_from'),
            'The weight from field is required.'
        );
    }

    /** @test */
    function weight_from_field_should_contain_only_numbers_with_decimal_upto_2_precisions_only()
    {
        $this->signInSuperAdministrator($this->admin);

        $shippingRate = factory(ShippingRate::class)->make();
        $formValues = array_merge($shippingRate->toArray(), ['weight_from' => '25,280.0504']);

        $this->post(route('admin.shippingRates.store'), $formValues)
            ->assertSessionHasErrors('weight_from');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('weight_from'),
            'The weight from field should contain only numbers with decimal upto 2 precisions only.'
        );
    }

    /** @test */
    function weight_to_field_is_required()
    {
        $this->signInSuperAdministrator($this->admin);

        $shippingRate = factory(ShippingRate::class)->make();
        $formValues = array_merge($shippingRate->toArray(), ['weight_to' => '']);

        $this->post(route('admin.shippingRates.store'), $formValues)
            ->assertSessionHasErrors('weight_to');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('weight_to'),
            'The weight to field is required.'
        );
    }

    /** @test */
    function weight_to_field_should_contain_only_numbers_with_decimal_upto_2_precisions_only()
    {
        $this->signInSuperAdministrator($this->admin);

        $shippingRate = factory(ShippingRate::class)->make();
        $formValues = array_merge($shippingRate->toArray(), ['weight_to' => '25,280.0504']);

        $this->post(route('admin.shippingRates.store'), $formValues)
            ->assertSessionHasErrors('weight_to');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('weight_to'),
            'The weight to field should contain only numbers with decimal upto 2 precisions only.'
        );
    }

    /** @test */
    function amount_field_is_required()
    {
        $this->signInSuperAdministrator($this->admin);

        $shippingRate = factory(ShippingRate::class)->make();
        $formValues = array_merge($shippingRate->toArray(), ['amount' => '']);

        $this->post(route('admin.shippingRates.store'), $formValues)
            ->assertSessionHasErrors('amount');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('amount'),
            'The amount field is required.'
        );
    }

    /** @test */
    function amount_field_should_contain_only_numbers_with_decimal_upto_2_precisions_only()
    {
        $this->signInSuperAdministrator($this->admin);

        $shippingRate = factory(ShippingRate::class)->make();
        $formValues = array_merge($shippingRate->toArray(), ['amount' => '25,280.0504']);

        $this->post(route('admin.shippingRates.store'), $formValues)
            ->assertSessionHasErrors('amount');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('amount'),
            'The amount field should contain only numbers with decimal upto 2 precisions only.'
        );
    }
}
