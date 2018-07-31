<?php

use IndianIra\Product;
use IndianIra\Carousel;
use IndianIra\Category;
use IndianIra\ShippingRate;
use Illuminate\Database\Seeder;
use IndianIra\ProductPriceAndOption;
use IndianIra\GlobalSettingCodCharge;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(\IndianIra\User::class)->create([
            'first_name' => 'Super',
            'last_name'  => 'Administrator',
            'username'   => 'admin',
            'email'      => 'admin@example.com',
            'password'   => bcrypt('Password'),
        ]);

        factory(ProductPriceAndOption::class, 20)->create(['image' => null]);
        factory(Carousel::class, 2)->create(['display' => 'Enabled']);
        factory(Category::class, 2)->create(['display' => 'Enabled']);

        $allProducts = Product::all();

        $allProducts->take(10)->shuffle()->each(function ($product) {
            $product->categories()->attach([Category::first()->id]);
        });
        $allProducts->whereNotIn('id', Category::first()->products->pluck('id')->toArray())
                    ->take(10)
                    ->shuffle()
                    ->each(function ($product) {
                        $product->categories()->attach([Category::all()->last()->id]);
                    });

        $lastCarousel = Carousel::all()->last();
        $lastCarousel->products()->attach($allProducts->take(6)->pluck('id')->shuffle()->toArray());

        $secLastCarousel = Carousel::whereId($lastCarousel->id - 1)->first();
        $secLastCarousel->products()->attach($allProducts->take(8)->pluck('id')->shuffle()->toArray());

        factory(Carousel::class)->create(['display' => 'Enabled']);
        $allProducts = Product::whereDisplay('Enabled')
                                ->whereNotIn('id', $secLastCarousel->products->pluck('id')->toArray())
                                ->get();
        $carousel = Carousel::all()->last();
        $carousel->products()->attach($allProducts->pluck('id')->shuffle()->toArray());

        $ratesAndWeight = collect();
        $ratesAndWeight->push(['from' => 1, 'to' => 1000, 'amount' => 50]);
        $ratesAndWeight->push(['from' => 1000, 'to' => 1500, 'amount' => 100]);
        $ratesAndWeight->push(['from' => 1500, 'to' => 2000, 'amount' => 150]);

        $ratesAndWeight->each(function ($row) {
            factory(ShippingRate::class)->create([
                'weight_from'   => $row['from'],
                'weight_to'     => $row['to'],
                'amount' => $row['amount'],
            ]);
        });

        $tags = factory(\IndianIra\Tag::class, 8)->create();
        $prods = Product::all();
        foreach ($tags as $tag) {
            $tag->products()->sync($prods->shuffle()->take(3)->pluck('id')->toArray());
        }

        GlobalSettingCodCharge::create(['amount' => 50.00]);
    }
}
