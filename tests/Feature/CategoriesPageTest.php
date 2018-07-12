<?php

namespace Tests\Feature;

use Tests\TestCase;
use IndianIra\Product;
use IndianIra\Category;
use IndianIra\ProductPriceAndOption;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoriesPageTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->generateSuperAdministrator();
    }

    /** @test */
    function user_can_view_the_categories_page()
    {
        $category = factory(Category::class)->create();

        $this->withoutExceptionHandling()
             ->get($category->pageUrl())
             ->assertViewIs('categories.show')
             ->assertSee('Products in ', $category->display_text);
    }

    /** @test */
    function user_cannot_view_the_categories_page_if_it_does_not_exist()
    {
        $this->withExceptionHandling()
             ->get('/categories/20/category-name')
             ->assertStatus(404);
    }

    /** @test */
    function user_sees_the_products_in_the_categories_page()
    {
        $category = factory(Category::class)->create();

        $products = factory(Product::class, 20)->create(['Display' => 'Enabled']);
        foreach ($products as $product) {
            factory(ProductPriceAndOption::class)->create(['Display' => 'Enabled', 'product_id' => $product->id]);
        }

        $category->products()->attach($products->pluck('id')->toArray());

        $this->withoutExceptionHandling()
             ->get($category->pageUrl())
             ->assertSee('Products in ', $category->display_text);

        $this->assertCount(20, $category->products);
    }
}
