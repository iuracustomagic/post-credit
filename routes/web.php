<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/login', '\App\Http\Controllers\Auth\AuthController@login')->name('login');
Route::get('/register', '\App\Http\Controllers\Auth\AuthController@register')->name('register');
Route::post('/login','\App\Http\Controllers\Auth\AuthController@postLogin')->name('postLogin');
Route::post('/register','\App\Http\Controllers\Auth\AuthController@managerSave')->name('manager.new');


Route::middleware(['basicAuth'])->group(function () {
    Route::post('/webhook-order', '\App\Http\Controllers\Webhook\WebHookController@webhookHandler');
});

Route::group(['middleware' => ['auth']],function () {
    Route::get('/logout', '\App\Http\Controllers\Auth\AuthController@logout')->name('logout');
    Route::get('/', '\App\Http\Controllers\Statistic\StatisticController@main')->name('statistic');
    Route::get('/manager-profile', '\App\Http\Controllers\Profile\ManagerProfileController@index')->name('profile.manager');
    Route::get('/leader-profile', '\App\Http\Controllers\Profile\LeaderProfileController@index')->name('profile.leader');
    Route::group(['prefix' => 'manager'], function() {
        Route::get('/', '\App\Http\Controllers\User\ManagerController@index')->name('manager.index');
        Route::get('/create', '\App\Http\Controllers\User\ManagerController@create')->name('manager.create');

        Route::get('/bonus-setting', '\App\Http\Controllers\User\ManagerController@bonusSetting')->name('bonus.setting');
        Route::patch('/bonus-update', '\App\Http\Controllers\User\ManagerController@bonusSave')->name('bonus.update');

        Route::post('/', '\App\Http\Controllers\User\ManagerController@store')->name('manager.store');
        Route::get('/{user}/edit', '\App\Http\Controllers\User\ManagerController@edit')->name('manager.edit');
        Route::get('/{user}', '\App\Http\Controllers\User\ManagerController@show')->name('manager.show');
        Route::patch('/{user}', '\App\Http\Controllers\User\ManagerController@update')->name('manager.update');
        Route::delete('/{user}', '\App\Http\Controllers\User\ManagerController@delete')->name('manager.delete');


    });

    Route::group(['prefix' => 'salesman'], function() {
        Route::get('/', '\App\Http\Controllers\User\SalesmanController@index')->name('salesman.index');
        Route::get('/create', '\App\Http\Controllers\User\SalesmanController@create')->name('salesman.create');
        Route::post('/', '\App\Http\Controllers\User\SalesmanController@store')->name('salesman.store');
        Route::get('/{user}/edit', '\App\Http\Controllers\User\SalesmanController@edit')->name('salesman.edit');
        Route::get('/{user}', '\App\Http\Controllers\User\SalesmanController@show')->name('salesman.show');
        Route::patch('/{user}', '\App\Http\Controllers\User\SalesmanController@update')->name('salesman.update');
        Route::delete('/{user}', '\App\Http\Controllers\User\SalesmanController@delete')->name('salesman.delete');
    });

    Route::group(['prefix' => 'company'], function() {
        Route::get('/', '\App\Http\Controllers\Company\CompanyController@index')->name('company.index');
        Route::get('/new-company', '\App\Http\Controllers\Company\CompanyController@newList')->name('company.new');
        Route::get('/create', '\App\Http\Controllers\Company\CompanyController@create')->name('company.create');
        Route::post('/', '\App\Http\Controllers\Company\CompanyController@store')->name('company.store');
        Route::get('/{company}/edit', '\App\Http\Controllers\Company\CompanyController@edit')->name('company.edit');
        Route::get('/{company}', '\App\Http\Controllers\Company\CompanyController@show')->name('company.show');
        Route::patch('/{company}', '\App\Http\Controllers\Company\CompanyController@update')->name('company.update');
        Route::delete('/{company}', '\App\Http\Controllers\Company\CompanyController@delete')->name('company.delete');
        Route::get('/divisions/{company}', '\App\Http\Controllers\Company\CompanyController@showDivisions')->name('companyDivisions.show');
        Route::get('/addDivision/{company}', '\App\Http\Controllers\Company\CompanyController@addDivision')->name('companyDivisions.add');
        Route::get('/showSalesmen/{company}', '\App\Http\Controllers\Company\CompanyController@showSalesmen')->name('showSalesmen');
    });

    Route::group(['prefix' => 'division'], function() {
        Route::get('/', '\App\Http\Controllers\Division\DivisionController@index')->name('division.index');
        Route::get('/create', '\App\Http\Controllers\Division\DivisionController@create')->name('division.create');
        Route::post('/', '\App\Http\Controllers\Division\DivisionController@store')->name('division.store');
        Route::get('/{division}/edit', '\App\Http\Controllers\Division\DivisionController@edit')->name('division.edit');
        Route::get('/{division}', '\App\Http\Controllers\Division\DivisionController@show')->name('division.show');
        Route::patch('/{division}', '\App\Http\Controllers\Division\DivisionController@update')->name('division.update');
        Route::delete('/image/{divisionImage}', '\App\Http\Controllers\Division\DivisionController@imageDelete')->name('division.imageDelete');
        Route::delete('/{division}', '\App\Http\Controllers\Division\DivisionController@delete')->name('division.delete');

    });

    Route::get('/order', '\App\Http\Controllers\Order\OrderController@index')->name('order.index');
    Route::post('/order', '\App\Http\Controllers\Order\OrderController@store')->name('order.store');
    Route::get('/check-order/{order}', '\App\Http\Controllers\Order\OrderController@checkOrders')->name('order.check');
});

