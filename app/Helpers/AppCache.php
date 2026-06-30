<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

class AppCache
{
    const GENERAL_SETTING = 'app.general_setting';
    const ALERT_PRODUCT_COUNT = 'app.alert_product_count';

    public static function currencyKey($currencyId)
    {
        return 'app.currency.' . $currencyId;
    }

    public static function forgetSharedData()
    {
        $general_setting = Cache::get(self::GENERAL_SETTING);

        Cache::forget(self::GENERAL_SETTING);
        Cache::forget(self::ALERT_PRODUCT_COUNT);

        if ($general_setting && isset($general_setting->currency)) {
            Cache::forget(self::currencyKey($general_setting->currency));
        }
    }
}
