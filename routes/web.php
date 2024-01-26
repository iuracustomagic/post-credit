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

Route::post('/webhook-order/{id}', '\App\Http\Controllers\Webhook\WebHookController@webhookHandler');
Route::post('/webhook-sms', '\App\Http\Controllers\Webhook\SmsWebHookController@webhookHandler');

Route::post('/check-order-mfo', '\App\Http\Controllers\Order\OrderController@checkMfo')->name('order.checkMfo');
Route::get('/check-order-mfo', '\App\Http\Controllers\Order\OrderController@getToken')->name('order.getToken');
Route::get('/send-sms', '\App\Http\Controllers\Order\OrderController@sentSms')->name('order.sentSms');
//Route::middleware(['basicAuth'])->group(function () {
//    Route::post('/webhook-order', '\App\Http\Controllers\Webhook\WebHookController@webhookHandler');
//});

Route::group(['middleware' => ['auth']],function () {
    Route::get('/logout', '\App\Http\Controllers\Auth\AuthController@logout')->name('logout');
    Route::get('/', '\App\Http\Controllers\Statistic\StatisticController@main')->name('statistic');
    Route::get('/statistic-mfo', '\App\Http\Controllers\Statistic\StatisticMfoController@index')->name('statistic.mfo');
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
    Route::get('/order-mfo', '\App\Http\Controllers\Order\OrderMfoController@index')->name('order.createMfo');
    Route::post('/order-mfo', '\App\Http\Controllers\Order\OrderMfoController@store')->name('order.storeMfo');
    Route::post('/specification/{order}', '\App\Http\Controllers\Order\OrderController@downloadSpecification')->name('order.specification');
    Route::post('/specification-mfo/{order}', '\App\Http\Controllers\Order\OrderMfoController@downloadSpecification')->name('order.specificationmfo');
    Route::get('/check-order/{order}', '\App\Http\Controllers\Order\OrderController@checkOrders')->name('order.check');
    Route::get('/copy-order/{order}', '\App\Http\Controllers\Order\OrderController@copyOrder')->name('order.copy');
    Route::get('/check-order-mfo/{order}', '\App\Http\Controllers\Order\OrderMfoController@checkStatus')->name('order.checkStatusMfo');
    Route::get('/send-more-data-mfo/{order}', '\App\Http\Controllers\Order\OrderMfoController@moreData')->name('order.moreData');
    Route::get('/sign-mfo/{order}', '\App\Http\Controllers\Order\OrderMfoController@signOrder')->name('order.signMfo');
    Route::post('/send-more-data-mfo', '\App\Http\Controllers\Order\OrderMfoController@sendMoreData')->name('order.sendMoreData');
    Route::post('/sign-mfo', '\App\Http\Controllers\Order\OrderMfoController@signMfoSend')->name('order.signMfoSend');
    Route::post('/accept-sms', '\App\Http\Controllers\Order\OrderController@sendSmsCode')->name('order.sendSmsCode');
    Route::get('/continue-order/{order}', '\App\Http\Controllers\Order\OrderController@continueOrder')->name('order.continue');
    Route::get('/cancel-order/{order}', '\App\Http\Controllers\Order\OrderController@cancelOrder')->name('order.cancel');
    Route::get('/sms-notifications', '\App\Http\Controllers\Sms\SmsController@index')->name('sms.list');
    Route::get('/sms-settings', '\App\Http\Controllers\Sms\SmsController@settings')->name('sms.settings');
    Route::patch('/sms-settings', '\App\Http\Controllers\Sms\SmsController@storeSettings')->name('setting.store');

});

