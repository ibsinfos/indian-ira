<?php

namespace Tests\Unit;

use IndianIra\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->generateSuperAdministrator();
    }

    /** @test */
    public function user_is_a_verified_user()
    {
        $user = factory(User::class)->create();

        $this->assertTrue($user->isVerified());
    }

    /** @test */
    function user_has_a_billing_address()
    {
        $user = factory(User::class)->create();

        $this->assertFalse($user->fresh()->hasBillingAddress());

        $user->billingAddress()->create();

        $this->assertTrue($user->fresh()->hasBillingAddress());
    }

    /** @test */
    function user_wishlist_is_empty()
    {
        $user = factory(User::class)->create();

        $this->assertCount(0, $user->wishlist);
    }

    /** @test */
    function user_can_add_product_in_wishlist()
    {
        $user = $this->signInUser();
        $product = factory(\IndianIra\Product::class)->create(['display' => 'Enabled']);
        $product->categories()->attach([
            factory(\IndianIra\Category::class)->create(['display' => 'Enabled'])->id
        ]);

        $this->assertTrue($user->canAddProductInWishlist($product));
    }

    /** @test */
    function user_cannot_add_product_in_wishlist_if_it_already_exists()
    {
        $user = $this->signInUser();
        $product = factory(\IndianIra\Product::class)->create(['display' => 'Enabled']);
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

        $this->assertFalse($user->canAddProductInWishlist($product));
    }
}
