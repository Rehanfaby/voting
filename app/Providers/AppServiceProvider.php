<?php

namespace App\Providers;

use App\Helpers\AppCache;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use DB;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function boot()
    {
        /*if( (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) {
            URL::forceScheme('https');
        }*/
        //setting language
        if(isset($_COOKIE['language'])) {
            \App::setLocale($_COOKIE['language']);
        } else {
            \App::setLocale('en');
        }
        Schema::defaultStringLength(191);

        try {
            $this->shareApplicationData();
        } catch (\Exception $e) {
            View::share('alert_product', 0);
        }
    }

    protected function shareApplicationData()
    {
        $general_setting = Cache::remember(AppCache::GENERAL_SETTING, 3600, function () {
            return DB::table('general_settings')->latest()->first();
        });

        if ($general_setting) {
            $currency = Cache::remember(AppCache::currencyKey($general_setting->currency), 3600, function () use ($general_setting) {
                return \App\Currency::find($general_setting->currency) ?? '';
            });

            View::share('general_setting', $general_setting);
            View::share('currency', $currency);
            config([
                'staff_access' => $general_setting->staff_access,
                'date_format' => $general_setting->date_format,
                'currency' => $currency->code ?? '',
                'currency_position' => $general_setting->currency_position,
            ]);
        }

        $alert_product = Cache::remember(AppCache::ALERT_PRODUCT_COUNT, 300, function () {
            return DB::table('products')->where('is_active', true)->whereColumn('alert_quantity', '>', 'qty')->count();
        });
        View::share('alert_product', $alert_product);
    }
}
