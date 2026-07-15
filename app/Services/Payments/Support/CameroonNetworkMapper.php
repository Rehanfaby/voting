<?php

namespace App\Services\Payments\Support;

use Illuminate\Validation\ValidationException;

class CameroonNetworkMapper
{
    public static function map($network)
    {
        $normalized = strtoupper(trim(preg_replace('/\s+/', ' ', (string) $network)));

        switch ($normalized) {
            case 'MTN':
            case 'MTN MOMO':
            case 'MTN MOBILE MONEY':
            case 'MTN_MOMO_CMR':
            case 'MOMO':
                return 'MTN_MOMO_CMR';

            case 'ORANGE':
            case 'ORANGE MONEY':
            case 'ORANGE_CMR':
            case 'OM':
                return 'ORANGE_CMR';

            default:
                throw ValidationException::withMessages([
                    'network' => 'Unsupported Cameroon mobile money provider.',
                ]);
        }
    }

    public static function fromPaymentMethod($paymentMethod)
    {
        $method = strtolower(trim((string) $paymentMethod));

        if ($method === 'om' || $method === 'orange') {
            return 'ORANGE_CMR';
        }

        return 'MTN_MOMO_CMR';
    }
}
