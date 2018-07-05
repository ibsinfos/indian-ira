<?php

use IndianIra\Product;
use IndianIra\Carousel;
use Illuminate\Database\Seeder;
use IndianIra\ProductPriceAndOption;

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

        $allProducts = Product::whereDisplay('Enabled')->get();

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
    }
}
