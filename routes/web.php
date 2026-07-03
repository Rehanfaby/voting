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

// Admin / staff password reset via WhatsApp OTP
Route::get('/admin/forgot-password', 'HomeController@adminForgotPassword')->name('admin.password.request');
Route::post('/admin/forgot-password', 'HomeController@adminForgotPasswordStore')->name('admin.password.send');
Route::get('/admin/forgot-password/verify', 'HomeController@adminForgotPasswordVerifyForm')->name('admin.password.verify');
Route::post('/admin/forgot-password/verify', 'HomeController@adminForgotPasswordVerify')->name('admin.password.verify.submit');
Route::get('/admin/forgot-password/reset', 'HomeController@adminForgotPasswordResetForm')->name('admin.password.reset.form');
Route::post('/admin/forgot-password/reset', 'HomeController@adminForgotPasswordReset')->name('admin.password.reset');

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
    Route::get('/musician/vote/payment/pending/{id}', 'HomeController@musicianVotePaymentPending')->name('musician.vote.payment.pending');
    Route::get('/musician/vote/payment/poll', 'HomeController@musicianVotePaymentPoll')->name('musician.vote.payment.poll');
    Route::post('musician/vote/payment/Stripe', 'HomeController@musicianVotePaymentStripe')->name('musician.vote.payment.stripe');
    Route::get('/musician/vote/payment/check/Stripe', 'HomeController@musicianVotePaymentCheckStripe')->name('musician.vote.payment.check.stripe');

    Route::get('/campay/webhook', 'HomeController@handleCampayWebhook')->name('campay.webhook');



    Route::get('/events', 'HomeController@events')->name('events');

	Route::get('/tickets/{id}', 'HomeController@tickets')->name('tickets');
	Route::get('/ticket/data/{id}', 'HomeController@ticket')->name('ticket.data');
	Route::get('/api/ticket/{id}/seats', 'ProductSeatMapController@publicSeats')->name('ticket.seats.public');
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
    Route::get('products/{id}/seat-map', 'ProductSeatMapController@edit')->name('products.seat_map');
    Route::post('products/{id}/seat-map/settings', 'ProductSeatMapController@saveSettings')->name('products.seat_map.settings');
    Route::post('products/{id}/seat-map/zones', 'ProductSeatMapController@saveZone')->name('products.seat_map.zones');
    Route::delete('products/seat-zones/{zoneId}', 'ProductSeatMapController@deleteZone')->name('products.seat_zones.destroy');
    Route::post('products/{id}/seat-map/seats', 'ProductSeatMapController@saveSeats')->name('products.seat_map.seats');

    Route::get('/admin/tickets/index', 'TicketController@index')->name('admin.ticket.index');
    Route::post('/tickets/deletebyselection', 'TicketController@deleteBySelection')->name('tickets.deleteBySelection');

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

	Route::get('setting/site_content', 'SettingController@siteContent')->name('setting.site_content');
	Route::post('setting/site_content/section', 'SettingController@siteContentStoreSection')->name('setting.site_content.section');
	Route::post('setting/site_content_store', 'SettingController@siteContentStore')->name('setting.site_content.store');
	Route::get('setting/general_setting', 'SettingController@generalSetting')->name('setting.general');
    Route::get('setting/env', 'SettingController@envSetting')->name('setting.env');
    Route::post('setting/env', 'SettingController@envSettingStore')->name('setting.env.store');
    Route::get('setting/grading_setting', 'SettingController@gradingSetting')->name('setting.grading');
	Route::post('setting/general_setting_store', 'SettingController@generalSettingStore')->name('setting.generalStore');
	Route::post('setting/grading_setting_store', 'SettingController@gradingSettingStore')->name('setting.gradingStore');
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
	Route::post('musician/approvebyselection', 'EmployeeController@approveBySelection')->name('musician.approveBySelection');
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
    Route::post('ambassador/deletebyselection', 'AmbassadorController@deleteBySelection');
    Route::get('about-us', 'AboutUsController@index')->name('about_us.index');
    Route::get('about-us/settings', 'AboutUsController@settings')->name('about_us.settings');
    Route::post('about-us/settings', 'AboutUsController@settingsStore')->name('about_us.settings.store');
    Route::get('about-us/values', 'AboutUsController@values')->name('about_us.values');
    Route::post('about-us/values', 'AboutUsController@valuesStore')->name('about_us.values.store');
    Route::get('about-us/winners', 'AboutUsController@winners')->name('about_us.winners');
    Route::post('about-us/winners', 'AboutUsController@winnersStore')->name('about_us.winners.store');
    Route::post('about-us', 'AboutUsController@store')->name('about_us.store');
    Route::post('about-us/update', 'AboutUsController@update')->name('about_us.update');
    Route::delete('about-us/{id}', 'AboutUsController@destroy')->name('about_us.destroy');
    Route::resource('coins', 'CoinController');
    Route::post('coins/deletebyselection', 'CoinController@deleteBySelection');

    Route::get('/announcement/index', 'AnnouncementController@index')->name('announcement.index');
    Route::get('/announcement/create', 'AnnouncementController@create')->name('announcement.create');
    Route::get('/announcement/show/{id}', 'AnnouncementController@show')->name('announcement.show');
    Route::post('/announcement/store', 'AnnouncementController@store')->name('announcement.store');
    Route::get('/announcement/{announcement}/edit', 'AnnouncementController@edit')->name('announcement.edit');
    Route::put('/announcement/{announcement}', 'AnnouncementController@update')->name('announcement.update');
    Route::post('/announcement/update/{id}', 'AnnouncementController@update')->name('announcement.update');
    Route::get('/announcement/delete/{id}', 'AnnouncementController@destroy')->name('announcement.destroy');

    Route::post('/announcement/upload/image', 'AnnouncementController@imageUpload')->name('announcement.upload.image');
    Route::get('/announcement/{id}/send', 'AnnouncementController@send')->name('announcement.send');
    Route::get('/announcement/{id}/send/whatsapp', 'AnnouncementController@sendWhatsapp')->name('announcement.send.whatsapp');
    Route::get('/announcement/{id}/send/mail', 'AnnouncementController@sendEmail')->name('announcement.send.mail');
    Route::get('/announcement/{id}/download', 'AnnouncementController@download')->name('announcement.send.download');
    Route::get('/announcement/{id}/print', 'AnnouncementController@print')->name('announcement.send.print');
    Route::get('/announcement/attachment/delete/{id}', 'AnnouncementController@announcementAttachmentDelete')->name('announcement.attachment.delete');
    Route::get('/announcement/attachment/delete/first/{id}', 'AnnouncementController@announcementAttachmentDeleteFirst')->name('announcement.attachment.delete.first');



    Route::get('admin/user', 'UserController@admin')->name('admin.index');
    Route::get('voter/user', 'UserController@voter')->name('voter.index');

    Route::get('report/voting', 'ReportController@votingReport')->name('voting.report');
    Route::get('report/centre', 'ReportController@reportCentre')->name('report.centre');
    Route::get('report/votes-by-region', 'ReportController@votesByRegionReport')->name('report.votes.by.region');
    Route::get('report/ticket/sales', 'ReportController@ticketSalesSummaryReport')->name('report.ticket.sales');
    Route::get('report/contestants/list', 'ReportController@contestantsListReport')->name('report.contestants.list');
    Route::get('report/income-expense', 'ReportController@incomeExpenseReport')->name('report.income.expense');
    Route::get('report/ticket/purchase', 'ReportController@ticketPurchaseReport')->name('report.ticket.purchase');
    Route::get('report/contestant/ranking', 'ReportController@contestantRanking')->name('report.contestant.ranking');
    Route::get('report/contestant/qualified', 'ReportController@qualifiedContestantRanking')->name('report.contestant.qualified');
    Route::get('report/contestant/eliminated', 'ReportController@eliminatedContestantRanking')->name('report.contestant.eliminated');
    Route::get('/eliminate/contestants', 'ReportController@eliminateContestants')->name('eliminate.contestants');

    Route::get('points/awaiting_candidates', 'PointController@awaitingCandidates')->name('points.awaiting_candidates');
    Route::get('points/create/{candidate_id?}', 'PointController@create')->name('points.create');
    Route::post('points/deletebyselection', 'PointController@deleteBySelection');
    Route::resource('points', 'PointController');
    Route::get('/contestants/{judgeId}/rated', 'PointController@getRatedContestants')->name('contestants.rated');
    Route::get('ambassador_points/awaiting_candidates', 'AmbassadorPointController@awaitingCandidates')->name('ambassador_points.awaiting_candidates');
    Route::get('ambassador_points/create/{candidate_id?}', 'AmbassadorPointController@create')->name('ambassador_points.create');
    Route::post('ambassador_points/deletebyselection', 'AmbassadorPointController@deleteBySelection');
    Route::resource('ambassador_points', 'AmbassadorPointController');


});


Route::get('/qr', 'QRController@show');
Route::get('/scan/{token}', 'QRController@scan');

/*
| Local-only fallback: serve uploaded media from production when the file
| does not exist locally. Only active when APP_ENV=local, so it has no effect
| on the live site. Set LOCAL_MEDIA_FALLBACK_URL in .env to override the host.
*/
if (app()->environment('local')) {
    Route::get('public/{path}', function ($path) {
        $localFile = base_path('public/' . $path);
        if (is_file($localFile)) {
            return response()->file($localFile);
        }
        $base = rtrim(env('LOCAL_MEDIA_FALLBACK_URL', 'https://mulemagc.com'), '/');
        return redirect($base . '/public/' . $path);
    })->where('path', '.*');
}

