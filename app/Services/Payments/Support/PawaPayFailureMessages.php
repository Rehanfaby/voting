<?php

namespace App\Services\Payments\Support;

class PawaPayFailureMessages
{
    public static function customerMessage($failureCode)
    {
        $code = strtoupper((string) $failureCode);

        switch ($code) {
            case 'PAYMENT_NOT_APPROVED':
                return 'The payment was not approved on the phone.';

            case 'INSUFFICIENT_BALANCE':
                return 'The mobile money account has insufficient balance.';

            case 'PAYER_NOT_FOUND':
                return 'The mobile money account could not be found.';

            case 'PAYER_LIMIT_REACHED':
            case 'WALLET_LIMIT_REACHED':
                return 'The mobile money wallet has reached a transaction limit.';

            case 'AUTHENTICATION_ERROR':
            case 'AUTHORISATION_ERROR':
                return 'The payment service is temporarily unavailable. Please try again shortly.';

            case 'UNKNOWN_ERROR':
            case 'UNSPECIFIED_FAILURE':
                return 'The payment could not be completed. Please try again or contact support.';

            default:
                return 'The payment could not be completed.';
        }
    }
}
