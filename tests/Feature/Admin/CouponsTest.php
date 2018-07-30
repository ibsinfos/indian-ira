<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use IndianIra\Coupon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CouponsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->signInSuperAdministrator();
    }

    /** @test */
    function only_logged_in_super_administrator_can_access_the_coupons_section()
    {
        // Logout the Super Administrator
        auth()->logout();

        $this->withoutExceptionHandling()
             ->get(route('admin.coupons'))
             ->assertStatus(302)
             ->assertRedirect(route('admin.login'));
    }

    /** @test */
    function super_administrator_can_view_the_coupons_section()
    {
        $this->withoutExceptionHandling()
             ->get(route('admin.coupons'))
             ->assertViewIs('admin.coupons.index')
             ->assertSee('List of All Coupons');
    }

    /** @test */
    function no_coupons_data_exists()
    {
        $this->assertCount(0, Coupon::all());
    }

    /** @test */
    function super_administrator_can_add_new_coupon()
    {
        $couponsData = $this->mergeCoupons();

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.coupons.store'), $couponsData);

        $result = json_decode($response->getContent());

        $coupons = Coupon::withTrashed()->orderBy('id', 'DESC')->get();

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Coupon added successfully!');
        $this->assertEquals($result->htmlResult, view('admin.coupons.table', compact('coupons'))->render());

        $this->assertNotNull(Coupon::first());
    }

    /** @test */
    function super_administrator_can_update_an_existing_coupon()
    {
        $coupon = factory(Coupon::class)->create();
        $formValues = $this->mergeCoupons(['code' => 'CPJUl2018']);

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.coupons.update', $coupon->id), $formValues);

        $result = json_decode($response->getContent());

        $coupons = Coupon::withTrashed()->orderBy('id', 'DESC')->get();

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Coupon updated successfully!');
        $this->assertEquals($result->htmlResult, view('admin.coupons.table', compact('coupons'))->render());

        $this->assertNotEquals(Coupon::first()->code, $coupon->code);
        $this->assertEquals($coupon->fresh()->code, 'CPJUl2018');
    }

    /** @test */
    function super_administrator_cannot_update_a_coupon_that_does_not_exist()
    {
        $coupons = factory(Coupon::class, 3)->create();

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.coupons.update', 50), $coupons->last()->toArray());

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Coupon with that id cannot be found.');

        $this->assertCount(3, Coupon::all());
    }

    /** @test */
    function super_administrator_can_temporarily_delete_a_coupon()
    {
        $coupons = factory(Coupon::class, 3)->create();

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.coupons.delete', 1));

        $result = json_decode($response->getContent());

        $coupons = Coupon::withTrashed()->orderBy('id', 'DESC')->get();

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Coupon deleted temporarily!');
        $this->assertEquals($result->htmlResult, view('admin.coupons.table', compact('coupons'))->render());

        $this->assertCount(3, $coupons);
        $this->assertCount(2, $coupons->where('deleted_at', null));
        $this->assertCount(1, $coupons->where('deleted_at', '<>', null));
    }

    /** @test */
    function super_administrator_cannot_temporarily_delete_a_coupon_that_does_not_exists()
    {
        $coupons = factory(Coupon::class, 3)->create();

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.coupons.delete', 10));

        $result = json_decode($response->getContent());

        $coupons = Coupon::withTrashed()->orderBy('id', 'DESC')->get();

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Coupon with that id cannot be found.');

        $this->assertCount(3, $coupons);
        $this->assertCount(3, $coupons->where('deleted_at', null));
        $this->assertCount(0, $coupons->where('deleted_at', '<>', null));
    }

    /** @test */
    function super_administrator_can_restore_a_temporarily_deleted_coupon()
    {
        factory(Coupon::class, 3)->create();
        $coupon = factory(Coupon::class)->create(['deleted_at' => \Carbon\Carbon::now()]);

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.coupons.restore', $coupon->id));

        $result = json_decode($response->getContent());

        $coupons = Coupon::withTrashed()->orderBy('id', 'DESC')->get();

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Coupon restored successfully!');
        $this->assertEquals($result->htmlResult, view('admin.coupons.table', compact('coupons'))->render());

        $this->assertCount(4, $coupons);
        $this->assertCount(4, $coupons->where('deleted_at', null));
        $this->assertCount(0, $coupons->where('deleted_at', '<>', null));
    }

    /** @test */
    function super_administrator_cannot_restore_a_temporarily_deleted_coupon_that_does_not_exists()
    {
        $coupons = factory(Coupon::class, 3)->create();
        $coupon = factory(Coupon::class)->create(['deleted_at' => \Carbon\Carbon::now()]);

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.coupons.restore', 10));

        $result = json_decode($response->getContent());

        $coupons = Coupon::withTrashed()->orderBy('id', 'DESC')->get();

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Coupon with that id cannot be found.');

        $this->assertCount(4, $coupons);
        $this->assertCount(3, $coupons->where('deleted_at', null));
        $this->assertCount(1, $coupons->where('deleted_at', '<>', null));
    }

    /** @test */
    function super_administrator_can_permanently_delete_a_temporarily_deleted_coupon()
    {
        factory(Coupon::class, 3)->create();
        $coupon = factory(Coupon::class)->create(['deleted_at' => \Carbon\Carbon::now()]);

        $this->assertCount(4, Coupon::withTrashed()->get());

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.coupons.destroy', $coupon->id));

        $result = json_decode($response->getContent());

        $coupons = Coupon::withTrashed()->orderBy('id', 'DESC')->get();

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Coupon destroyed successfully!');
        $this->assertEquals($result->htmlResult, view('admin.coupons.table', compact('coupons'))->render());

        $this->assertCount(3, $coupons);
        $this->assertCount(3, $coupons->where('deleted_at', null));
        $this->assertCount(0, $coupons->where('deleted_at', '<>', null));
    }

    /** @test */
    function super_administrator_cannot_permanently_delete_a_temporarily_deleted_coupon_that_does_not_exists()
    {
        $coupons = factory(Coupon::class, 3)->create();
        $coupon = factory(Coupon::class)->create(['deleted_at' => \Carbon\Carbon::now()]);

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.coupons.destroy', 10));

        $result = json_decode($response->getContent());

        $coupons = Coupon::withTrashed()->orderBy('id', 'DESC')->get();

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Coupon with that id cannot be found.');

        $this->assertCount(4, $coupons);
        $this->assertCount(3, $coupons->where('deleted_at', null));
        $this->assertCount(1, $coupons->where('deleted_at', '<>', null));
    }

    /** @test */
    function coupon_code_field_is_required()
    {
        $coupon = factory(Coupon::class)->make();
        $formValues = array_merge($coupon->toArray(), ['code' => '']);

        $this->withExceptionHandling()
            ->post(route('admin.coupons.store'), $formValues)
            ->assertSessionHasErrors('code');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('code'),
            'The coupon code field is required.'
        );
    }

    /** @test */
    function coupon_code_should_contain_only_alpha_numeric_characters()
    {
        $coupon = factory(Coupon::class)->make();
        $formValues = array_merge($coupon->toArray(), ['code' => 'Lorem?|ipsum1243dolor$%']);

        $this->withExceptionHandling()
            ->post(route('admin.coupons.store'), $formValues)
            ->assertSessionHasErrors('code');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('code'),
            'The code may only contain letters, numbers, dashes and underscores.'
        );
    }

    /** @test */
    function coupon_code_field_should_be_less_than_30_characters()
    {
        $coupon = factory(Coupon::class)->make();
        $formValues = array_merge($coupon->toArray(), ['code' => 'Lorem_ipsum_dolor_sit_amet_consectetur']);

        $this->withExceptionHandling()
            ->post(route('admin.coupons.store'), $formValues)
            ->assertSessionHasErrors('code');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('code'),
            'The coupon code may not be greater than 30 characters.'
        );
    }

    /** @test */
    function coupon_discount_percent_field_is_required()
    {
        $coupon = factory(Coupon::class)->make();
        $formValues = array_merge($coupon->toArray(), ['discount_percent' => '']);

        $this->withExceptionHandling()
            ->post(route('admin.coupons.store'), $formValues)
            ->assertSessionHasErrors('discount_percent');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('discount_percent'),
            'The discount percent field is required.'
        );
    }

    /** @test */
    function coupon_discount_percent_should_contain_only_numeric_characters()
    {
        $coupon = factory(Coupon::class)->make();
        $formValues = array_merge($coupon->toArray(), ['discount_percent' => 'Lorem? $%']);

        $this->withExceptionHandling()
            ->post(route('admin.coupons.store'), $formValues)
            ->assertSessionHasErrors('discount_percent');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('discount_percent'),
            'The discount percent should contain only numbers with optional decimal upto 2 precisions.'
        );
    }

    /**
     * Merge the user's adress.
     *
     * @param   array  $details
     * @return  array
     */
    protected function mergeCoupons($details = [])
    {
        return array_merge([
            'code'             => 'CP2018',
            'discount_percent' => 18.0,
        ], $details);
    }
}
