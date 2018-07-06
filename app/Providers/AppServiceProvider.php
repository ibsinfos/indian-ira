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
            \Illuminate\Support\Facades\View::share(
                'parentCategoriesInMenu',
                \IndianIra\Category::whereParentId(0)->where('display_in_menu', 1)->get()
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
