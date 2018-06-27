<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([
    'prefix' => 'generate-super-administrator',
    'namespace' => 'Admin'
], function () {
    Route::get('/', 'GenerateController@index')->name('admin.generate');
    Route::post('/', 'GenerateController@store')->name('admin.generate.store');
});

Route::group(['middleware' => 'super_admin_exists'], function () {

    Route::group([
        'prefix'     => 'admin',
        'namespace'  => 'Admin'
    ], function () {
        Route::get('/', function () {
            return redirect(route('admin.login'));
        });

        Route::get('/login', 'LoginController@index')->name('admin.login');
        Route::post('/login', 'LoginController@postLogin')->name('admin.postLogin');

        Route::group(['middleware' => 'super_admin_logged_in'], function () {
            Route::get('/logout', 'DashboardController@logout')->name('admin.logout');

            Route::get('/dashboard', 'DashboardController@index')->name('admin.dashboard');

            Route::group([
                'prefix'    => 'global-settings',
                'namespace' => 'GlobalSettings',
            ], function () {
                Route::get('/bank-details', 'BankDetailsController@index')->name('admin.globalSettings.bank');
                Route::post('/bank-details', 'BankDetailsController@update')->name('admin.globalSettings.bank.update');

                Route::get('/payment-options', 'PaymentOptionsController@index')
                    ->name('admin.globalSettings.paymentOptions');
                Route::post('/payment-options', 'PaymentOptionsController@update')
                    ->name('admin.globalSettings.paymentOptions.update');

                Route::get('/cod-charges', 'CodChargesController@index')->name('admin.globalSettings.codCharges');
                Route::post('/cod-charges', 'CodChargesController@update')
                    ->name('admin.globalSettings.codCharges.update');
            });

            Route::group(['prefix' => 'shipping-rates'], function () {
                Route::get('/', 'ShippingRatesController@index')->name('admin.shippingRates');
                Route::post('/', 'ShippingRatesController@store')->name('admin.shippingRates.store');
                Route::post('/{id}/update', 'ShippingRatesController@update')->name('admin.shippingRates.update');
                Route::get('/{id}/delete', 'ShippingRatesController@delete')->name('admin.shippingRates.delete');
                Route::get('/{id}/restore', 'ShippingRatesController@restore')->name('admin.shippingRates.restore');
                Route::get('/{id}/destroy', 'ShippingRatesController@destroy')->name('admin.shippingRates.destroy');
            });

            Route::group(['prefix' => 'tags'], function () {
                Route::get('/', 'TagsController@index')->name('admin.tags');
                Route::post('/', 'TagsController@store')->name('admin.tags.store');
                Route::post('/{id}/update', 'TagsController@update')->name('admin.tags.update');
                Route::get('/{id}/delete', 'TagsController@delete')->name('admin.tags.delete');
                Route::get('/{id}/restore', 'TagsController@restore')->name('admin.tags.restore');
                Route::get('/{id}/destroy', 'TagsController@destroy')->name('admin.tags.destroy');
            });

            Route::group(['prefix' => 'categories', 'namespace' => 'Categories'], function () {
                Route::get('/', 'CategoriesController@index')->name('admin.categories');
                Route::post('/', 'CategoriesController@store')->name('admin.categories.store');

                Route::get('/export', 'ExportController@export')->name('admin.categories.export');
                Route::post('/import', 'ImportController@import')->name('admin.categories.import');

                Route::post('/{id}/update', 'CategoriesController@update')->name('admin.categories.update');
                Route::get('/{id}/delete', 'CategoriesController@delete')->name('admin.categories.delete');
                Route::get('/{id}/restore', 'CategoriesController@restore')->name('admin.categories.restore');
                Route::get('/{id}/destroy', 'CategoriesController@destroy')->name('admin.categories.destroy');
            });

            Route::group(['prefix' => 'products', 'namespace' => 'Products'], function () {
                Route::get('/', 'ProductsController@index')->name('admin.products');
                Route::post('/', 'ProductsController@store')->name('admin.products.store');

                Route::get('/{id}/edit', 'ProductsController@edit')->name('admin.products.edit');

                Route::post('/{id}/updateGeneral', 'ProductsController@updateGeneral')
                    ->name('admin.products.updateGeneral');
                Route::post('/{id}/updateDetailedInformation', 'ProductsController@updateDetailedInformation')
                    ->name('admin.products.updateDetailedInformation');
                Route::post('/{id}/updateMetaInformation', 'ProductsController@updateMetaInformation')
                    ->name('admin.products.updateMetaInformation');
                Route::post('/{id}/updateImage', 'ProductsController@updateImage')
                    ->name('admin.products.updateImage');

                Route::group(['prefix' => '{id}/price-and-options'], function () {
                    Route::get('/', 'PriceAndOptionsController@index')->name('admin.products.priceAndOptions');
                    Route::post('/', 'PriceAndOptionsController@store')->name('admin.products.priceAndOptions.store');
                    Route::post('/{optionId}/update', 'PriceAndOptionsController@update')
                        ->name('admin.products.priceAndOptions.update');
                    Route::get('/{optionId}/destroy', 'PriceAndOptionsController@destroy')
                        ->name('admin.products.priceAndOptions.destroy');
                });

                Route::get('/{id}/delete', 'ProductsController@delete')->name('admin.products.delete');
                Route::get('/{id}/restore', 'ProductsController@restore')->name('admin.products.restore');
                Route::get('/{id}/destroy', 'ProductsController@destroy')->name('admin.products.destroy');
            });
        });
    });

    Route::get('/', function () {
        return view('welcome');
    })->name('homePage');

    Route::group([
        'prefix' => 'users',
        'namespace' => 'Users'
    ], function () {
        Route::group(['prefix' => 'register'], function () {
            Route::get('/', 'RegisterController@index')->name('users.register');
            Route::post('/', 'RegisterController@store')->name('users.register.store');
        });

        Route::group(['prefix' => 'confirm-registration'], function () {
            Route::get('/', 'ConfirmRegistrationController@show')->name('users.showConfirmRegistrationPage');
            Route::get('/resend', 'ConfirmRegistrationController@resend')->name('users.resendConfimationMail');
            Route::get('/{token}', 'ConfirmRegistrationController@update')->name('users.confirmRegistration');
        });

        Route::group(['prefix' => 'login'], function () {
            Route::get('/', 'LoginController@index')->name('users.login');
            Route::post('/', 'LoginController@postLogin')->name('users.postLogin');
        });

        Route::group(['prefix' => 'password'], function () {
            Route::get('/forgot', 'ForgotPasswordController@index')->name('users.forgotPassword');
            Route::post('/forgot', 'ForgotPasswordController@store')->name('users.forgotPassword.store');
            Route::get('/reset/{token}', 'ResetPasswordController@edit')->name('users.resetPassword');
            Route::post('/reset/{token}', 'ResetPasswordController@update')->name('users.resetPassword.update');
        });

        Route::group(['middleware' => 'user_logged_in'], function () {
            Route::get('/dashboard', 'DashboardController@index')->name('users.dashboard');
            Route::get('/logout', 'DashboardController@logout')->name('users.logout');
        });
    });
});
