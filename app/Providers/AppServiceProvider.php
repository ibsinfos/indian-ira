<?php

namespace IndianIra\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (app()->environment() != 'testing') {
            $categories = \IndianIra\Category::onlySuperParent()->whereDisplay('Enabled')->get();

            \Illuminate\Support\Facades\View::share(
                'parentCategoriesInMenu',
                $categories->where('display_in_menu', 1)
            );

            \Illuminate\Support\Facades\View::share(
                'allCategoriesInMenu',
                $categories
            );
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
