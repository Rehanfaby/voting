<?php

namespace App\Services\Payments\Support;

use Illuminate\Validation\ValidationException;

class CameroonPhoneNormalizer
{
    public static function normalize($input)
    {
        $digits = preg_replace('/\D/', '', (string) $input);

        if ($digits === '') {
            throw ValidationException::withMessages([
                'phone' => 'Please enter a valid Cameroon mobile money number.',
            ]);
        }

        if (strpos($digits, '00') === 0) {
            $digits = substr($digits, 2);
        }

        if (strlen($digits) === 9 && $digits[0] === '6') {
            $digits = '237' . $digits;
        } elseif (strlen($digits) === 10 && $digits[0] === '0' && $digits[1] === '6') {
            $digits = '237' . substr($digits, 1);
        } elseif (strlen($digits) === 12 && strpos($digits, '237') === 0) {
            // already international without plus
        } else {
            throw ValidationException::withMessages([
                'phone' => 'Please enter a valid Cameroon mobile money number.',
            ]);
        }

        if (!preg_match('/^2376\d{8}$/', $digits)) {
            throw ValidationException::withMessages([
                'phone' => 'Please enter a valid Cameroon mobile money number.',
            ]);
        }

        return $digits;
    }

    public static function mask($digits)
    {
        $digits = preg_replace('/\D/', '', (string) $digits);
        if (strlen($digits) < 7) {
            return '***';
        }

        return substr($digits, 0, 5) . '****' . substr($digits, -3);
    }
}
