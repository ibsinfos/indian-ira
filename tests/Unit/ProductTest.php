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
}
