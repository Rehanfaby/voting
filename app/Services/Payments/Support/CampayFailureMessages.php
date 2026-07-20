<?php

namespace App\Services\Payments\Support;

class CampayFailureMessages
{
    public static function customerMessage($failureCode)
    {
        $code = strtoupper((string) $failureCode);

        switch ($code) {
            case 'LOW_BALANCE_OR_PAYEE_LIMIT_REACHED_OR_NOT_ALLOWED':
                return trans('file.MoMo low balance or limit reached');

            case 'PAYMENT_NOT_APPROVED':
            case 'EXPIRED':
            case 'TIMEOUT':
                return trans('file.MoMo payment not approved or expired');

            case 'UNSUPPORTED_CARRIER':
            case 'ER102':
                return trans('file.MoMo unsupported carrier');

            case 'INVALID_PHONE_NUMBER':
            case 'PAYER_NOT_FOUND':
                return trans('file.MoMo invalid phone number');

            default:
                return trans('file.Payment failed please try again');
        }
    }
}
