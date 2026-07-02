<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

/**
 * Editable front-end site content (section toggles, casting calendar, prime
 * dates / countdown) stored as a JSON file under storage/app so it works on
 * shared hosting without database migrations. All reads fall back to sensible
 * defaults so the homepage never breaks if the file is missing.
 */
class SiteContent
{
    const FILE = 'site-content.json';

    /**
     * Top-level admin side-menu items that can be reordered.
     * Keys match each menu's collapse id (or 'dashboard' for the home link).
     */
    public static function menuKeys()
    {
        return [
            'dashboard'        => 'Dashboard',
            'product'          => 'Ticket / Product',
            'vote'             => 'Vote',
            'point'            => 'Judge Grading',
            'ambassador-point' => 'Ambassador Grading',
            'grading-setting'  => 'Grading',
            'coin'             => 'Coins',
            'expense'          => 'Expense',
            'people'           => 'People',
            'contestants'      => 'Contestants',
            'account'          => 'Accounting',
            'report'           => 'Reports',
            'setting'          => 'Settings',
        ];
    }

    /** Saved side-menu order (known keys only, missing ones appended). */
    public static function menuOrder()
    {
        $keys = array_keys(self::menuKeys());
        $saved = self::get('menu_order', []);
        if (!is_array($saved)) {
            $saved = [];
        }
        $order = array_values(array_intersect($saved, $keys));
        foreach ($keys as $k) {
            if (!in_array($k, $order, true)) {
                $order[] = $k;
            }
        }
        return $order;
    }

    /** Sections that can be toggled on/off from the admin. */
    public static function sectionKeys()
    {
        return [
            'popup'              => 'Homepage Popup (flyer / announcement)',
            'weekly_contestants' => 'Qualified Contestants for the Week',
            'our_winners'        => 'Our Winners (show at end of competition)',
            'judges'             => 'Judges Section',
            'casting'            => 'Provincial Casting Calendar',
            'finals'             => 'Finals Schedule (Primes)',
            'ambassadors'        => 'Meet Our Ambassadors',
            'partners'           => 'Partners / Sponsors',
            'most_voted'         => 'Most Voted Contestant of the Week',
            'top_five'           => 'Top Five Contestants',
        ];
    }

    public static function defaults()
    {
        return [
            'sections' => [
                'popup'              => false, // enable when there's a flyer/announcement to show
                'weekly_contestants' => true,
                'our_winners'        => false, // only turned on at the end of the competition
                'judges'             => true,
                'casting'            => true,
                'finals'             => true,
                'ambassadors'        => true,
                'partners'           => true,
                'most_voted'         => true,
                'top_five'           => true,
            ],
            // Uploaded popup image (relative to public/), null = fall back to default flyer.
            'popup_image' => null,
            // Countdown toggles (checkboxes in Settings > Site Content).
            'casting_countdown' => true,
            'primes_countdown'  => true,
            'most_voted_count'  => 1,
            'hero_image_en'     => null,
            'hero_image_fr'     => null,
            'casting_title'    => 'Provincial Casting Calendar 2026',
            'casting_subtitle' => "Here's a draft schedule for the Mulema Gospel casting by province.",
            'casting_rows' => [
                ['province' => 'Far North',   'venue' => 'Maroua',      'date' => 'March 21–22, 2026'],
                ['province' => 'North',       'venue' => 'Garoua',      'date' => 'March 28–29, 2026'],
                ['province' => 'Adamawa',     'venue' => 'Ngaoundéré',  'date' => 'April 4–5, 2026'],
                ['province' => 'East',        'venue' => 'Bertoua',     'date' => 'April 11–12, 2026'],
                ['province' => 'South West',  'venue' => 'Buea',        'date' => 'April 17–19, 2026'],
                ['province' => 'Littoral',    'venue' => 'Douala',      'date' => 'April 17–19, 2026'],
                ['province' => 'North West',  'venue' => 'Bamenda',     'date' => 'April 25–26, 2026'],
                ['province' => 'South',       'venue' => 'Sangmelima',  'date' => 'April 25–26, 2026'],
                ['province' => 'West',        'venue' => 'Bafoussam',   'date' => 'May 2–3, 2026'],
                ['province' => 'Centre',      'venue' => 'Yaoundé',     'date' => 'May 8–10, 2026'],
            ],
            'menu_order' => [
                'dashboard', 'product', 'vote', 'point', 'ambassador-point',
                'grading-setting', 'coin', 'expense', 'people', 'contestants',
                'account', 'report', 'setting',
            ],
            'primes_title' => 'Finals Schedule',
            // date is ISO (YYYY-MM-DD HH:MM) so the front-end can count down.
            'primes' => [
                ['label' => 'Prime 1',     'date' => '2026-08-08 19:00'],
                ['label' => 'Prime 2',     'date' => '2026-08-15 19:00'],
                ['label' => 'Prime 3',     'date' => '2026-08-22 19:00'],
                ['label' => 'Final Prime', 'date' => '2026-08-29 19:00'],
            ],
        ];
    }

    /** Full content array (file merged over defaults). */
    public static function all()
    {
        $data = [];
        try {
            if (Storage::disk('local')->exists(self::FILE)) {
                $decoded = json_decode(Storage::disk('local')->get(self::FILE), true);
                if (is_array($decoded)) {
                    $data = $decoded;
                }
            }
        } catch (\Throwable $e) {
            $data = [];
        }

        $defaults = self::defaults();
        $merged = array_replace_recursive($defaults, $data);

        // sections: a saved value should fully replace defaults so unchecking works
        if (isset($data['sections']) && is_array($data['sections'])) {
            $merged['sections'] = array_merge($defaults['sections'], $data['sections']);
        }
        // arrays of rows must replace, not merge by index
        foreach (['casting_rows', 'primes', 'menu_order'] as $listKey) {
            if (isset($data[$listKey]) && is_array($data[$listKey])) {
                $merged[$listKey] = array_values($data[$listKey]);
            }
        }
        return $merged;
    }

    public static function save(array $data)
    {
        Storage::disk('local')->put(self::FILE, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /** Is a front-end section enabled? */
    public static function enabled($key)
    {
        $all = self::all();
        return !empty($all['sections'][$key]);
    }

    public static function get($key, $default = null)
    {
        $all = self::all();
        return $all[$key] ?? $default;
    }

    /** Public URL of the popup image. Uses the admin-uploaded image when present,
     * otherwise falls back to the bundled default flyer.
     */
    public static function popupImageUrl()
    {
        $img = self::get('popup_image');
        if (!empty($img) && file_exists(public_path($img))) {
            return url($img);
        }
        return asset('public/img/flayer.jpeg');
    }

    /**
     * Hero banner for the homepage vote section. Uses admin-uploaded image when
     * set, otherwise the bundled locale-specific banner.
     */
    public static function heroImageUrl($locale = null)
    {
        $locale = $locale ?: \App::getLocale();
        $key = $locale === 'fr' ? 'hero_image_fr' : 'hero_image_en';
        $fallback = $locale === 'fr'
            ? 'public/frontend/images/top-banner2-fr.jpg'
            : 'public/frontend/images/top-banner2-en.jpg';
        $img = self::get($key);
        if (!empty($img) && file_exists(public_path($img))) {
            return url($img);
        }
        return url($fallback);
    }

    /** How many top-voted contestants to show in "Most Voted of the Week". */
    public static function mostVotedCount()
    {
        $n = (int) self::get('most_voted_count', 1);
        return max(1, min(20, $n));
    }

    /** The next upcoming prime (for the countdown), or null. */
    public static function nextPrime()
    {
        $primes = self::get('primes', []);
        $now = time();
        $upcoming = null;
        foreach ($primes as $p) {
            if (empty($p['date'])) {
                continue;
            }
            $ts = strtotime($p['date']);
            if ($ts && $ts >= $now && ($upcoming === null || $ts < strtotime($upcoming['date']))) {
                $upcoming = $p;
            }
        }
        return $upcoming;
    }
}
