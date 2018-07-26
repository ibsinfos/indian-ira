<?php

namespace Tests\Feature\Users;

use Tests\TestCase;
use IndianIra\UserWishlist;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WishListTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $product;

    public function setUp()
    {
        parent::setUp();

        $this->generateSuperAdministrator();

        $this->user = $this->signInUser();

        $this->product = factory(\IndianIra\Product::class)->create(['display' => 'Enabled']);
        $this->product->categories()->sync(factory(\IndianIra\Category::class)->create(['display' => 'Enabled'])->id);
    }

    /** @test */
    function user_needs_to_login_in_order_to_add_product_to_their_wishlist()
    {
        auth()->logout();

        $this->withoutExceptionHandling()
             ->get(route('users.wishlist.add', $this->product->code))
             ->assertStatus(302)
             ->assertRedirect(route('users.login'));
    }

    /** @test */
    function user_wishlist_is_empty()
    {
        $this->assertCount(0, UserWishlist::whereUserId($this->user->id)->get());

        $this->assertEmpty($this->user->wishlist);
    }

    /** @test */
    function user_can_view_their_wishlist()
    {
        $this->withoutExceptionHandling()
             ->get(route('users.wishlist'))
             ->assertViewIs('users.wishlist.index')
             ->assertSee('List of Products in Wishlist');
    }

    /** @test */
    function user_may_add_product_to_their_wishlist()
    {
        $response = $this->withoutExceptionHandling()
                         ->get(route('users.wishlist.add', $this->product->code));

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Product added successfully in your wishlist ! Redirecting...');
        $this->assertEquals($result->location, route('users.wishlist'));

        $this->assertCount(1, $this->user->wishlist);
    }

    /** @test */
    function user_cannot_add_product_to_their_wishlist_if_it_already_exists()
    {
        $productInWishlist = factory(UserWishlist::class)->create(['user_id' => $this->user->id]);

        $response = $this->withoutExceptionHandling()
                         ->get(route('users.wishlist.add', $productInWishlist->product_code));

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Product already exists in your wishlist');

        $this->assertCount(1, $this->user->wishlist);
    }

    /** @test */
    function user_can_remove_product_from_their_wishlist_if_it_already_exists()
    {
        $productInWishlist = factory(UserWishlist::class)->create(['user_id' => $this->user->id]);
        $this->assertCount(1, $this->user->wishlist);

        $response = $this->withoutExceptionHandling()
                         ->get(route('users.wishlist.remove', $productInWishlist->product_id));

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'success');
        $this->assertEquals($result->title, 'Success !');
        $this->assertEquals($result->message, 'Product removed successfully in your wishlist !');
        $this->assertEquals($result->location, route('users.wishlist'));

        $this->assertEquals(0, $this->user->wishlist()->count());
    }

    /** @test */
    function user_cannot_remove_product_from_their_wishlist_if_it_does_not_exists()
    {
        factory(UserWishlist::class)->create(['user_id' => $this->user->id]);
        $this->assertCount(1, $this->user->wishlist);

        $response = $this->withoutExceptionHandling()
                         ->get(route('users.wishlist.remove', 10));

        $result = json_decode($response->getContent());

        $this->assertEquals($result->status, 'failed');
        $this->assertEquals($result->title, 'Failed !');
        $this->assertEquals($result->message, 'Product does not exists in your wishlist');

        $this->assertEquals(1, $this->user->wishlist->count());
    }
}
