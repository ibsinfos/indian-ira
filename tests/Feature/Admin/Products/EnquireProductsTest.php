<?php

namespace Tests\Feature\Admin\Products;

use Tests\TestCase;
use IndianIra\EnquireProduct;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EnquireProductsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp()
    {
        parent::setUp();

        $this->signInSuperAdministrator();
    }

    /** @test */
    function no_product_enquiries_exists()
    {
        $this->assertCount(0, EnquireProduct::all());
    }

    /** @test */
    function non_administrator_cannot_access_the_product_enquiries_section()
    {
        auth()->logout();

        $this->withoutExceptionHandling()
             ->get(route('admin.products.enquiries'))
             ->assertStatus(302)
             ->assertRedirect(route('admin.login'));
    }

    /** @test */
    function only_administrator_can_access_the_product_enquiries_section()
    {
        $this->withoutExceptionHandling()
             ->get(route('admin.products.enquiries'))
             ->assertViewIs('admin.products-enquiries.index')
             ->assertSee('List of Products Enquiries');
    }

    /** @test */
    function super_administrator_can_temporarily_delete_a_product_enquiry()
    {
        $enquiries = factory(EnquireProduct::class, 5)->create();

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.products.enquiries.delete', $enquiries->random()->code));

        $result = json_decode($response->getContent());

        $enquiries = $this->getEnquiries();

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Product Enquiry deleted successfully !');
        $this->assertEquals($result->htmlResult, view('admin.products-enquiries.table', compact('enquiries'))->render());

        $this->assertCount(5, $enquiries);
        $this->assertCount(4, $enquiries->where('deleted_at', null));
        $this->assertCount(1, $enquiries->where('deleted_at', '<>', null));
    }

    /** @test */
    function super_administrator_cannot_temporarily_delete_a_product_enquiry_that_does_not_exists()
    {
        $enquiries = factory(EnquireProduct::class, 5)->create();

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.products.enquiries.delete', 'some-random-code'));

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Product Enquiry with that code cannot be found!');

        $this->assertCount(5, $enquiries);
        $this->assertCount(5, $enquiries->where('deleted_at', null));
        $this->assertCount(0, $enquiries->where('deleted_at', '<>', null));
    }

    /** @test */
    function super_administrator_can_restore_a_temporarily_deleted_product_enquiry()
    {
        $enquiries = factory(EnquireProduct::class, 5)->create();
        $deleted = factory(EnquireProduct::class)->create(['deleted_at' => now()]);

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.products.enquiries.restore', $deleted->code));

        $result = json_decode($response->getContent());

        $enquiries = $this->getEnquiries();

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Product Enquiry restored successfully !');
        $this->assertEquals($result->htmlResult, view('admin.products-enquiries.table', compact('enquiries'))->render());

        $this->assertCount(6, $enquiries);
        $this->assertCount(6, $enquiries->where('deleted_at', null));
        $this->assertCount(0, $enquiries->where('deleted_at', '<>', null));
    }

    /** @test */
    function super_administrator_cannot__restore_a_temporarily_deleted_product_enquiry_that_does_not_exists()
    {
        $enquiries = factory(EnquireProduct::class, 5)->create();

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.products.enquiries.restore', 'some-random-code'));

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Product Enquiry with that code cannot be found!');
    }

    /** @test */
    function super_administrator_can_permanently_delete_a_temporarily_deleted_product_enquiry()
    {
        $enquiries = factory(EnquireProduct::class, 5)->create();
        $deleted = factory(EnquireProduct::class)->create(['deleted_at' => now()]);

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.products.enquiries.destroy', $deleted->code));

        $result = json_decode($response->getContent());

        $enquiries = $this->getEnquiries();

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Product Enquiry destroyed successfully !');
        $this->assertEquals($result->htmlResult, view('admin.products-enquiries.table', compact('enquiries'))->render());

        $this->assertCount(5, $enquiries);
        $this->assertCount(5, $enquiries->where('deleted_at', null));
        $this->assertCount(0, $enquiries->where('deleted_at', '<>', null));
    }

    /** @test */
    function super_administrator_cannot_permanently_delete_a_temporarily_deleted_product_enquiry()
    {
        $enquiries = factory(EnquireProduct::class, 5)->create();

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.products.enquiries.destroy', 'some-random-code'));

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Product Enquiry with that code cannot be found!');
    }

    /**
     * Get all the products enquiries.
     *
     * @return  \Illuminate\Support\Collection
     */
    protected function getEnquiries()
    {
        return EnquireProduct::withTrashed()
                                ->orderBy('id', 'DESC')
                                ->get();
    }
}
