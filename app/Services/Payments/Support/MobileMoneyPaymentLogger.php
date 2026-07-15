<?php

namespace App\Services\Payments\Support;

use Illuminate\Support\Facades\Log;

class MobileMoneyPaymentLogger
{
    public static function info($message, array $context = [])
    {
        Log::info($message, self::sanitize($context));
    }

    public static function warning($message, array $context = [])
    {
        Log::warning($message, self::sanitize($context));
    }

    public static function error($message, array $context = [])
    {
        Log::error($message, self::sanitize($context));
    }

    protected static function sanitize(array $context)
    {
        $blocked = [
            'api_token',
            'token',
            'authorization',
            'password',
            'secret',
        ];

        foreach ($context as $key => $value) {
            $lower = strtolower((string) $key);
            foreach ($blocked as $needle) {
                if (strpos($lower, $needle) !== false) {
                    $context[$key] = '[redacted]';
                    break;
                }
            }

            if ($lower === 'phone_number' || $lower === 'phone') {
                $context[$key] = CameroonPhoneNormalizer::mask($value);
            }
        }

        return $context;
    }
}
