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

use App\Http\Controllers\StripePaymentController;

Auth::routes();

Route::group(['middleware' => 'auth'], function() {
	Route::get('/dashboard', 'HomeController@dashboard');
});

Route::get('user/signup', 'HomeController@signup')->name('user.signup');
Route::get('user/login', 'HomeController@login')->name('user.login');
Route::get('/forgot/password', 'HomeController@forgotPassword')->name('forgot.password');
Route::post('/forgot/password','HomeController@forgotPasswordStore')->name('forgot.password');
Route::post('/forgot/password/verify','HomeController@forgotPasswordCheck')->name('otp.verify.password');
Route::post('/shop/password/change', 'HomeController@forgotPasswordCheckStore')->name('shop.password.change');

Route::get('language_switch/{locale}', 'LanguageController@switchLanguage');
Route::get('/payment-cancel', [StripePaymentController::class, 'paymentCancel'])->name('payment.cancel');

//frontend
Route::group(['middleware' => 'checkOtp'], function() {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/about', 'HomeController@about')->name('about');
    Route::get('/contact', 'HomeController@contact')->name('contact');
    Route::post('/contact/message', 'HomeController@contactMessage')->name('contact.message');
    Route::get('user/contentant', 'HomeController@userContentant')->name('user.contentant');
    Route::get('musician/data/{id}', 'HomeController@employee')->name('musician.data');
    Route::post('musician/find', 'HomeController@employeeFind')->name('musician.find');
    Route::post('musician/vote', 'HomeController@employeeVote')->name('musician.vote');
    Route::get('musician/team', 'HomeController@team')->name('team');
    Route::post('musician/vote/payment', 'HomeController@musicianVotePayment')->name('musician.vote.payment');
    Route::get('musician/vote/payment/coin', 'HomeController@musicianVotePaymentCoin')->name('musician.vote.payment.coin');
    Route::get('/musician/vote/payment/check', 'HomeController@musicianVotePaymentCheck')->name('musician.vote.payment.check');
    Route::post('musician/vote/payment/Stripe', 'HomeController@musicianVotePaymentStripe')->name('musician.vote.payment.stripe');
    Route::get('/musician/vote/payment/check/Stripe', 'HomeController@musicianVotePaymentCheckStripe')->name('musician.vote.payment.check.stripe');

    Route::get('/campay/webhook', 'HomeController@handleCampayWebhook')->name('campay.webhook');



    Route::get('/events', 'HomeController@events')->name('events');

	Route::get('/tickets/{id}', 'HomeController@tickets')->name('tickets');
	Route::get('/ticket/data/{id}', 'HomeController@ticket')->name('ticket.data');
	Route::post('/purchase/ticket', 'HomeController@purchaseTicket')->name('purchase.ticket');

	Route::post('/ticket/payment', 'HomeController@ticketPayment')->name('ticket.payment');
    Route::get('/ticket/payment/check', 'HomeController@ticketPaymentCheck')->name('ticket.payment.check');
    Route::post('/ticket/payment/Stripe', 'HomeController@ticketPaymentStripe')->name('ticket.payment.stripe');
    Route::get('/ticket/payment/check/Stripe', 'HomeController@ticketPaymentCheckStripe')->name('ticket.payment.check.stripe');


	Route::get('/ticket/scan/{token}', 'HomeController@ticketScan')->name('ticket.scan');
    Route::get('/ticket/scan/used/{token}', 'HomeController@ticketScanUsed')->name('ticket.scan.used');

	Route::get('user/events', 'HomeController@userEvents')->name('user.events');

});

//end frontend

Route::group(['middleware' => ['auth', 'active']], function() {


	Route::post('importunit', 'UnitController@importUnit')->name('unit.import');
	Route::post('unit/deletebyselection', 'UnitController@deleteBySelection');
	Route::get('unit/lims_unit_search', 'UnitController@limsUnitSearch')->name('unit.search');
	Route::resource('unit', 'UnitController');

	Route::post('category/import', 'CategoryController@import')->name('category.import');
	Route::post('category/deletebyselection', 'CategoryController@deleteBySelection');
	Route::post('category/category-data', 'CategoryController@categoryData');
    Route::get('/categoryStats/{id}', 'CategoryController@categoryStats');
	Route::resource('category', 'CategoryController');

	Route::post('importtax', 'TaxController@importTax')->name('tax.import');
	Route::post('tax/deletebyselection', 'TaxController@deleteBySelection');
	Route::get('tax/lims_tax_search', 'TaxController@limsTaxSearch')->name('tax.search');
	Route::resource('tax', 'TaxController');


	Route::resource('stock-count', 'StockCountController');
	Route::post('stock-count/finalize', 'StockCountController@finalize')->name('stock-count.finalize');
	Route::get('stock-count/stockdif/{id}', 'StockCountController@stockDif');
	Route::get('stock-count/{id}/qty_adjustment', 'StockCountController@qtyAdjustment')->name('stock-count.adjustment');

	Route::get('qty_adjustment/getproduct/{id}', 'AdjustmentController@getProduct')->name('adjustment.getproduct');
	Route::get('qty_adjustment/lims_product_search', 'AdjustmentController@limsProductSearch')->name('product_adjustment.search');
	Route::post('qty_adjustment/deletebyselection', 'AdjustmentController@deleteBySelection');
	Route::resource('qty_adjustment', 'AdjustmentController');

	//Route::get('products/getbarcode', 'ProductController@getBarcode');
	Route::get('productsStats/{id}', 'ProductController@productsStats');
	Route::post('products/product-data', 'ProductController@productData');
	Route::post('products/product-data/vendor', 'ProductController@productDataVendor');
	Route::get('products/gencode', 'ProductController@generateCode');
	Route::get('products/search', 'ProductController@search');
	Route::get('products/saleunit/{id}', 'ProductController@saleUnit');
	Route::get('products/getdata/{id}', 'ProductController@getData');
	Route::get('products/product_warehouse/{id}', 'ProductController@productWarehouseData');
	Route::post('importproduct', 'ProductController@importProduct')->name('product.import');
	Route::post('exportproduct', 'ProductController@exportProduct')->name('product.export');
	Route::get('products/print_barcode','ProductController@printBarcode')->name('product.printBarcode');

	Route::get('products/lims_product_search', 'ProductController@limsProductSearch')->name('product.search');
	Route::post('products/deletebyselection', 'ProductController@deleteBySelection');
    Route::get('/editbyselection/warehouse/products', 'ProductController@warehouseProducts')->name('edit.by.selection.warehouse.products');
	Route::get('products/editbyselection', 'ProductController@editBySelection')->name('product.edit.by.selection');
    Route::get('products/editbyselection/page', 'ProductController@editBySelectionPage')->name('product.edit.by.selection.page');
	Route::post('products/updatebyselection', 'ProductController@updateBySelection')->name('product.update.by.selection');
	Route::post('products/update', 'ProductController@updateProduct');
	Route::get('products/store/model', 'SaleController@storeModel')->name('product.store.model');
	Route::resource('products', 'ProductController');

    Route::get('/admin/tickets/index', 'TicketController@index')->name('admin.ticket.index');

    Route::get('/admin/ticket/scan/screen', 'ProductController@ticketScanScreen')->name('admin.ticket.scan.screen');
    Route::post('/admin/ticket/scan', 'ProductController@ticketScan')->name('admin.ticket.scan');
    Route::get('/admin/ticket/scan/used/{token}', 'ProductController@ticketScanUsed')->name('admin.ticket.scan.used');

	Route::post('importcustomer_group', 'CustomerGroupController@importCustomerGroup')->name('customer_group.import');
	Route::post('customer_group/deletebyselection', 'CustomerGroupController@deleteBySelection');
	Route::get('customer_group/lims_customer_group_search', 'CustomerGroupController@limsCustomerGroupSearch')->name('customer_group.search');
	Route::resource('customer_group', 'CustomerGroupController');

	  Route::get('customer/payment_check', 'CustomerController@CustomerPayemntCheck')->name('customer.payment_check');
	Route::post('importcustomer', 'CustomerController@importCustomer')->name('customer.import');
	Route::get('customer/getDeposit/{id}', 'CustomerController@getDeposit');
	Route::post('customer/add_deposit', 'CustomerController@addDeposit')->name('customer.addDeposit');
	Route::post('customer/update_deposit', 'CustomerController@updateDeposit')->name('customer.updateDeposit');
	Route::post('customer/deleteDeposit', 'CustomerController@deleteDeposit')->name('customer.deleteDeposit');
	Route::post('customer/deletebyselection', 'CustomerController@deleteBySelection');
	Route::get('customer/lims_customer_search', 'CustomerController@limsCustomerSearch')->name('customer.search');
	Route::resource('customer', 'CustomerController');
    Route::get('/customer/gen_payment_invoice/{id}', 'CustomerController@genInvoice')->name('customer.gen_payment_invoice');
    Route::get('/customer_group/gen_payment_invoice/{id}', 'CustomerGroupController@genInvoice')->name('customer_group.gen_payment_invoice');
    Route::get('customer_group/customers/{id}', 'CustomerController@CustomerGroupCustomers')->name('customer_group.customers');
    Route::get('customer_group/deposits/{id}', 'CustomerGroupController@Deposits')->name('customer_group.deposits');
    Route::get('customer_group/payments/{id}', 'CustomerGroupController@Payments')->name('customer_group.payments');
    Route::post('customer_group/add_deposit', 'CustomerGroupController@addDeposit')->name('customer_group.addDeposit');

	Route::post('purchases/purchase-data', 'PurchaseController@purchaseData')->name('purchases.data');
	Route::get('purchases/product_purchase/{id}','PurchaseController@productPurchaseData');
	Route::get('purchases/lims_product_search', 'PurchaseController@limsProductSearch')->name('product_purchase.search');
	Route::post('purchases/add_payment', 'PurchaseController@addPayment')->name('purchase.add-payment');
	Route::get('purchases/getpayment/{id}', 'PurchaseController@getPayment')->name('purchase.get-payment');
	Route::post('purchases/updatepayment', 'PurchaseController@updatePayment')->name('purchase.update-payment');
	Route::post('purchases/deletepayment', 'PurchaseController@deletePayment')->name('purchase.delete-payment');
	Route::get('purchases/purchase_by_csv', 'PurchaseController@purchaseByCsv');
	Route::post('importpurchase', 'PurchaseController@importPurchase')->name('purchase.import');
	Route::post('purchases/deletebyselection', 'PurchaseController@deleteBySelection');
	Route::resource('purchases', 'PurchaseController');


    Route::post('/logout', 'HomeController@logout')->name('logout');
	Route::get('/otp/screen', 'HomeController@otpCheck')->name('check.otp');
	Route::post('/otp/screen/store', 'HomeController@otpCheckStore')->name('check.otp.store');
	Route::get('/admin', 'HomeController@admin');
	Route::get('/wp', 'HomeController@whatsapp');
	Route::get('/mmt', 'HomeController@mobileMoneyToken');
	Route::get('/mmr', 'HomeController@mobileMoneyRequest');
	Route::get('/mms', 'HomeController@mobileMoneyStatus');
	Route::get('/dashboard-filter/{start_date}/{end_date}', 'HomeController@dashboardFilter');
	Route::get('check-batch-availability/{product_id}/{batch_no}/{warehouse_id}', 'ProductController@checkBatchAvailability');

	Route::get('role/permission/{id}', 'RoleController@permission')->name('role.permission');
	Route::post('role/set_permission', 'RoleController@setPermission')->name('role.setPermission');
	Route::resource('role', 'RoleController');

	Route::get('user/profile/{id}', 'UserController@profile')->name('user.profile');
	Route::put('user/update_profile/{id}', 'UserController@profileUpdate')->name('user.profileUpdate');
	Route::put('user/changepass/{id}', 'UserController@changePassword')->name('user.password');
	Route::get('user/genpass', 'UserController@generatePassword');
	Route::post('user/deletebyselection', 'UserController@deleteBySelection');
	Route::resource('user','UserController');

	Route::get('setting/general_setting', 'SettingController@generalSetting')->name('setting.general');
	Route::post('setting/general_setting_store', 'SettingController@generalSettingStore')->name('setting.generalStore');
	Route::get('setting/general_setting/change-theme/{theme}', 'SettingController@changeTheme');
	Route::get('setting/sms_setting', 'SettingController@smsSetting')->name('setting.sms');
	Route::get('setting/createsms', 'SettingController@createSms')->name('setting.createSms');
	Route::post('setting/sendsms', 'SettingController@sendSms')->name('setting.sendSms');
	Route::post('setting/sms_setting_store', 'SettingController@smsSettingStore')->name('setting.smsStore');

	Route::get('expense_categories/gencode', 'ExpenseCategoryController@generateCode');
	Route::post('expense_categories/import', 'ExpenseCategoryController@import')->name('expense_category.import');
	Route::post('expense_categories/deletebyselection', 'ExpenseCategoryController@deleteBySelection');
	Route::resource('expense_categories', 'ExpenseCategoryController');

    Route::post('importbrand', 'BrandController@importBrand')->name('brand.import');
    Route::post('brand/deletebyselection', 'BrandController@deleteBySelection');
    Route::get('brand/lims_brand_search', 'BrandController@limsBrandSearch')->name('brand.search');
    Route::resource('brand', 'BrandController');

    Route::post('importwarehouse', 'WarehouseController@importWarehouse')->name('warehouse.import');
    Route::post('warehouse/deletebyselection', 'WarehouseController@deleteBySelection');
    Route::get('warehouse/lims_warehouse_search', 'WarehouseController@limsWarehouseSearch')->name('warehouse.search');
    Route::resource('warehouse', 'WarehouseController');

    Route::post('expenses/deletebyselection', 'ExpenseController@deleteBySelection');
	Route::resource('expenses', 'ExpenseController');
	Route::get('/expense/asset', 'ExpenseController@asset')->name('asset.expense');
	//accounting routes
	Route::get('accounts/make-default/{id}', 'AccountsController@makeDefault');
	Route::resource('accounts', 'AccountsController');
	//HRM routes
	Route::post('departments/deletebyselection', 'DepartmentController@deleteBySelection');
	Route::resource('departments', 'DepartmentController');

	Route::post('musician/deletebyselection', 'EmployeeController@deleteBySelection');
	Route::resource('musician', 'EmployeeController');
    Route::get('musician/gallery/{id}', 'EmployeeController@gallery')->name('musician.gallery');
    Route::get('musician/gallery/delete/{id}', 'EmployeeController@galleryDestroy')->name('musician.gallery.delete');
    Route::get('musician/gallery/edit/{id}', 'EmployeeController@galleryEdit')->name('musician.gallery.edit');
    Route::get('musician/gallery/update', 'EmployeeController@galleryUpload')->name('musician.gallery.update');
    Route::get('musician/upload/{id}', 'EmployeeController@upload')->name('musician.upload');
    Route::post('musician/upload/store', 'EmployeeController@uploadStore')->name('musician.file.store');
    Route::get('musician/votes/{id}', 'EmployeeController@votes')->name('musician.votes');
    Route::get('/musician/pending/index', 'EmployeeController@pending')->name('musician.pending.index');
    Route::get('/musician/approve/{id}', 'EmployeeController@approveStore')->name('musician.approve');
    Route::get('/musician/reject/{id}', 'EmployeeController@rejectStore')->name('musician.reject');


    Route::post('notifications/store', 'NotificationController@store')->name('notifications.store');
    Route::get('notifications/mark-as-read', 'NotificationController@markAsRead');

    Route::resource('currency', 'CurrencyController');

    Route::get('my-transactions/{year}/{month}', 'HomeController@myTransaction');


    Route::resource('votes', 'VoteController');
    Route::post('votes/deletebyselection', 'VoteController@deleteBySelection');
    Route::resource('judge', 'JudgeController');
    Route::post('judge/deletebyselection', 'JudgeController@deleteBySelection');
    Route::resource('ambassador', 'AmbassadorController');
    Route::resource('coins', 'CoinController');
    Route::post('coins/deletebyselection', 'CoinController@deleteBySelection');


    Route::get('admin/user', 'UserController@admin')->name('admin.index');
    Route::get('voter/user', 'UserController@voter')->name('voter.index');

    Route::get('report/voting', 'ReportController@votingReport')->name('voting.report');


});


Route::get('/qr', 'QRController@show');
Route::get('/scan/{token}', 'QRController@scan');

