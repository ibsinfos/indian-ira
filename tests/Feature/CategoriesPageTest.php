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

    /** @test */
    function product_can_be_viewed_via_categories_page()
    {
        $category = factory(Category::class)->create(['display' => 'Enabled']);

        for ($i = 0; $i <= 2; $i++) {
            $product = factory(Product::class)->create([
                'display' => 'Enabled',
                'code' => 'PRD-'. ++$i .'-'. mt_rand(1000, 9999),
            ]);

            factory(ProductPriceAndOption::class)->create([
                'display'    => 'Enabled',
                'product_id' => $product->id,
            ]);
        }

        $products = Product::onlyEnabled()->get();

        $category->products()->attach($products->pluck('id')->toArray());

        $this->assertCount(2, $category->products);

        $this->withoutExceptionHandling()
            ->get($category->pageUrl() . '/products/' . $products->first()->code . '/' . str_slug($products->first()->name))
            ->assertViewIs('products.show')
            ->assertSee(title_case($products->first()->name));
    }

    /** @test */
    function user_cannot_view_the_products_page_if_it_does_not_exist()
    {
        $this->withExceptionHandling()
             ->get('/categories/20/category-name/products/USJYDFG/product-name')
             ->assertStatus(404);
    }
}
