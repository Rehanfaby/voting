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
            'about-us'         => 'About Us',
            'site-content'     => 'Site Content',
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
    /** Homepage section toggles (popup is managed in its own card). */
    public static function homepageSectionKeys()
    {
        return array_diff_key(self::sectionKeys(), ['popup' => true]);
    }

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
            // Optional link opened when the popup image is clicked.
            'popup_link' => '',
            // Optional countdown shown on the popup (closes with the popup).
            'popup_countdown' => false,
            'popup_countdown_at' => null,
            'popup_countdown_label' => '',
            // Homepage / site gallery images: [ ['image' => 'uploads/gallery/x.jpg', 'caption' => ''], ... ]
            'gallery' => [],
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
                'account', 'report', 'about-us', 'site-content', 'setting',
            ],
            'primes_title' => 'Finals Schedule',
            // date is ISO (YYYY-MM-DD HH:MM) so the front-end can count down.
            'primes' => [
                ['label' => 'Prime 1',     'date' => '2026-08-08 19:00'],
                ['label' => 'Prime 2',     'date' => '2026-08-15 19:00'],
                ['label' => 'Prime 3',     'date' => '2026-08-22 19:00'],
                ['label' => 'Final Prime', 'date' => '2026-08-29 19:00'],
            ],
            'about_page' => [
                'hero_subtitle' => '',
                'mission_title' => '',
                'mission_p1' => '',
                'mission_p2' => '',
                'mission_p3' => '',
                'heart_badge' => '',
                'intro_title' => '',
                'intro_text' => '',
                'image' => null,
                'regions' => 'Adamaoua, Centre, East, Far North, Littoral, North, North-West, West, South, South-West',
                'values_heading' => '',
                'values' => 'Excellence, Integrity, Spirit-led worship, Purity, Innovation, Performance',
                'leaders_heading' => '',
                'leaders_subheading' => '',
                'winners_year' => '2025',
                'winners_heading' => '',
                'facebook' => 'https://www.facebook.com/mulemagospeltalent/',
                'instagram' => 'https://www.instagram.com/mulemagospeltalent',
                'tiktok' => 'https://www.tiktok.com/@mulemagospel',
                'socials_heading' => '',
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
        foreach (['casting_rows', 'primes', 'menu_order', 'gallery'] as $listKey) {
            if (isset($data[$listKey]) && is_array($data[$listKey])) {
                $merged[$listKey] = array_values($data[$listKey]);
            }
        }
        if (isset($data['about_page']) && is_array($data['about_page'])) {
            $merged['about_page'] = array_merge($defaults['about_page'] ?? [], $data['about_page']);
        }
        if (isset($merged['primes']) && is_array($merged['primes'])) {
            $merged['primes'] = self::sortedPrimes($merged['primes']);
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
        if (!empty($img) && self::uploadExists($img)) {
            return self::publicUploadUrl($img);
        }
        return asset('public/img/flayer.jpeg');
    }

    /** Whether an admin-uploaded popup image exists (not the default flyer). */
    public static function hasCustomPopup()
    {
        $img = self::get('popup_image');
        return !empty($img) && self::uploadExists($img);
    }

    /** Optional link opened when the popup image is clicked (empty = not clickable). */
    public static function popupLink()
    {
        $link = trim((string) self::get('popup_link', ''));
        return $link !== '' ? $link : null;
    }

    /** Popup countdown target as an ISO string, or null when disabled/unset. */
    public static function popupCountdownIso()
    {
        if (empty(self::get('popup_countdown'))) {
            return null;
        }
        $dt = self::parseEventDate(self::get('popup_countdown_at'));
        if (!$dt || $dt->lte(\Carbon\Carbon::now())) {
            return null;
        }
        return $dt->toIso8601String();
    }

    /** Site gallery items: array of ['url' => ..., 'caption' => ...]. */
    public static function galleryItems()
    {
        $items = self::get('gallery', []);
        if (!is_array($items)) {
            return [];
        }
        $out = [];
        foreach ($items as $item) {
            if (is_string($item)) {
                $item = ['image' => $item, 'caption' => ''];
            }
            if (!is_array($item) || empty($item['image']) || !self::uploadExists($item['image'])) {
                continue;
            }
            $out[] = [
                'url' => self::publicUploadUrl($item['image']),
                'caption' => trim((string) ($item['caption'] ?? '')),
            ];
        }
        return $out;
    }

    /** An About-page social link (facebook/instagram/tiktok), or null. */
    public static function aboutSocial($key)
    {
        $page = self::get('about_page', []);
        if (!is_array($page)) {
            return null;
        }
        $val = trim((string) ($page[$key] ?? ''));
        return $val !== '' ? $val : null;
    }

    /** Build a public URL for files stored under /public on this install. */
    public static function publicUploadUrl($relativePath)
    {
        if (empty($relativePath)) {
            return '';
        }
        $path = ltrim(str_replace('\\', '/', $relativePath), '/');
        if (strpos($path, 'public/') === 0) {
            return url($path);
        }

        return url('public/' . $path);
    }

    public static function uploadExists($relativePath)
    {
        if (empty($relativePath)) {
            return false;
        }
        $path = ltrim(str_replace('\\', '/', $relativePath), '/');
        if (file_exists(public_path($path))) {
            return true;
        }
        if (strpos($path, 'public/') !== 0 && file_exists(public_path('public/' . $path))) {
            return true;
        }

        return false;
    }

    /** Sort prime rows earliest-first by date/time. */
    /** Parse a prime/event datetime stored from admin (ISO or datetime-local). */
    public static function parseEventDate($date)
    {
        if (empty($date)) {
            return null;
        }
        try {
            return \Carbon\Carbon::parse(trim(str_replace('T', ' ', (string) $date)));
        } catch (\Throwable $e) {
            return null;
        }
    }

    public static function isRowEnabled(array $row)
    {
        return !isset($row['enabled']) || $row['enabled'] !== false;
    }

    /** Disable primes whose date/time has passed (persists to JSON). */
    public static function expirePastEvents()
    {
        try {
            if (!Storage::disk('local')->exists(self::FILE)) {
                return;
            }
            $data = json_decode(Storage::disk('local')->get(self::FILE), true);
            if (!is_array($data)) {
                return;
            }
            $changed = false;
            $now = \Carbon\Carbon::now();
            if (!empty($data['primes']) && is_array($data['primes'])) {
                foreach ($data['primes'] as &$prime) {
                    if (isset($prime['enabled']) && $prime['enabled'] === false) {
                        continue;
                    }
                    $dt = self::parseEventDate($prime['date'] ?? '');
                    if ($dt && $dt->lt($now)) {
                        $prime['enabled'] = false;
                        $changed = true;
                    }
                }
                unset($prime);
            }
            if ($changed) {
                Storage::disk('local')->put(self::FILE, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }
        } catch (\Throwable $e) {
            // never break the site
        }
    }

    public static function sortedPrimes(array $primes)
    {
        usort($primes, function ($a, $b) {
            $ta = self::parseEventDate($a['date'] ?? '');
            $tb = self::parseEventDate($b['date'] ?? '');
            $tsA = $ta ? $ta->timestamp : PHP_INT_MAX;
            $tsB = $tb ? $tb->timestamp : PHP_INT_MAX;
            if ($tsA === $tsB) {
                return 0;
            }

            return $tsA <=> $tsB;
        });

        return array_values($primes);
    }

    /** Enabled, upcoming primes for the public site (past events hidden). */
    public static function activePrimes()
    {
        self::expirePastEvents();
        $now = \Carbon\Carbon::now();
        $out = [];
        foreach (self::sortedPrimes(self::get('primes', [])) as $p) {
            if (!self::isRowEnabled($p)) {
                continue;
            }
            $dt = self::parseEventDate($p['date'] ?? '');
            if ($dt && $dt->lt($now)) {
                continue;
            }
            $out[] = $p;
        }

        return $out;
    }

    /** Enabled casting rows for the public site. */
    public static function activeCastingRows()
    {
        $rows = self::get('casting_rows', []);
        if (!is_array($rows)) {
            return [];
        }

        return array_values(array_filter($rows, function ($row) {
            return self::isRowEnabled(is_array($row) ? $row : []);
        }));
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
        if (!empty($img) && self::uploadExists($img)) {
            return self::publicUploadUrl($img);
        }
        return url($fallback);
    }

    /** How many top-voted contestants to show in "Most Voted of the Week". */
    public static function mostVotedCount()
    {
        $n = (int) self::get('most_voted_count', 1);
        return max(1, min(20, $n));
    }

    /** Public URL for a prime/finals promo image (optional). */
    public static function primeImageUrl($path)
    {
        if (empty($path) || !self::uploadExists($path)) {
            return null;
        }

        return self::publicUploadUrl($path);
    }

    /** The next upcoming enabled prime (for the countdown), or null. */
    public static function nextPrime()
    {
        self::expirePastEvents();
        $now = \Carbon\Carbon::now();
        $upcoming = null;
        $upcomingAt = null;
        foreach (self::sortedPrimes(self::get('primes', [])) as $p) {
            if (!self::isRowEnabled($p)) {
                continue;
            }
            $dt = self::parseEventDate($p['date'] ?? '');
            if (!$dt || $dt->lte($now)) {
                continue;
            }
            if ($upcoming === null || $dt->lt($upcomingAt)) {
                $upcoming = $p;
                $upcomingAt = $dt;
            }
        }

        return $upcoming;
    }

    /** About page field with lang-file fallback. */
    public static function aboutField($key, $fallback = '')
    {
        $page = self::get('about_page', []);
        if (!is_array($page)) {
            return $fallback;
        }
        $val = $page[$key] ?? null;
        if ($val !== null && trim((string) $val) !== '') {
            return $val;
        }
        return $fallback;
    }

    /**
     * About-page text that must stay translatable. The admin stores content in a
     * single language (English). On non-default locales we prefer a per-locale
     * override (e.g. mission_p1_fr) and otherwise fall back to the translated
     * language string, so French visitors never see the English admin text.
     */
    public static function aboutTranslatable($key, $fallback = '')
    {
        $page = self::get('about_page', []);
        $locale = \App::getLocale();
        if (is_array($page)) {
            $localeVal = $page[$key . '_' . $locale] ?? null;
            if ($localeVal !== null && trim((string) $localeVal) !== '') {
                return $localeVal;
            }
            $base = $page[$key] ?? null;
            if ($locale === 'en' && $base !== null && trim((string) $base) !== '') {
                return $base;
            }
        }
        return $fallback;
    }

    public static function aboutImageUrl()
    {
        $page = self::get('about_page', []);
        $img = is_array($page) ? ($page['image'] ?? null) : null;
        if (!empty($img) && self::uploadExists($img)) {
            return self::publicUploadUrl($img);
        }
        return asset('public/frontend/images/bottom-banner-en.jpeg');
    }

    /** @return string[] */
    public static function aboutRegions()
    {
        $raw = self::aboutField('regions', '');
        if ($raw === '') {
            return ['Adamaoua', 'Centre', 'East', 'Far North', 'Littoral', 'North', 'North-West', 'West', 'South', 'South-West'];
        }
        return array_values(array_filter(array_map('trim', explode(',', $raw))));
    }

    /** @return array<int, array{label: string, icon: string}> */
    public static function aboutValues()
    {
        $icons = ['fa-star', 'fa-shield-halved', 'fa-dove', 'fa-gem', 'fa-lightbulb', 'fa-bolt'];
        $defaults = [
            trans('file.Excellence'),
            trans('file.Integrity'),
            trans('file.Spirit-led worship'),
            trans('file.Purity'),
            trans('file.Innovation'),
            trans('file.Performance'),
        ];

        $raw = self::aboutField('values', implode(', ', $defaults));
        $labels = array_values(array_filter(array_map('trim', explode(',', $raw))));

        if (empty($labels)) {
            $labels = $defaults;
        }

        $values = [];
        foreach ($labels as $i => $label) {
            $values[] = [
                'label' => $label,
                'icon' => $icons[$i] ?? 'fa-circle-check',
            ];
        }

        return $values;
    }

    public static function aboutWinnersYear()
    {
        $year = trim(self::aboutField('winners_year', '2025'));
        return $year !== '' ? $year : '2025';
    }
}
