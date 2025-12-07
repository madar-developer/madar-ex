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
Route::get('/cache', function(){
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('migrate');
});
// Route::get('/adelfes', function () {
//     return sendMadarxWebhook(798, 'delivered', 'mx-20250749772', '2025-08-04 00:00:00', 'Package delivered to customer successfully'  );
// });
Route::get('/', function () {
    return view('welcome');
});
Route::get('/privacy', function () {
    return view('privacy');
});
Route::get('/privecy.php', function () {
    // $orders = \App\Models\Order::whereNull('delivery_date')->where('status','delivered')->limit(500)->get();
    // foreach($orders as $order){
    //     $delivey_date = $order->OrderLog()->where('status', 'delivered')->first();
    //     if($delivey_date){
    //         $order->delivery_date = $delivey_date->created_at->toDateString();
    //         $order->save();
    //     }
        
    // }
    // return $orders = \App\Models\Order::whereNull('delivery_date')->where('status','delivered')->count();
    return view('privacy');
});
Route::get('/docs', function () {
    return view('docs');
});
Route::post('post_form','HomeController@PostForm');
Route::get('get-order-status/{id}','HomeController@getOrderStatus');
Route::get('get-order-status-ch','HomeController@getOrderStatusCh');
// Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/register', 'HomeController@Register')->name('company-register');
Route::post('/register', 'HomeController@RegisterPost');
                Route::get('/driver-finance-collect2/{id}', 'Admin\PdfController@driverFinanceCollectPdf')->name('driver-finance-collect.pdf2');
/*===============================================
|                  admin                        |
===============================================*/
Route::group(['namespace' =>'Admin'], function() {
    Route::get('/admin/login','AuthController@loginForm')->name('login');
    Route::post('/admin/login','AuthController@login');
    Route::get('/dashboard/city-id', 'CityController@GetStatusByAjex');
    
    Route::group(['prefix' => 'dashboard', 'middleware' => 'auth:admin'], function() {
        Route::get('show-invoice','HomeController@shoeInvoice');
        Route::post('/logout', 'AuthController@logout')->name('logout');
        Route::get('/', 'HomeController@index');
        Route::delete('/file-delete/{id}', 'HomeController@FDestroy')->name('files.destroy');
        Route::get('/order-bill/{id}','OrderController@bill');
        Route::resource('/contact_us', 'ContactUsController');
        Route::resource('/users', 'UserController');
        Route::resource('/shops', 'ShopController');
        Route::resource('/companies', 'CompanyController');
        Route::post('/companies-files/{id}', 'CompanyController@files')->name('companies-files');
        Route::post('/companies-pricelist/{id}', 'CompanyController@pricelist')->name('companies-pricelist');
        Route::resource('/company-addresses', 'CompanyAddressController');
        Route::resource('/company-cache-types', 'CompanyCacheTypeController');
        Route::resource('/carmaintaince', 'CarMaintenanceController');
        Route::resource('/orders', 'OrderController');
        Route::get('/orders-charts', 'OrderController@charts')->name('orders-charts');
        Route::get('/orders-invoice/{id}', 'OrderController@invoice')->name('orders-invoice');
        Route::post('/orders-invoice/{id}', 'OrderController@invoicePost');
        Route::post('/orders-ajax', 'OrderController@UAll')->name('orders-ajax');
        Route::post('/orders-excel', 'ExportExcelController@Import');
        Route::resource('/cars', 'CarController');
        Route::resource('/sliders', 'SliderController');
        Route::resource('/partners', 'PartnerController');
        Route::resource('/worktimes', 'WorkTimeController');
        Route::resource('/drivers', 'DriverController');
        Route::get('/drivers-charts', 'DriverController@charts')->name('drivers-charts');
        Route::get('/driver-finance-orders/{id}', 'DriverController@DFOrders');
        Route::resource('/driver-prices', 'DriverPriceController');
        Route::post('/drivers-files/{id}', 'DriverController@files')->name('drivers-files');
        // Route::resource('/driver-finances', 'DriverFinanceController');
        // Route::resource('/branch-finances', 'BranchFinanceController');
        Route::resource('/driver-finances', 'DriverFinanceController');
        Route::resource('/branch-finances', 'BranchFinanceController');
        Route::get('/branch-finances-r-t-m', 'BranchFinanceController@RTM')->name('branch-finance-r-t-m');
        Route::post('/branch-finances-r-t-m', 'BranchFinanceController@RTMPost');
        //
        // Route::get('/drivers-collect-orders/{id}', 'DriverController@CollectOrders')->name('drivers.collect-orders');
        Route::post('/drivers-collect-orders/{id}', 'DriverController@CollectOrders')->name('drivers.collect-orders');
        Route::post('/drivers-cashed-orders/{id}', 'DriverController@CashedOrders')->name('drivers.cashed-orders');
        Route::resource('/cars', 'CarController');
        Route::resource('/invoices', 'InvoiceController');
        Route::post('/invoices-transfer', 'InvoiceController@Transfer')->name('invoices-transfer');
        Route::resource('/mail', 'MailController');
        Route::resource('/transfers', 'TransferController');
        Route::get('/transfers-recalculate/{id}', 'TransferController@recalculate')->name('transfers.recalculate');
        Route::get('/transfer-report/{id}', 'PdfController@transferPdf')->name('transfers.report');
        Route::resource('/admins', 'AdminController');
        Route::get('/noti-count', 'AdminController@notiCount')->name('noti-count');
        Route::get('/show_invoices', 'TransferController@showInvoices');
        Route::get('/tranfer-invoices/{id}', 'TransferController@transferInvoices');
        Route::resource('/cities', 'CityController');
        Route::resource('/city-groups', 'CityGroupController');
        Route::resource('/payment-methods', 'PaymentMethodController');
        Route::resource('/avaliable-methods', 'AvailableMethodController');
        Route::resource('/order-status', 'OrderStatusController');
        Route::resource('/discounts', 'DiscountController');
        Route::resource('/event-discounts', 'EventDiscountController');
        Route::resource('/blogs', 'BlogController');
        Route::resource('/pricelists', 'PriceListController');
        Route::resource('/branches', 'BranchController');
        Route::resource('/transactions', 'TransactionController');
        Route::resource('/trips', 'TripController');
        Route::resource('/payments', 'PaymentController');
        Route::resource('/products', 'ProductController');
        Route::resource('/sliders', 'SliderController');
        Route::resource('/notifications', 'NotificationController')->only(['index', 'store']);

        Route::group(['prefix' => 'reports'], function() {
            Route::get('/orders', 'ReportController@ordersGet');
            Route::post('/orders', 'ReportController@ordersPost');
            Route::get('/companies', 'CompanyExController@companiesGet');
            Route::post('/companies', 'CompanyExController@companiesPost');
            Route::get('/drivers', 'DriverExController@driversGet');
            Route::post('/drivers', 'DriverExController@driversPost');
            // Route::get('companies', 'ExportExcelController@index');
            // Route::get('companies', 'ExportExcelController@excel')->name('export_excel.excel');
            Route::group(['prefix' => 'pdf'], function() {
                Route::get('/company/{id}', 'PdfController@companyPdf')->name('company.pdf');
                Route::get('/company-finance/{id}', 'PdfController@companyFinancePdf')->name('company-finance.pdf');
                Route::get('/order/{id}', 'PdfController@orderPdf')->name('order.pdf');
                Route::get('/invoice/{id}', 'PdfController@invoicePdf')->name('invoice.pdf');
                Route::get('/driver/{id}', 'PdfController@driverPdf')->name('driver.pdf');
                Route::get('/driver-finance/{id}', 'PdfController@driverFinancePdf')->name('driver-finance.pdf');
                Route::get('/driver-finance-collect/{id}', 'PdfController@driverFinanceCollectPdf')->name('driver-finance-collect.pdf');
                Route::get('/driver-finance-collect-excel/{id}', 'DriverController@driverFinanceCollectExcel')->name('driver-finance-collect.excel');
            });
        });
        Route::group(['prefix' => 'statistics'], function() {
            Route::get('orders-company', "StatisticsController@OrdersCompany");
        });
        Route::group(['prefix' => 'settings'], function() {
            Route::get('/site', 'SettingController@index');
            Route::post('/site', 'SettingController@store');
            Route::get('/admins', 'SettingsController@AdminGet');
            Route::post('/admins', 'SettingsController@AdminPost');
            Route::get('/admins-edit/{id?}', 'SettingsController@AdminEdit');
            Route::post('/admins-edit/{id?}', 'SettingsController@AdminUpdate');
            Route::post('/admins-update/{id}', 'SettingsController@AdminUpdate');
            Route::delete('/admins-delete/{id}', 'SettingsController@AdminDelete');
            Route::get('/regions', 'SettingsController@RegionGet');
            Route::post('/regions', 'SettingsController@RegionPost');
            Route::post('/regions-update/{id}', 'SettingsController@RegionUpdate');
            Route::delete('/regions-delete/{id}', 'SettingsController@RegionDelete');
            Route::get('/car-types', 'SettingsController@CarTypeGet');
            Route::post('/car-types', 'SettingsController@CarTypePost');
            Route::post('/car-types-update/{id}', 'SettingsController@CarTypeUpdate');
            Route::delete('/car-types-delete/{id}', 'SettingsController@CarTypeDelete');
            Route::get('/bustypes', 'SettingsController@BusTypeGet');
            Route::post('/bustypes', 'SettingsController@BusTypePost');
            Route::post('/bustypes-update/{id}', 'SettingsController@BusTypeUpdate');
            Route::delete('/bustypes-delete/{id}', 'SettingsController@BusTypeDelete');
            Route::get('/colors', 'SettingsController@ColorGet');
            Route::post('/colors', 'SettingsController@ColorPost');
            Route::post('/colors-update/{id}', 'SettingsController@ColorUpdate');
            Route::delete('/colors-delete/{id}', 'SettingsController@ColorDelete');
            Route::get('/years', 'SettingsController@YearGet');
            Route::post('/years', 'SettingsController@YearPost');
            Route::post('/years-update/{id}', 'SettingsController@YearUpdate');
            Route::delete('/years-delete/{id}', 'SettingsController@YearDelete');

            Route::get('/models', 'SettingsController@ModelsGet');
            Route::post('/models', 'SettingsController@ModelsPost');
            Route::post('/models-update/{id}', 'SettingsController@ModelsUpdate');
            Route::delete('/models-delete/{id}', 'SettingsController@ModelsDelete');
            // ***********************************************************************
            Route::get('/missions', 'SettingsController@MissionsGet');
            Route::post('/missions', 'SettingsController@MissionsPost');
            Route::post('/missions-update/{id}', 'SettingsController@MissionsUpdate');
            Route::delete('/missions-delete/{id}', 'SettingsController@ModelsDelete');
            // ********************************************************************
            Route::get('/categories', 'SettingsController@CategoriesGet');
            Route::post('/categories', 'SettingsController@CategoriesPost');
            Route::post('/categories-update/{id}', 'SettingsController@CategoriesUpdate');
            Route::delete('/categories-delete/{id}', 'SettingsController@CategoriesDelete');

            Route::get('/price-lists', 'SettingsController@PriceListGet');
            Route::post('/price-lists', 'SettingsController@PriceListPost');
            Route::post('/price-lists-update/{id}', 'SettingsController@PriceListUpdate');
            Route::delete('/price-lists-delete/{id}', 'SettingsController@PriceListDelete');
            Route::get('/permissions', 'SettingsController@PermissionGet');
            Route::post('/permissions', 'SettingsController@PermissionPost');
            Route::post('/permissions-update/{id}', 'SettingsController@PermissionUpdate');
            Route::delete('/permissions-delete/{id}', 'SettingsController@PermissionDelete');
            Route::delete('/user-permissions-delete/{id}', 'SettingsController@PermissionDeleteUser');
            Route::post('/user-role', 'SettingsController@UserRole');
        });
    });
});
Route::group(['prefix' => 'company', 'namespace' => 'Admin', 'middleware' => 'auth:company'], function() {
    Route::get('/', 'CompanyHomeController@index');
    Route::resource('/company-orders', 'CompanyOrderController');
    Route::get('/order-bill/{id}','CompanyOrderController@bill');
    Route::get('/noti-count-com', 'CompanyOrderController@notiCount')->name('noti-count-company');
    Route::post('/company-orders-excel', 'ExportExcelController@CompanyImport');
    Route::get('/profile', 'CompanyProfileController@profile')->name('company-profile');
    Route::resource('/company-company-addresses', 'CCompanyAddressController');
    Route::resource('/request-drivers', 'CompanyRequestDriverController');
    Route::resource('/company-company-cache-types', 'CompanyCacheTypeController');
    Route::resource('/company-invoices', 'CompanyInvoiceController');
    Route::resource('/company-transfers', 'CompanyTransferController');
    Route::get('/transfer-report/{id}', 'PdfController@transferPdf')->name('company-transfers.report');
    Route::get('/tranfer-invoices/{id}', 'CompanyTransferController@transferInvoices');
    Route::get('/company-finance', 'PdfController@CcompanyFinancePdf')->name('company.company-finance.pdf');
    /////////////////////////// pdf
    Route::group(['prefix' => 'pdf'], function() {
        Route::get('/company-invoices/{id}', 'PdfCompanyController@invoicePdf')->name('company-invoices.pdf');
        Route::get('/company-transfers/{id}', 'PdfCompanyController@transferPdf')->name('company-transfers.pdf');
    });
    /////////////////////////
    Route::group(['prefix' => 'settings'], function() {
        Route::get('/admins-edit', 'CompanySettingsController@AdminEdit');
        Route::post('/admins-edit', 'CompanySettingsController@AdminUpdate');
    });
});

/*===============================================
|                 admin                         |
===============================================*/
