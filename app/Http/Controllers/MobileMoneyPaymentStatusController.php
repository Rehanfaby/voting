<?php

namespace App\Http\Controllers;

use App\MobileMoneyPayment;
use App\Services\Payments\Enums\PaymentStatus;
use Illuminate\Http\Request;

class MobileMoneyPaymentStatusController extends Controller
{
    public function show($reference)
    {
        $payment = MobileMoneyPayment::where('public_reference', $reference)->first();
        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found.',
            ], 404);
        }

        $message = 'Waiting for payment confirmation.';
        if ($payment->status === PaymentStatus::COMPLETED) {
            $message = 'Payment completed successfully.';
        } elseif ($payment->status === PaymentStatus::FAILED) {
            $message = 'Payment failed.';
        }

        return response()->json([
            'success' => true,
            'transaction_reference' => $payment->public_reference,
            'status' => $payment->status,
            'message' => $message,
            'provider' => $payment->provider,
            'provider_status' => $payment->provider_status,
        ]);
    }
}
