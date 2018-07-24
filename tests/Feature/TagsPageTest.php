<?php

namespace Tests\Feature;

use IndianIra\Tag;
use Tests\TestCase;
use IndianIra\Product;
use IndianIra\Category;
use IndianIra\ProductPriceAndOption;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TagsPageTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->generateSuperAdministrator();
    }

    /** @test */
    function user_can_view_all_tags_page()
    {
        $tag = factory(Tag::class)->create();

        $this->withoutExceptionHandling()
             ->get(route('tags.index'))
             ->assertStatus(200)
             ->assertViewIs('tags.index')
             ->assertSee('List of All Tags');
    }

    /** @test */
    function user_sees_the_products_in_the_selected_tag_page()
    {
        $tag = factory(Tag::class)->create();
        $category = factory(Category::class)->create(['display' => 'Enabled']);

        $products = factory(Product::class, 20)->create(['display' => 'Enabled']);
        foreach ($products as $product) {
            factory(ProductPriceAndOption::class)->create(['display' => 'Enabled', 'product_id' => $product->id]);
        }

        $category->products()->attach($products->pluck('id')->toArray());
        $tag->products()->attach($products->pluck('id')->toArray());

        $this->withoutExceptionHandling()
             ->get($tag->pageUrl())
             ->assertViewIs('tags.show')
             ->assertSee('Products tagged as: ', $tag->name);

        $this->assertCount(20, $tag->products);
    }

    /** @test */
    function user_sees_the_product_from_the_tag_page()
    {
        $tag = factory(Tag::class)->create();
        $category = factory(Category::class)->create(['display' => 'Enabled']);
        $products = factory(Product::class, 10)->create(['display' => 'Enabled']);
        foreach ($products as $product) {
            factory(ProductPriceAndOption::class)->create(['display' => 'Enabled', 'product_id' => $product->id]);
        }
        $category->products()->attach($products->pluck('id')->toArray());
        $tag->products()->attach($products->pluck('id')->toArray());

        $this->withoutExceptionHandling()
             ->get($tag->productPageUrl($product))
             ->assertViewIs('tags.product')
             ->assertSee($product->name);
    }
}
