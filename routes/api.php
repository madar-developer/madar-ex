<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['apilocale'])->group(function () {

    Route::get('get-info', 'Api\ServiceController@info');
    Route::get('get-status-list', 'Api\ServiceController@StatusList');
    Route::post('get-token', 'Api\ServiceController@getToken');
    Route::post('send-order', 'Api\ServiceController@addOrder');
    Route::post('get-order-history', 'Api\ServiceController@getHistory');
Route::post('get-bill', 'Api\ServiceController@printPdf');
Route::post('canel-order', 'Api\ServiceController@cancelOrder');

Route::group(['prefix' => '/v1', 'namespace' => 'Api'], function() {
    Route::post('get-order-history', 'ServiceController@getHistory');
    Route::group(['prefix' => '/driver', 'namespace' => 'Driver'], function() {
        Route::post('/signin', 'AuthController@login');
        Route::post('forget-password', 'ProfileController@ForgetPassword');
        Route::group(['middleware' => 'auth:api-driver'], function() {
            Route::post('logout', 'AuthController@logout');
            Route::get('delete-account', 'ProfileController@delete_account');
            Route::post('delete-account', 'ProfileController@delete_account');
            Route::get('profile', 'ProfileController@profile');
            Route::get('statistics', 'ProfileController@statistics');
            Route::get('finance', 'ProfileController@finance');
            Route::get('notifications', 'ProfileController@notifications');
            Route::post('delete-notification', 'ProfileController@markNotificationReaded');
            Route::post('profile-update', 'ProfileController@update');
            Route::get('orders', 'OrderController@index');
            Route::post('orders-search', 'OrderController@searchOrders');
            Route::get('finance-not-collected', 'OrderController@NotCollectedStatistics');
            Route::get('sidemenu-counts', 'OrderController@MenuCounts');
            Route::get('invoices', 'OrderController@InvoicesStatistics');
            Route::get('invoice-show/{id}', 'OrderController@InvoiceShow');
            Route::get('orders/show/{id}', 'OrderController@show');
            Route::post('orders/accept', 'OrderController@accept');
            Route::post('orders/refuse', 'OrderController@refuse');
            Route::post('orders/change-cash-type', 'OrderController@changeCashType');
            Route::post('orders/change-status', 'OrderController@changestatus');
            Route::post('orders/change-orders-status', 'OrderController@changestatusArr');
            Route::post('orders/reschedule', 'OrderController@reschedule');
        });
    });
    Route::group(['prefix' => '/driver','middleware' => 'auth:api-driver'], function() {
        Route::post('/upload-file', 'AppInfoController@UploadFile');
    });
    Route::group(['prefix' => '/company', 'namespace' => 'Company'], function() {
        Route::post('/signin', 'AuthController@login');
        Route::post('/signup', 'AuthController@signup');
        Route::post('forget-password', 'ProfileController@ForgetPassword');
        Route::group(['middleware' => 'auth:api-company'], function() {
            Route::get('delete-account', 'ProfileController@delete_account');
            Route::post('delete-account', 'ProfileController@delete_account');
            Route::post('logout', 'AuthController@logout');
            Route::get('profile', 'ProfileController@profile');
            Route::post('profile-update', 'ProfileController@update');
            Route::get('notifications', 'ProfileController@notifications');
            Route::post('delete-notification', 'ProfileController@markNotificationReaded');
            Route::get('orders', 'OrderController@index');
            Route::post('orders', 'OrderController@index');
            Route::get('orders/{id}', 'OrderController@show');
            Route::post('orders/update/{id}', 'OrderController@update');
            Route::post('orders/store', 'OrderController@store');
            Route::get('invoices', 'CompanyController@invoices');
            Route::post('invoices-by-date', 'CompanyController@invoicesByDay');
            Route::get('transfers', 'CompanyController@transfers');
            Route::get('transfer-invoices/{id}', 'CompanyController@transferInvoices');
            Route::get('statistics', 'CompanyController@statistics');
            // cache types
            Route::get('pay-way', 'PayWayController@index');
            Route::post('pay-way/edit/{id}', 'PayWayController@update');
            Route::post('pay-way/delete/{id}', 'PayWayController@destroy');
            Route::post('pay-way/store', 'PayWayController@store');
            // cache types
            Route::get('addresses', 'AddressController@index');
            Route::post('addresses/edit/{id}', 'AddressController@update');
            Route::post('addresses/delete/{id}', 'AddressController@destroy');
            Route::post('addresses/store', 'AddressController@store');
        });
        Route::get('info', 'InfoController@index');

    });

    Route::get('/test-noti/{type}', 'TestNotiController@index');
    Route::get('/order-types', 'AppInfoController@OrderType');
    Route::get('/app-info', 'AppInfoController@index');
    Route::get('/times', 'AppInfoController@times');
    Route::get('/slider', 'AppInfoController@slider');
    Route::get('/get-statuses', 'AppInfoController@getStatuses');
    Route::get('/deliver-failed-option', 'AppInfoController@FailDeliverOption');
});

});
