<?php

namespace Tests\Unit;

use Tests\TestCase;
use IndianIra\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->generateSuperAdministrator();
    }

    /** @test */
    function product_is_enabled()
    {
        factory(Product::class)->create(['display' => 'Enabled']);

        $this->assertTrue(Product::first()->display == 'Enabled');
    }

    /** @test */
    function a_product_has_related_products()
    {
        $product1 = factory(Product::class)->create(['display' => 'Enabled', 'name' => 'Product 1']);
        $product2 = factory(Product::class)->create(['display' => 'Enabled', 'name' => 'Product 2']);
        $product3 = factory(Product::class)->create(['display' => 'Enabled', 'name' => 'Product 3']);
        factory(Product::class, 10)->create(['display' => 'Enabled']);

        $allProducts = Product::onlyEnabled()->get();

        $product1->interRelated()->sync(
            $allProducts->where('id', '<>', $product1->id)->shuffle()->take(5)->pluck('id')->toArray()
        );

        $product2->interRelated()->sync(
            $allProducts->where('id', '<>', $product2->id)->shuffle()->take(3)->pluck('id')->toArray()
        );

        $this->assertCount(5, $product1->interRelated);
        $this->assertCount(3, $product2->interRelated);
    }

    /** @test */
    function product_does_not_exists_in_the_user_wishlist()
    {
        $user = $this->signInUser();
        $product = factory(Product::class)->create(['display' => 'Enabled']);

        $this->assertFalse($product->existsInWishlist($user));
    }

    /** @test */
    function product_exists_in_the_wishlist_of_the_authenticated_user()
    {
        $user = $this->signInUser();
        $product = factory(Product::class)->create(['display' => 'Enabled']);
        $product->categories()->attach([
            factory(\IndianIra\Category::class)->create(['display' => 'Enabled'])->id
        ]);

        factory(\IndianIra\UserWishlist::class)->create([
            'user_id'          => $user->id,
            'product_id'       => $product->id,
            'product_code'     => $product->code,
            'product_name'     => $product->name,
            'product_image'    => $product->cartImage(),
            'product_page_url' => $product->canonicalPageUrl(),
        ]);

        $this->assertTrue($product->existsInWishlist($user));
    }
}
