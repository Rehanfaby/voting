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

Route::post('/payments/pawapay/checkouts/callback', 'PawaPayCheckoutCallbackController@handle')
    ->name('payments.pawapay.checkouts.callback');

Route::post('/payments/pawapay/deposits/callback', 'PawaPayDepositCallbackController@handle')
    ->name('payments.pawapay.deposits.callback');

Route::post('/payments/pawapay/payouts/callback', 'PawaPayPayoutCallbackController@handle')
    ->name('payments.pawapay.payouts.callback');

Route::post('/payments/pawapay/refunds/callback', 'PawaPayRefundCallbackController@handle')
    ->name('payments.pawapay.refunds.callback');

// Legacy alias — same handler as deposits/callback
Route::post('/payments/pawapay/callback', 'PawaPayDepositCallbackController@handle')
    ->name('payments.pawapay.callback');

Route::get('/payments/{reference}/status', 'MobileMoneyPaymentStatusController@show')
    ->name('payments.status');

