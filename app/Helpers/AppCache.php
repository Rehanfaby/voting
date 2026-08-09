<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

class AppCache
{
    const GENERAL_SETTING = 'app.general_setting';
    const ALERT_PRODUCT_COUNT = 'app.alert_product_count';
    const PUBLIC_CONTESTANTS = 'app.public_contestants_v1';
    const PUBLIC_VOTE_COUNTS = 'app.public_vote_counts_v1';

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

        self::forgetPublicContestants();
    }

    public static function forgetPublicContestants()
    {
        Cache::forget(self::PUBLIC_CONTESTANTS);
        Cache::forget(self::PUBLIC_VOTE_COUNTS);
        Cache::forget('dash_contestant_counts_v1');
        Cache::forget('admin_dashboard_stats_v2');
        Cache::forget('module_help_candidates_gallery_v1');
    }
}
