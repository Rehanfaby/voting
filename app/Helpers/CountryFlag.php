<?php

namespace App\Helpers;

/**
 * Country list + flag URLs for judges and other profiles.
 * Stores ISO 3166-1 alpha-2 codes (e.g. CM, GB) in the database.
 */
class CountryFlag
{
    /** @return array<string,string> code => label */
    public static function options()
    {
        return [
            'CM' => 'Cameroon',
            'NG' => 'Nigeria',
            'GH' => 'Ghana',
            'CI' => "Côte d'Ivoire",
            'SN' => 'Senegal',
            'FR' => 'France',
            'GB' => 'United Kingdom',
            'US' => 'United States',
            'CA' => 'Canada',
            'DE' => 'Germany',
            'BE' => 'Belgium',
            'CH' => 'Switzerland',
            'ZA' => 'South Africa',
            'KE' => 'Kenya',
            'CD' => 'DR Congo',
            'GA' => 'Gabon',
            'CG' => 'Congo',
            'CF' => 'Central African Republic',
            'TD' => 'Chad',
            'GQ' => 'Equatorial Guinea',
        ];
    }

    /** Normalize stored value to a 2-letter ISO code, or null. */
    public static function code($value)
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }
        if (strlen($value) === 2 && ctype_alpha($value)) {
            return strtoupper($value);
        }
        $map = array_change_key_case(array_flip(self::options()), CASE_LOWER);
        $key = strtolower($value);
        if (isset($map[$key])) {
            return strtoupper($map[$key]);
        }
        foreach (self::options() as $code => $label) {
            if (strcasecmp($label, $value) === 0) {
                return $code;
            }
        }
        return null;
    }

    public static function label($value)
    {
        $code = self::code($value);
        if (!$code) {
            return $value ?: '';
        }
        $opts = self::options();
        return $opts[$code] ?? $code;
    }

    /** Small PNG flag from flagcdn (works on shared hosting, no extra assets). */
    public static function url($value, $width = 24)
    {
        $code = self::code($value);
        if (!$code) {
            return null;
        }
        $width = max(16, min(80, (int) $width));
        return 'https://flagcdn.com/w' . $width . '/' . strtolower($code) . '.png';
    }
}
