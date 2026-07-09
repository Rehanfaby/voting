<?php

namespace App\Helpers;

class PhoneHelper
{
    /** Developer number used for payment simulation (Papa Rolly). */
    public const DEVELOPER_LOCAL = '675321739';

    public static function developerE164()
    {
        return '+237' . self::DEVELOPER_LOCAL;
    }

    /** Live Campay MoMo token (config-backed so it survives config:cache). */
    public static function momoToken()
    {
        return config('services.momo.token') ?: getenv('MOMO_TOKEN');
    }

    /** Whether MoMo payments run in simulation mode (no live Campay prompt). */
    public static function paymentSimulate()
    {
        $flag = config('services.momo.simulate');
        if ($flag === null || $flag === '') {
            $flag = env('PAYMENT_SIMULATE');
        }
        if ($flag !== null && $flag !== '') {
            return filter_var($flag, FILTER_VALIDATE_BOOLEAN);
        }

        return empty(self::momoToken());
    }

    /** Default local digits for payment phone fields (simulation or empty user). */
    public static function defaultPaymentLocal($localDigits = '')
    {
        if (self::paymentSimulate()) {
            return self::DEVELOPER_LOCAL;
        }
        $digits = preg_replace('/\D/', '', (string) $localDigits);
        if (strpos($digits, '237') === 0) {
            $digits = substr($digits, 3);
        }

        return ltrim($digits, '0');
    }

    /** Format local CM mobile for display: +237 6 75 32 17 39 */
    public static function formatCameroonDisplay($localDigits)
    {
        $d = preg_replace('/\D/', '', (string) $localDigits);
        if (strpos($d, '237') === 0) {
            $d = substr($d, 3);
        }
        $d = ltrim($d, '0');
        if ($d === '') {
            return '+237 ';
        }
        $chunks = [substr($d, 0, 1)];
        $rest = substr($d, 1);
        while ($rest !== '') {
            $chunks[] = substr($rest, 0, 2);
            $rest = substr($rest, 2);
        }

        return '+237 ' . implode(' ', $chunks);
    }

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

    /** E.164 (+237…) for UltraMsg and other WhatsApp APIs. */
    public static function forUltraMsg($phone)
    {
        return self::cameroon($phone);
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
