<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use IndianIra\Product;
use IndianIra\Carousel;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CarouselsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->signInSuperAdministrator();
    }

    /** @test */
    function only_logged_in_super_administrator_can_access_the_carousels_section()
    {
        // Logout the Super Administrator
        auth()->logout();

        $this->withoutExceptionHandling()
             ->get(route('admin.carousels'))
             ->assertStatus(302)
             ->assertRedirect(route('admin.login'));
    }

    /** @test */
    function super_administrator_can_view_the_carousels_section()
    {
        $this->withoutExceptionHandling()
             ->get(route('admin.carousels'))
             ->assertViewIs('admin.carousels.index')
             ->assertSee('List of All Carousels');
    }

    /** @test */
    function no_carousels_data_exists()
    {
        $this->assertCount(0, Carousel::all());
    }

    /** @test */
    function super_administrator_can_add_new_carousel()
    {
        $products = factory(Product::class, 5)->create();

        $carouselsData = $this->mergeWithMakeCarousel([
            'name'       => 'New Arrivals',
            'product_id' => $products->pluck('id')->shuffle()->toArray()
        ]);

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.carousels.store'), $carouselsData);

        $result = json_decode($response->getContent());
        $carousels = Carousel::withTrashed()->orderBy('id', 'DESC')->get();

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Carousel added successfully!');
        $this->assertEquals($result->location, route('admin.carousels'));
        $this->assertEquals($result->htmlResult, view('admin.carousels.table', compact('carousels'))->render());

        $this->assertNotNull($carousel = Carousel::first());

        $this->assertCount(5, $carousel->products);
    }

    /** @test */
    function super_administrator_can_update_an_existing_carousel()
    {
        $products = factory(Product::class, 5)->create();
        $carousel = factory(Carousel::class)->create();

        $carousel->products()->attach($products->pluck('id')->shuffle()->toArray());

        $this->assertCount(5, $carousel->products);

        $carouselsData = $this->mergeWithMakeCarousel([
            'name'       => 'New Arrivals',
            'product_id' => $products->pluck('id')->take(3)->shuffle()->toArray()
        ]);

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.carousels.update', $carousel->id), $carouselsData);

        $result = json_decode($response->getContent());
        $carousels = Carousel::withTrashed()->orderBy('id', 'DESC')->get();

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Carousel updated successfully!');
        $this->assertEquals($result->location, route('admin.carousels'));
        $this->assertEquals($result->htmlResult, view('admin.carousels.table', compact('carousels'))->render());

        $this->assertNotNull($carousel = Carousel::first());

        $this->assertCount(3, $carousel->products);
    }

    /** @test */
    function super_administrator_cannot_update_a_non_existing_carousel()
    {
        $products = factory(Product::class, 5)->create();
        $carousel = factory(Carousel::class)->create();

        $carousel->products()->attach($products->pluck('id')->shuffle()->toArray());

        $this->assertCount(5, $carousel->products);

        $carouselsData = $this->mergeWithMakeCarousel([
            'name'       => 'New Arrivals',
            'product_id' => $products->pluck('id')->shuffle()->toArray()
        ]);

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.carousels.update', 50), $carouselsData);

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Carousel with that id cannot be found.');
    }

    /** @test */
    function super_administrator_can_temporarily_delete_a_carousel()
    {
        $carousel = factory(Carousel::class, 3)->create();

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.carousels.delete', 1));

        $result = json_decode($response->getContent());

        $carousels = Carousel::withTrashed()->orderBy('id', 'DESC')->get();

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Carousel deleted temporarily!');
        $this->assertEquals($result->htmlResult, view('admin.carousels.table', compact('carousels'))->render());

        $this->assertCount(3, Carousel::withTrashed()->get());
        $this->assertCount(2, Carousel::all());
        $this->assertCount(1, Carousel::onlyTrashed()->get());
    }

    /** @test */
    function super_administrator_cannot_delete_a_non_existent_carousel()
    {
        $carousel = factory(Carousel::class, 3)->create();

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.carousels.delete', 5));

        $result = json_decode($response->getContent());

        $carousels = Carousel::withTrashed()->orderBy('id', 'DESC')->get();

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Carousel with that id cannot be found.');

        $this->assertCount(3, Carousel::withTrashed()->get());
        $this->assertCount(3, Carousel::all());
        $this->assertCount(0, Carousel::onlyTrashed()->get());
    }

    /** @test */
    function super_administrator_can_restore_a_temporarily_deleted_carousel()
    {
        factory(Carousel::class, 3)->create();
        $carousel = factory(Carousel::class)->create(['deleted_at' => now()]);

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.carousels.restore', $carousel->id));

        $result = json_decode($response->getContent());

        $carousels = Carousel::withTrashed()->orderBy('id', 'DESC')->get();

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Carousel restored successfully!');
        $this->assertEquals($result->htmlResult, view('admin.carousels.table', compact('carousels'))->render());

        $this->assertCount(4, Carousel::withTrashed()->get());
        $this->assertCount(4, Carousel::all());
        $this->assertCount(0, Carousel::onlyTrashed()->get());
    }

    /** @test */
    function super_administrator_cannot_restore_a_non_existent_temporarily_deleted_carousel()
    {
        factory(Carousel::class, 3)->create();
        $carousel = factory(Carousel::class)->create(['deleted_at' => now()]);

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.carousels.restore', 5));

        $result = json_decode($response->getContent());

        $carousels = Carousel::withTrashed()->orderBy('id', 'DESC')->get();

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Carousel with that id cannot be found.');

        $this->assertCount(4, Carousel::withTrashed()->get());
        $this->assertCount(3, Carousel::all());
        $this->assertCount(1, Carousel::onlyTrashed()->get());
    }

    /** @test */
    function super_administrator_can_permanently_delete_a_temporarily_deleted_carousel()
    {
        factory(Carousel::class, 3)->create();
        $carousel = factory(Carousel::class)->create(['deleted_at' => now()]);

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.carousels.destroy', $carousel->id));

        $result = json_decode($response->getContent());

        $carousels = Carousel::withTrashed()->orderBy('id', 'DESC')->get();

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Carousel destroyed successfully!');
        $this->assertEquals($result->htmlResult, view('admin.carousels.table', compact('carousels'))->render());

        $this->assertCount(3, Carousel::withTrashed()->get());
        $this->assertCount(3, Carousel::all());
        $this->assertCount(0, Carousel::onlyTrashed()->get());
    }

    /** @test */
    function super_administrator_cannot_permanently_delete_a_non_existent_temporarily_deleted_carousel()
    {
        factory(Carousel::class, 3)->create();
        $carousel = factory(Carousel::class)->create(['deleted_at' => now()]);

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.carousels.destroy', 5));

        $result = json_decode($response->getContent());

        $carousels = Carousel::withTrashed()->orderBy('id', 'DESC')->get();

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Carousel with that id cannot be found.');

        $this->assertCount(4, Carousel::withTrashed()->get());
        $this->assertCount(3, Carousel::all());
        $this->assertCount(1, Carousel::onlyTrashed()->get());
    }

    /** @test */
    function carousel_name_field_is_required()
    {
        $formValues = $this->mergeWithMakeCarousel(['name' => '']);

        $this->post(route('admin.carousels.store'), $formValues)
            ->assertSessionHasErrors('name');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('name'),
            'The carousel name field is required.'
        );
    }

    /** @test */
    function category_name_field_should_be_less_than_50_characters()
    {
        $formValues = $this->mergeWithMakeCarousel(['name' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.']);

        $this->post(route('admin.carousels.store'), $formValues)
            ->assertSessionHasErrors('name');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('name'),
            'The carousel name may not be greater than 50 characters.'
        );
    }

    /** @test */
    function display_field_is_required()
    {
        $formValues = $this->mergeWithMakeCarousel(['display' => '']);

        $this->post(route('admin.carousels.store'), $formValues)
            ->assertSessionHasErrors('display');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('display'),
            'The display field is required.'
        );
    }

    /** @test */
    function display_field_should_be_either_Enabled_or_Disabled()
    {
        $formValues = $this->mergeWithMakeCarousel(['display' => 'sdiyf 5ur6t7y68541']);

        $this->post(route('admin.carousels.store'), $formValues)
            ->assertSessionHasErrors('display');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('display'),
            'The display field should be either Enabled or Disabled.'
        );
    }

    /** @test */
    function short_description_should_be_less_than_250_characters()
    {
        $formValues = $this->mergeWithMakeCarousel([
                'short_description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit pariatur quos deserunt adipisci cumque, iure omnis modi, blanditiis vero, eveniet explicabo quae. Inventore adipisci repudiandae cumque laudantium ratione qui odio. Lorem ipsum dolor sit amet, consectetur adipisicing elit.'
            ]);

        $this->post(route('admin.carousels.store'), $formValues)
            ->assertSessionHasErrors('short_description');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('short_description'),
            'The short description may not be greater than 250 characters.'
        );
    }

    /** @test */
    function product_id_field_is_required()
    {
        $formValues = array_merge($this->mergeWithMakeCarousel(), [
            'product_id' => ''
        ]);

        $this->post(route('admin.carousels.store'), $formValues)
            ->assertSessionHasErrors('product_id');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('product_id'),
            'Select products you wish to add in this carousel.'
        );
    }

    /** @test */
    function product_id_field_should_be_an_array()
    {
        $formValues = array_merge($this->mergeWithMakeCarousel(), [
            'product_id' => 'skduzf,vjn'
        ]);

        $this->post(route('admin.carousels.store'), $formValues)
            ->assertSessionHasErrors('product_id');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('product_id'),
            'Invalid products selected.'
        );
    }

    protected function mergeWithMakeCarousel($attributes = [])
    {
        $carousel = factory(Carousel::class)->make();

        return array_merge($carousel->toArray(), $attributes);
    }
}
