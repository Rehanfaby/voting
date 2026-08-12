<?php

namespace App\Helpers;

use App\User;

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

        // Some records store multiple numbers joined with / , ; |
        $parts = preg_split('/[\/|,;]+/', (string) $phone);
        foreach ($parts as $part) {
            $normalized = self::normalizeCameroonPart($part);
            if ($normalized) {
                return $normalized;
            }
        }

        return null;
    }

    private static function normalizeCameroonPart($phone)
    {
        $phone = preg_replace('/[\s\-\.\(\)]/', '', (string) $phone);
        if ($phone === '') {
            return null;
        }

        if (strpos($phone, '+') === 0) {
            $digits = preg_replace('/\D/', '', $phone);
            return $digits !== '' ? '+' . $digits : null;
        }
        if (strpos($phone, '00') === 0) {
            $digits = preg_replace('/\D/', '', substr($phone, 2));
            return $digits !== '' ? '+' . $digits : null;
        }
        if (strpos($phone, '237') === 0) {
            $digits = preg_replace('/\D/', '', $phone);
            return $digits !== '' ? '+' . $digits : null;
        }

        $digits = preg_replace('/\D/', '', $phone);
        if ($digits === '') {
            return null;
        }

        // Cameroon local mobile: 9 digits starting with 6 (optionally with a leading 0).
        $local = ltrim($digits, '0');
        if (strlen($local) === 9 && strpos($local, '6') === 0) {
            return '+237' . $local;
        }

        // Already includes country code (e.g. 2376… or other intl) — do not force +237.
        if (strlen($digits) >= 10 && strlen($digits) <= 15) {
            return '+' . $digits;
        }

        // Short local remainder — assume Cameroon.
        return '+237' . $local;
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

    /**
     * True when UltraMsg/WhatsApp can actually deliver to this number.
     * Cameroon mobile: +237 and 9 digits starting with 6.
     */
    public static function isValidWhatsApp($phone)
    {
        $e164 = self::cameroon($phone);
        if (!$e164) {
            $e164 = self::e164($phone);
        }
        if (!$e164) {
            return false;
        }
        $digits = preg_replace('/\D/', '', $e164);
        $len = strlen($digits);
        if ($len < 10 || $len > 15) {
            return false;
        }
        if (strpos($digits, '237') === 0) {
            $local = substr($digits, 3);

            return strlen($local) === 9 && strpos($local, '6') === 0;
        }
        // Doubled country code, e.g. 2252250171730220
        if (preg_match('/^(225|234|233|250|256|254)\1/', $digits)) {
            return false;
        }

        return true;
    }

    /**
     * Stable identity for one person: digits of the E.164 number
     * (e.g. 237620771801). Collapses 0620…, 237620… and +237620….
     */
    public static function identityKey($phone)
    {
        $e164 = self::cameroon($phone);
        if (!$e164) {
            return null;
        }
        $digits = preg_replace('/\D/', '', $e164);

        return $digits !== '' ? $digits : null;
    }

    /** Find an existing user by any stored format of the same phone. */
    public static function findUserByPhone($phone, $roleId = 3)
    {
        $raw = trim((string) $phone);
        if ($raw === '') {
            return null;
        }

        $normalized = self::cameroon($raw);
        $key = self::identityKey($raw);
        $query = User::query()->where('is_deleted', false);
        if ($roleId !== null) {
            $query->where('role_id', $roleId);
        }

        $exact = (clone $query)->where(function ($q) use ($raw, $normalized) {
            $q->where('phone', $raw);
            if ($normalized && $normalized !== $raw) {
                $q->orWhere('phone', $normalized)->orWhere('whatsapp_number', $normalized);
            }
        })->orderBy('id')->first();
        if ($exact) {
            return $exact;
        }

        if (!$key || strlen($key) < 8) {
            return null;
        }
        $local = substr($key, -9);
        $candidates = (clone $query)->where(function ($q) use ($local) {
            $q->where('phone', 'like', '%' . $local)
                ->orWhere('whatsapp_number', 'like', '%' . $local);
        })->orderBy('id')->limit(25)->get();

        foreach ($candidates as $user) {
            $userKey = self::identityKey($user->phone) ?: self::identityKey($user->whatsapp_number);
            if ($userKey === $key) {
                return $user;
            }
        }

        return null;
    }

    /**
     * Normalise a full international number (already carrying its country dial
     * code) to E.164, e.g. "+237675321739". Accepts values with +, 00 or bare
     * digits and trusts the caller to include the country code.
     */
    public static function e164($value)
    {
        if ($value === null || $value === '') {
            return null;
        }
        $raw = trim((string) $value);
        $digits = preg_replace('/\D/', '', $raw);
        if (strpos($raw, '00') === 0) {
            $digits = ltrim($digits, '0');
        }
        if ($digits === '') {
            return null;
        }

        return '+' . $digits;
    }

    /**
     * Split a stored number into [dialCode, localDigits] using the known
     * country list (longest matching dial code wins). Falls back to the given
     * default dial code (Cameroon) when nothing matches.
     */
    public static function splitIntl($value, $defaultDial = '237')
    {
        $raw = trim((string) $value);
        $digits = preg_replace('/\D/', '', $raw);
        if (strpos($raw, '00') === 0) {
            $digits = ltrim($digits, '0');
        }
        if ($digits === '') {
            return [$defaultDial, ''];
        }

        // Only treat leading digits as a country code when the number is clearly
        // international (prefixed with + or 00). Otherwise assume the default.
        $isIntl = strpos($raw, '+') === 0 || strpos($raw, '00') === 0 || strpos($digits, $defaultDial) === 0;
        if (!$isIntl) {
            return [$defaultDial, ltrim($digits, '0')];
        }

        $dials = array_column(self::countries(), 'dial');
        usort($dials, function ($a, $b) {
            return strlen($b) <=> strlen($a);
        });
        foreach ($dials as $dial) {
            if (strpos($digits, $dial) === 0) {
                return [$dial, ltrim(substr($digits, strlen($dial)), '0')];
            }
        }

        return [$defaultDial, ltrim($digits, '0')];
    }

    /**
     * Country dial codes for the international phone selector. Cameroon is the
     * default; the rest let voters use any country code.
     *
     * @return array<int, array{iso:string,name:string,dial:string,flag:string}>
     */
    public static function countries()
    {
        return [
            ['iso' => 'CM', 'name' => 'Cameroon', 'dial' => '237', 'flag' => '🇨🇲'],
            ['iso' => 'NG', 'name' => 'Nigeria', 'dial' => '234', 'flag' => '🇳🇬'],
            ['iso' => 'GH', 'name' => 'Ghana', 'dial' => '233', 'flag' => '🇬🇭'],
            ['iso' => 'CI', 'name' => "Côte d'Ivoire", 'dial' => '225', 'flag' => '🇨🇮'],
            ['iso' => 'SN', 'name' => 'Senegal', 'dial' => '221', 'flag' => '🇸🇳'],
            ['iso' => 'GA', 'name' => 'Gabon', 'dial' => '241', 'flag' => '🇬🇦'],
            ['iso' => 'CG', 'name' => 'Congo (Rep.)', 'dial' => '242', 'flag' => '🇨🇬'],
            ['iso' => 'CD', 'name' => 'Congo (DRC)', 'dial' => '243', 'flag' => '🇨🇩'],
            ['iso' => 'TD', 'name' => 'Chad', 'dial' => '235', 'flag' => '🇹🇩'],
            ['iso' => 'CF', 'name' => 'Central African Rep.', 'dial' => '236', 'flag' => '🇨🇫'],
            ['iso' => 'GQ', 'name' => 'Equatorial Guinea', 'dial' => '240', 'flag' => '🇬🇶'],
            ['iso' => 'BJ', 'name' => 'Benin', 'dial' => '229', 'flag' => '🇧🇯'],
            ['iso' => 'TG', 'name' => 'Togo', 'dial' => '228', 'flag' => '🇹🇬'],
            ['iso' => 'BF', 'name' => 'Burkina Faso', 'dial' => '226', 'flag' => '🇧🇫'],
            ['iso' => 'ML', 'name' => 'Mali', 'dial' => '223', 'flag' => '🇲🇱'],
            ['iso' => 'NE', 'name' => 'Niger', 'dial' => '227', 'flag' => '🇳🇪'],
            ['iso' => 'GN', 'name' => 'Guinea', 'dial' => '224', 'flag' => '🇬🇳'],
            ['iso' => 'RW', 'name' => 'Rwanda', 'dial' => '250', 'flag' => '🇷🇼'],
            ['iso' => 'KE', 'name' => 'Kenya', 'dial' => '254', 'flag' => '🇰🇪'],
            ['iso' => 'UG', 'name' => 'Uganda', 'dial' => '256', 'flag' => '🇺🇬'],
            ['iso' => 'TZ', 'name' => 'Tanzania', 'dial' => '255', 'flag' => '🇹🇿'],
            ['iso' => 'ZA', 'name' => 'South Africa', 'dial' => '27', 'flag' => '🇿🇦'],
            ['iso' => 'ZM', 'name' => 'Zambia', 'dial' => '260', 'flag' => '🇿🇲'],
            ['iso' => 'ZW', 'name' => 'Zimbabwe', 'dial' => '263', 'flag' => '🇿🇼'],
            ['iso' => 'AO', 'name' => 'Angola', 'dial' => '244', 'flag' => '🇦🇴'],
            ['iso' => 'MZ', 'name' => 'Mozambique', 'dial' => '258', 'flag' => '🇲🇿'],
            ['iso' => 'ET', 'name' => 'Ethiopia', 'dial' => '251', 'flag' => '🇪🇹'],
            ['iso' => 'EG', 'name' => 'Egypt', 'dial' => '20', 'flag' => '🇪🇬'],
            ['iso' => 'MA', 'name' => 'Morocco', 'dial' => '212', 'flag' => '🇲🇦'],
            ['iso' => 'DZ', 'name' => 'Algeria', 'dial' => '213', 'flag' => '🇩🇿'],
            ['iso' => 'TN', 'name' => 'Tunisia', 'dial' => '216', 'flag' => '🇹🇳'],
            ['iso' => 'FR', 'name' => 'France', 'dial' => '33', 'flag' => '🇫🇷'],
            ['iso' => 'BE', 'name' => 'Belgium', 'dial' => '32', 'flag' => '🇧🇪'],
            ['iso' => 'GB', 'name' => 'United Kingdom', 'dial' => '44', 'flag' => '🇬🇧'],
            ['iso' => 'US', 'name' => 'United States', 'dial' => '1', 'flag' => '🇺🇸'],
            ['iso' => 'CA', 'name' => 'Canada', 'dial' => '1', 'flag' => '🇨🇦'],
            ['iso' => 'DE', 'name' => 'Germany', 'dial' => '49', 'flag' => '🇩🇪'],
            ['iso' => 'IT', 'name' => 'Italy', 'dial' => '39', 'flag' => '🇮🇹'],
            ['iso' => 'ES', 'name' => 'Spain', 'dial' => '34', 'flag' => '🇪🇸'],
            ['iso' => 'NL', 'name' => 'Netherlands', 'dial' => '31', 'flag' => '🇳🇱'],
            ['iso' => 'CH', 'name' => 'Switzerland', 'dial' => '41', 'flag' => '🇨🇭'],
            ['iso' => 'PT', 'name' => 'Portugal', 'dial' => '351', 'flag' => '🇵🇹'],
            ['iso' => 'SE', 'name' => 'Sweden', 'dial' => '46', 'flag' => '🇸🇪'],
            ['iso' => 'NO', 'name' => 'Norway', 'dial' => '47', 'flag' => '🇳🇴'],
            ['iso' => 'AE', 'name' => 'United Arab Emirates', 'dial' => '971', 'flag' => '🇦🇪'],
            ['iso' => 'SA', 'name' => 'Saudi Arabia', 'dial' => '966', 'flag' => '🇸🇦'],
            ['iso' => 'QA', 'name' => 'Qatar', 'dial' => '974', 'flag' => '🇶🇦'],
            ['iso' => 'TR', 'name' => 'Turkey', 'dial' => '90', 'flag' => '🇹🇷'],
            ['iso' => 'IN', 'name' => 'India', 'dial' => '91', 'flag' => '🇮🇳'],
            ['iso' => 'CN', 'name' => 'China', 'dial' => '86', 'flag' => '🇨🇳'],
            ['iso' => 'BR', 'name' => 'Brazil', 'dial' => '55', 'flag' => '🇧🇷'],
            ['iso' => 'AU', 'name' => 'Australia', 'dial' => '61', 'flag' => '🇦🇺'],
        ];
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
