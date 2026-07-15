<?php

namespace App\Services\Payments;

use App\MobileMoneyPayment;
use App\Services\Payments\Data\PaymentCallbackResult;
use App\Services\Payments\Data\PaymentStatusResult;
use App\Services\Payments\Enums\PaymentStatus;
use App\Services\Payments\Support\MobileMoneyPaymentLogger;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentStatusProcessor
{
    public function applyStatusResult(MobileMoneyPayment $payment, PaymentStatusResult $result)
    {
        return DB::transaction(function () use ($payment, $result) {
            $locked = MobileMoneyPayment::where('id', $payment->id)->lockForUpdate()->first();
            if (!$locked || $locked->isFinal()) {
                return $locked;
            }

            $locked->provider_status = $result->providerStatus;
            $locked->last_status_checked_at = Carbon::now();

            if ($result->providerReference) {
                $locked->provider_reference = $result->providerReference;
            }

            if ($result->status === PaymentStatus::COMPLETED) {
                $locked->status = PaymentStatus::COMPLETED;
                $locked->completed_at = Carbon::now();
            } elseif ($result->status === PaymentStatus::FAILED) {
                $locked->status = PaymentStatus::FAILED;
                $locked->failed_at = Carbon::now();
                $locked->failure_code = $result->failureCode;
                $locked->failure_message = $result->failureMessage;
            } else {
                $locked->status = $result->status;
            }

            $locked->save();

            return $locked;
        });
    }

    public function applyCallback(MobileMoneyPayment $payment, PaymentCallbackResult $callback)
    {
        return DB::transaction(function () use ($payment, $callback) {
            $locked = MobileMoneyPayment::where('id', $payment->id)->lockForUpdate()->first();
            if (!$locked) {
                return null;
            }

            if ($locked->isCompleted()) {
                return $locked;
            }

            if (!$this->callbackMatchesPayment($locked, $callback)) {
                $locked->callback_payload = json_encode($callback->rawPayload);
                $locked->save();

                MobileMoneyPaymentLogger::error('Payment callback mismatch flagged for review', [
                    'payment_id' => $locked->id,
                    'public_reference' => $locked->public_reference,
                    'provider' => $locked->provider,
                ]);

                return $locked;
            }

            $locked->callback_payload = json_encode($callback->rawPayload);
            $locked->provider_status = $callback->providerStatus;
            $locked->last_status_checked_at = Carbon::now();

            if ($callback->providerReference) {
                $locked->provider_reference = $callback->providerReference;
            }

            if ($callback->status === PaymentStatus::COMPLETED) {
                $locked->status = PaymentStatus::COMPLETED;
                $locked->completed_at = Carbon::now();
            } elseif ($callback->status === PaymentStatus::FAILED) {
                $locked->status = PaymentStatus::FAILED;
                $locked->failed_at = Carbon::now();
                $locked->failure_code = $callback->failureCode;
                $locked->failure_message = $callback->failureMessage;
            } else {
                $locked->status = $callback->status ?: PaymentStatus::PROCESSING;
            }

            $locked->save();

            return $locked;
        });
    }

    protected function callbackMatchesPayment(MobileMoneyPayment $payment, PaymentCallbackResult $callback)
    {
        if ($callback->providerTransactionId
            && $payment->provider_transaction_id
            && $callback->providerTransactionId !== $payment->provider_transaction_id) {
            return false;
        }

        if ($callback->amount !== null && (int) $callback->amount !== (int) $payment->amount) {
            return false;
        }

        if ($callback->currency && strtoupper($callback->currency) !== strtoupper($payment->currency)) {
            return false;
        }

        if ($callback->mobileNetwork) {
            $allowed = ['MTN_MOMO_CMR', 'ORANGE_CMR'];
            if (!in_array(strtoupper($callback->mobileNetwork), $allowed, true)) {
                return false;
            }
        }

        return true;
    }
}
