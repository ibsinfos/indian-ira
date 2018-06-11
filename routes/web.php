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
        });
    });

    Route::get('/', function () {
        return view('welcome');
    })->name('homePage');
});
