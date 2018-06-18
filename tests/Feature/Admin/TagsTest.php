<?php

namespace Tests\Feature\Admin;

use IndianIra\Tag;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TagsTest extends TestCase
{
    use RefreshDatabase;

    public $admin;

    public function setUp()
    {
        parent::setUp();

        $this->admin = $this->generateSuperAdministrator();
    }

    /** @test */
    function only_logged_in_super_administrator_can_access_the_tags_section()
    {
        $this->withoutExceptionHandling()
             ->get(route('admin.tags'))
             ->assertStatus(302)
             ->assertRedirect(route('admin.login'));
    }

    /** @test */
    function super_administrator_can_view_the_tags_section()
    {
        $this->signInSuperAdministrator($this->admin);

        $this->withoutExceptionHandling()
             ->get(route('admin.tags'))
             ->assertViewIs('admin.tags.index')
             ->assertSee('List of All Tags');
    }

    /** @test */
    function no_tags_data_exists()
    {
        $this->assertCount(0, Tag::all());
    }

    /** @test */
    function super_administrator_can_add_new_tags()
    {
        $this->signInSuperAdministrator($this->admin);

        $tagsData = [
            'name'              => 'My-Awesome-Tag',
            'short_description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.',
            'meta_title'        => 'Magnam et necessitatibus ut placeat dolor sunt eum tempore.',
            'meta_description'  => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolorum voluptate.',
            'meta_keywords'     => 'Dicta quaerat, ducimus sapiente, placeat, doloribus, nulla aliquam, eaque ipsum.'
        ];

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.tags.store'), $tagsData);

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Tags added successfully!');
        $this->assertEquals($result->location, route('admin.tags'));

        $this->assertNotNull(Tag::first());
    }

    /** @test */
    function super_administrator_can_update_an_existing_tag()
    {
        $this->signInSuperAdministrator($this->admin);

        $tag = factory(Tag::class)->create();
        $formValues = array_merge(
                        $tag->toArray(),
                        ['name' => 'Apparel']
                    );

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.tags.update', $tag->id), $formValues);

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Tag updated successfully!');
        $this->assertEquals($result->location, route('admin.tags'));

        $this->assertEquals($tag->fresh()->name, 'Apparel');
        $this->assertNotEquals(Tag::first()->name, $tag->name);
    }

    /** @test */
    function super_administrator_cannot_update_a_tag_that_does_not_exist()
    {
        $this->signInSuperAdministrator($this->admin);

        $tags = factory(Tag::class, 3)->create();
        $formValues = factory(Tag::class)->make(['name' => 'diffTags'])->toArray();

        $response = $this->withoutExceptionHandling()
                         ->post(route('admin.tags.update', 50), $formValues);

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Tag with that id cannot be found.');

        $this->assertCount(3, Tag::all());
    }

    /** @test */
    function super_administrator_can_temporarily_delete_a_tag()
    {
        $this->signInSuperAdministrator($this->admin);

        $tags = factory(Tag::class, 3)->create();

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.tags.delete', 1));

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Tag deleted temporarily!');
        $this->assertEquals($result->location, route('admin.tags'));

        $this->assertCount(3, Tag::withTrashed()->get());
        $this->assertCount(2, Tag::all());
        $this->assertCount(1, Tag::onlyTrashed()->get());
    }

    /** @test */
    function super_administrator_cannot_temporarily_delete_a_tag_that_does_not_exists()
    {
        $this->signInSuperAdministrator($this->admin);

        $tags = factory(Tag::class, 3)->create();

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.tags.delete', 10));

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Tag with that id cannot be found.');

        $this->assertCount(3, Tag::withTrashed()->get());
        $this->assertCount(3, Tag::all());
        $this->assertCount(0, Tag::onlyTrashed()->get());
    }

    /** @test */
    function super_administrator_can_restore_a_temporarily_deleted_tag()
    {
        $this->signInSuperAdministrator($this->admin);

        factory(Tag::class, 3)->create();
        $tag = factory(Tag::class)->create(['deleted_at' => \Carbon\Carbon::now()]);

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.tags.restore', $tag->id));

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Tag restored successfully!');
        $this->assertEquals($result->location, route('admin.tags'));

        $this->assertCount(4, Tag::withTrashed()->get());
        $this->assertCount(4, Tag::all());
        $this->assertCount(0, Tag::onlyTrashed()->get());
    }

    /** @test */
    function super_administrator_cannot_restore_a_temporarily_deleted_tag_that_does_not_exists()
    {
        $this->signInSuperAdministrator($this->admin);

        $tags = factory(Tag::class, 3)->create();
        $tag = factory(Tag::class)->create(['deleted_at' => \Carbon\Carbon::now()]);

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.tags.restore', 10));

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Tag with that id cannot be found.');

        $this->assertCount(4, Tag::withTrashed()->get());
        $this->assertCount(3, Tag::all());
        $this->assertCount(1, Tag::onlyTrashed()->get());
    }

    /** @test */
    function super_administrator_can_permanently_delete_a_temporarily_deleted_tag()
    {
        $this->signInSuperAdministrator($this->admin);

        factory(Tag::class, 3)->create();
        $tag = factory(Tag::class)->create(['deleted_at' => \Carbon\Carbon::now()]);

        $this->assertCount(4, Tag::withTrashed()->get());

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.tags.destroy', $tag->id));

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Tag destroyed successfully!');
        $this->assertEquals($result->location, route('admin.tags'));

        $this->assertCount(3, Tag::withTrashed()->get());
        $this->assertCount(3, Tag::all());
        $this->assertCount(0, Tag::onlyTrashed()->get());
    }

    /** @test */
    function super_administrator_cannot_permanently_delete_a_temporarily_deleted_tag_that_does_not_exists()
    {
        $this->signInSuperAdministrator($this->admin);

        $tags = factory(Tag::class, 3)->create();
        $tag = factory(Tag::class)->create(['deleted_at' => \Carbon\Carbon::now()]);

        $response = $this->withoutExceptionHandling()
                         ->get(route('admin.tags.destroy', 10));

        $result = json_decode($response->getContent());

        $this->assertNotNull($result);
        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Tag with that id cannot be found.');

        $this->assertCount(4, Tag::withTrashed()->get());
        $this->assertCount(3, Tag::all());
        $this->assertCount(1, Tag::onlyTrashed()->get());
    }

    /** @test */
    function tag_name_field_is_required()
    {
        $this->signInSuperAdministrator($this->admin);

        $tag = factory(Tag::class)->make();
        $formValues = array_merge($tag->toArray(), ['name' => '']);

        $this->post(route('admin.tags.store'), $formValues)
            ->assertSessionHasErrors('name');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('name'),
            'The tag name field is required.'
        );
    }

    /** @test */
    function tag_name_field_should_be_less_than_250_characters()
    {
        $this->signInSuperAdministrator($this->admin);

        $tag = factory(Tag::class)->make();
        $formValues = array_merge($tag->toArray(), ['name' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam tempora sunt quae rem aspernatur labore corporis soluta earum, aliquam, culpa quis, repellendus enim nemo ducimus vitae iure molestiae. Temporibus, veritatis. Lorem ipsum dolor sit amet, consectetur adipisicing elit.']);

        $this->post(route('admin.tags.store'), $formValues)
            ->assertSessionHasErrors('name');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('name'),
            'The tag name may not be greater than 250 characters.'
        );
    }

    /** @test */
    function tag_name_field_should_be_unique()
    {
        $this->signInSuperAdministrator($this->admin);

        $tag1 = factory(Tag::class)->create();
        $tag2 = factory(Tag::class)->make(['name' => 'Bestest-Tag']);
        $formValues = array_merge($tag2->toArray(), ['name' => $tag1->name]);

        $this->post(route('admin.tags.store'), $formValues)
            ->assertSessionHasErrors('name');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('name'),
            'The tag name has already been taken.'
        );
    }

    /** @test */
    function short_description_should_be_less_than_250_characters()
    {
        $this->signInSuperAdministrator($this->admin);

        $tag = factory(Tag::class)->make();
        $formValues = array_merge(
            $tag->toArray(), [
                'short_description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit pariatur quos deserunt adipisci cumque, iure omnis modi, blanditiis vero, eveniet explicabo quae. Inventore adipisci repudiandae cumque laudantium ratione qui odio. Lorem ipsum dolor sit amet, consectetur adipisicing elit.'
            ]
        );

        $this->post(route('admin.tags.store'), $formValues)
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
        $this->signInSuperAdministrator($this->admin);

        $tag = factory(Tag::class)->make();
        $formValues = array_merge($tag->toArray(), ['meta_title' => '']);

        $this->post(route('admin.tags.store'), $formValues)
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
        $this->signInSuperAdministrator($this->admin);

        $tag = factory(Tag::class)->make();
        $formValues = array_merge(
            $tag->toArray(), [
                'meta_title' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit pariatur quos deserunt adipisci cumque.'
            ]
        );

        $this->post(route('admin.tags.store'), $formValues)
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
        $this->signInSuperAdministrator($this->admin);

        $tag = factory(Tag::class)->make();
        $formValues = array_merge($tag->toArray(), ['meta_description' => '']);

        $this->post(route('admin.tags.store'), $formValues)
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
        $this->signInSuperAdministrator($this->admin);

        $tag = factory(Tag::class)->make();
        $formValues = array_merge(
            $tag->toArray(), [
                'meta_description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad reiciendis ab illo veritatis quaerat omnis accusamus enim excepturi voluptatibus, sapiente rem labore quasi magnam eos aliquid numquam eum ipsa, atque.'
            ]
        );

        $this->post(route('admin.tags.store'), $formValues)
            ->assertSessionHasErrors('meta_description');

        $errors = session('errors');
        $this->assertEquals(
            $errors->first('meta_description'),
            'The meta description may not be greater than 160 characters.'
        );
    }
}
