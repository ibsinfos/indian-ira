<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use IndianIra\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoriesTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->signInSuperAdministrator();
    }

    /** @test */
    function only_logged_in_super_administrator_can_access_the_categories_section()
    {
        // Logout the Super Administrator
        auth()->logout();

        $this->withoutExceptionHandling()
             ->get(route('admin.categories'))
             ->assertStatus(302)
             ->assertRedirect(route('admin.login'));
    }

    /** @test */
    function super_administrator_can_view_the_categories_section()
    {
        $this->withoutExceptionHandling()
             ->get(route('admin.categories'))
             ->assertViewIs('admin.categories.index')
             ->assertSee('List of All Categories');
    }

    /** @test */
    function no_categories_data_exists()
    {
        $this->assertCount(0, Category::all());
    }

    /** @test */
    function super_administrator_can_add_new_category()
    {
        $categoriesData = [
            'parent_id'         => 0,
            'name'              => 'Apparels',
            'display'           => 'Enabled',
            'short_description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.',
            'meta_title'        => 'Magnam et necessitatibus ut placeat dolor sunt eum tempore.',
            'meta_description'  => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolorum voluptate.',
            'meta_keywords'     => 'Dicta quaerat, ducimus sapiente, placeat, doloribus, nulla aliquam, eaque ipsum.'
        ];

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.categories.store'), $categoriesData);

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Category added successfully!');
        $this->assertEquals($result->location, route('admin.categories'));

        $this->assertNotNull(Category::first());
    }

    /** @test */
    function super_administrator_can_add_upto_three_levels_of_category_only()
    {
        $superParent = factory(Category::class)->create();
        $parent = factory(Category::class)->create(['parent_id' => $superParent->id]);
        $category = factory(Category::class)->create(['parent_id' => $parent->id]);

        $categoriesData = [
            'parent_id'         => $category->id,
            'name'              => 'Apparels',
            'display'           => 'Enabled',
            'short_description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.',
            'meta_title'        => 'Magnam et necessitatibus ut placeat dolor sunt eum tempore.',
            'meta_description'  => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolorum voluptate.',
            'meta_keywords'     => 'Dicta quaerat, ducimus sapiente, placeat, doloribus, nulla aliquam, eaque ipsum.'
        ];

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.categories.store'), $categoriesData);

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Only three levels of category can be added.');
    }

    /** @test */
    function super_administrator_can_update_an_existing_category()
    {
        $category = factory(Category::class)->create();
        $formValues = array_merge(
                        $category->toArray(),
                        ['name' => 'Apparel']
                    );

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.categories.update', $category->id), $formValues);

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Category updated successfully!');
        $this->assertEquals($result->location, route('admin.categories'));

        $this->assertNotEquals(Category::first()->name, $category->name);
        $this->assertEquals($category->fresh()->name, 'Apparel');
    }

    /** @test */
    function super_administrator_cannot_update_a_category_that_does_not_exist()
    {
        $categories = factory(Category::class, 3)->create();

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.categories.update', 50), $categories->last()->toArray());

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Category with that id cannot be found.');

        $this->assertCount(3, Category::all());
    }

    /** @test */
    function super_administrator_can_temporarily_delete_a_category()
    {
        $categories = factory(Category::class, 3)->create();

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.categories.delete', 1));

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Category deleted temporarily!');
        $this->assertEquals($result->location, route('admin.categories'));

        $this->assertCount(3, Category::withTrashed()->get());
        $this->assertCount(2, Category::all());
        $this->assertCount(1, Category::onlyTrashed()->get());
    }

    /** @test */
    function super_administrator_cannot_temporarily_delete_a_category_that_does_not_exists()
    {
        $categories = factory(Category::class, 3)->create();

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.categories.delete', 10));

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Category with that id cannot be found.');

        $this->assertCount(3, Category::withTrashed()->get());
        $this->assertCount(3, Category::all());
        $this->assertCount(0, Category::onlyTrashed()->get());
    }

    /** @test */
    function super_administrator_can_restore_a_temporarily_deleted_category()
    {
        factory(Category::class, 3)->create();
        $category = factory(Category::class)->create(['deleted_at' => \Carbon\Carbon::now()]);

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.categories.restore', $category->id));

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Category restored successfully!');
        $this->assertEquals($result->location, route('admin.categories'));

        $this->assertCount(4, Category::withTrashed()->get());
        $this->assertCount(4, Category::all());
        $this->assertCount(0, Category::onlyTrashed()->get());
    }

    /** @test */
    function super_administrator_cannot_restore_a_temporarily_deleted_category_that_does_not_exists()
    {
        $categories = factory(Category::class, 3)->create();
        $category = factory(Category::class)->create(['deleted_at' => \Carbon\Carbon::now()]);

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.categories.restore', 10));

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Category with that id cannot be found.');

        $this->assertCount(4, Category::withTrashed()->get());
        $this->assertCount(3, Category::all());
        $this->assertCount(1, Category::onlyTrashed()->get());
    }

    /** @test */
    function super_administrator_can_permanently_delete_a_temporarily_deleted_category()
    {
        factory(Category::class, 3)->create();
        $category = factory(Category::class)->create(['deleted_at' => \Carbon\Carbon::now()]);

        $this->assertCount(4, Category::withTrashed()->get());

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.categories.destroy', $category->id));

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Category destroyed successfully!');
        $this->assertEquals($result->location, route('admin.categories'));

        $this->assertCount(3, Category::withTrashed()->get());
        $this->assertCount(3, Category::all());
        $this->assertCount(0, Category::onlyTrashed()->get());
    }

    /** @test */
    function super_administrator_cannot_permanently_delete_a_temporarily_deleted_category_that_does_not_exists()
    {
        $categories = factory(Category::class, 3)->create();
        $category = factory(Category::class)->create(['deleted_at' => \Carbon\Carbon::now()]);

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.categories.destroy', 10));

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Category with that id cannot be found.');

        $this->assertCount(4, Category::withTrashed()->get());
        $this->assertCount(3, Category::all());
        $this->assertCount(1, Category::onlyTrashed()->get());
    }

    /** @test */
    function super_administrator_can_download_the_categories_in_excel_format()
    {
        $categories = factory(Category::class, 10)->create();

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.categories.export'));

        $response->assertStatus(200);
    }

    /** @test */
    function super_administrator_can_upload_the_categories()
    {
        $this->assertCount(0, Category::all());

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.categories.import'), [
                            'excel_file' => \Illuminate\Http\UploadedFile::fake()->create('categories.xlsx', 1)
                         ]);

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Categories imported successfully.. Reloading in few seconds...');
        $this->assertEquals($result->location, route('admin.categories'));
    }

    /** @test */
    function uploaded_file_extension_should_either_be_xlsx_or_xls_only()
    {
        $this->post(route('admin.categories.import'), [
                    'excel_file' => \Illuminate\Http\UploadedFile::fake()->create('categories.txt', 1),
                    'extension' => 'txt'
                ])
             ->assertSessionHasErrors('extension');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('extension'),
            'Invalid file uploaded. Only xlsx and xls file can be uploaded.'
        );
    }

    /** @test */
    function upload_file_field_is_required_when_uploading_te_categories()
    {
        $this->post(route('admin.categories.import'), ['excel_file' => ''])
             ->assertSessionHasErrors('excel_file');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('excel_file'),
            'The upload excel file field is required.'
        );
    }

    /** @test */
    function category_name_field_is_required()
    {
        $category = factory(Category::class)->make();
        $formValues = array_merge($category->toArray(), ['name' => '']);

        $this->post(route('admin.categories.store'), $formValues)
            ->assertSessionHasErrors('name');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('name'),
            'The category name field is required.'
        );
    }

    /** @test */
    function category_name_field_should_be_less_than_250_characters()
    {
        $category = factory(Category::class)->make();
        $formValues = array_merge($category->toArray(), ['name' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam tempora sunt quae rem aspernatur labore corporis soluta earum, aliquam, culpa quis, repellendus enim nemo ducimus vitae iure molestiae. Temporibus, veritatis. Lorem ipsum dolor sit amet, consectetur adipisicing elit.']);

        $this->post(route('admin.categories.store'), $formValues)
            ->assertSessionHasErrors('name');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('name'),
            'The category name may not be greater than 250 characters.'
        );
    }

    /** @test */
    function parent_category_field_should_be_id_of_the_category()
    {
        $parent = factory(Category::class)->create();
        $category = factory(Category::class)->make(['parent_id' => $parent->id]);
        $formValues = array_merge($category->toArray(), ['parent_id' => 'Lorem ipsum dolor sit amet']);

        $this->post(route('admin.categories.store'), $formValues)
            ->assertSessionHasErrors('parent_id');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('parent_id'),
            'Invalid parent category selected.'
        );
    }

    /** @test */
    function parent_category_field_cannot_be_less_than_zero()
    {
        $category = factory(Category::class)->make();
        $formValues = array_merge($category->toArray(), ['parent_id' => -5]);

        $this->post(route('admin.categories.store'), $formValues)
            ->assertSessionHasErrors('parent_id');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('parent_id'),
            'Invalid parent category selected.'
        );
    }

    /** @test */
    function display_field_is_required()
    {
        $formValues = factory(Category::class)->make(['display' => '']);
        $this->post(route('admin.categories.store'), $formValues->toArray())
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
        $formValues = factory(Category::class)->make(['display' => 'Radon-World']);
        $this->post(route('admin.categories.store'), $formValues->toArray())
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
        $category = factory(Category::class)->make();
        $formValues = array_merge(
            $category->toArray(), [
                'short_description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit pariatur quos deserunt adipisci cumque, iure omnis modi, blanditiis vero, eveniet explicabo quae. Inventore adipisci repudiandae cumque laudantium ratione qui odio. Lorem ipsum dolor sit amet, consectetur adipisicing elit.'
            ]
        );

        $this->post(route('admin.categories.store'), $formValues)
            ->assertSessionHasErrors('short_description');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('short_description'),
            'The short description may not be greater than 250 characters.'
        );
    }

    /** @test */
    function meta_title_field_is_required()
    {
        $category = factory(Category::class)->make();
        $formValues = array_merge($category->toArray(), ['meta_title' => '']);

        $this->post(route('admin.categories.store'), $formValues)
            ->assertSessionHasErrors('meta_title');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('meta_title'),
            'The meta title field is required.'
        );
    }

    /** @test */
    function meta_title_should_be_less_than_60_characters()
    {
        $category = factory(Category::class)->make();
        $formValues = array_merge(
            $category->toArray(), [
                'meta_title' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit pariatur quos deserunt adipisci cumque.'
            ]
        );

        $this->post(route('admin.categories.store'), $formValues)
            ->assertSessionHasErrors('meta_title');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('meta_title'),
            'The meta title may not be greater than 60 characters.'
        );
    }

    /** @test */
    function meta_description_field_is_required()
    {
        $category = factory(Category::class)->make();
        $formValues = array_merge($category->toArray(), ['meta_description' => '']);

        $this->post(route('admin.categories.store'), $formValues)
            ->assertSessionHasErrors('meta_description');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('meta_description'),
            'The meta description field is required.'
        );
    }

    /** @test */
    function meta_description_should_be_less_than_160_characters()
    {
        $category = factory(Category::class)->make();
        $formValues = array_merge(
            $category->toArray(), [
                'meta_description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad reiciendis ab illo veritatis quaerat omnis accusamus enim excepturi voluptatibus, sapiente rem labore quasi magnam eos aliquid numquam eum ipsa, atque.'
            ]
        );

        $this->post(route('admin.categories.store'), $formValues)
            ->assertSessionHasErrors('meta_description');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('meta_description'),
            'The meta description may not be greater than 160 characters.'
        );
    }

    /** @test */
    function meta_keywords_should_be_less_than_250_characters()
    {
        $category = factory(Category::class)->make();
        $formValues = array_merge(
            $category->toArray(), [
                'meta_keywords' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit pariatur quos deserunt adipisci cumque, iure omnis modi, blanditiis vero, eveniet explicabo quae. Inventore adipisci repudiandae cumque laudantium ratione qui odio. Lorem ipsum dolor sit amet, consectetur adipisicing elit.'
            ]
        );

        $this->post(route('admin.categories.store'), $formValues)
            ->assertSessionHasErrors('meta_keywords');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('meta_keywords'),
            'The meta keywords may not be greater than 150 characters.'
        );
    }
}
