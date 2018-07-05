<?php

namespace Tests\Unit;

use Tests\TestCase;
use IndianIra\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->signInSuperAdministrator();
    }

    /** @test */
    function category_is_enabled()
    {
        $category = factory(Category::class)->create(['display' => 'Enabled']);

        $this->assertTrue($category->display == 'Enabled');
    }

    /** @test */
    function category_is_disabled()
    {
        $category = factory(Category::class)->create(['display' => 'Disabled']);

        $this->assertTrue($category->display == 'Disabled');
    }

    /** @test */
    function category_is_super_parent_category()
    {
        $parent = factory(Category::class)->create();
        $this->assertTrue($parent->isSuperParent());

        $category = factory(Category::class)->create(['parent_id' => $parent->id]);
        $this->assertFalse($category->isSuperParent());
    }

    /** @test */
    function category_is_parent_category()
    {
        $parent = factory(Category::class)->create();
        $this->assertFalse($parent->isParent());

        $category = factory(Category::class)->create(['parent_id' => $parent->id]);
        $this->assertTrue($category->isParent());
    }

    /** @test */
    function only_parent_category_can_be_seen_in_navigation_menu()
    {
        $parent = factory(Category::class)->create(['display_in_menu' => 1]);
        $this->assertTrue($parent->seenInNavigationMenu());

        $category = factory(Category::class)->create(['parent_id' => $parent->id]);
        $this->assertFalse($category->seenInNavigationMenu());
    }
}
