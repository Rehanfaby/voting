<?php

namespace App\Helpers;

class PhoneHelper
{
    /** Strip formatting and ensure a Cameroon (+237) E.164 number. */
    public static function cameroon($phone)
    {
        if ($phone === null || $phone === '') {
            return null;
        }

        $phone = preg_replace('/[\s\-\.\(\)]/', '', (string) $phone);

        if (strpos($phone, '+') === 0) {
            return $phone;
        }
        if (strpos($phone, '00') === 0) {
            return '+' . substr($phone, 2);
        }
        if (strpos($phone, '237') === 0) {
            return '+' . $phone;
        }

        return '+237' . ltrim($phone, '0');
    }

    /** Build +237 number from a local digits-only input (no country code). */
    public static function fromLocalDigits($local)
    {
        if ($local === null || $local === '') {
            return null;
        }
        $digits = preg_replace('/\D/', '', (string) $local);
        if ($digits === '') {
            return null;
        }
        if (strpos($digits, '237') === 0) {
            $digits = substr($digits, 3);
        }
        $digits = ltrim($digits, '0');

        return '+237' . $digits;
    }

    /** True when a stored "name" is really just a phone number. */
    public static function looksLikePhone($value)
    {
        if ($value === null || $value === '') {
            return true;
        }

        $digits = preg_replace('/\D/', '', (string) $value);

        return strlen($digits) >= 9 && preg_match('/^[\+\d\s\-\(\)\.]+$/', trim((string) $value));
    }
}
