<?php

namespace App\Helpers;

class WhatsAppFormatter
{
    public static function siteName(): string
    {
        return 'Mulemagc';
    }

    /** Human system/brand name shown at the top of every message (from Settings → Site Title). */
    public static function systemName(): string
    {
        try {
            $title = \App\GeneralSetting::query()->value('site_title');
            if (is_string($title) && trim($title) !== '') {
                return trim($title);
            }
        } catch (\Throwable $e) {
            // fall through
        }

        return 'Mulema Gospel';
    }

    /** Full system title used in announcement messages. */
    public static function announcementTitle(): string
    {
        return self::systemName();
    }

    public static function siteUrl(): string
    {
        $url = rtrim((string) config('app.url', url('/')), '/');
        if (strpos($url, 'localhost') !== false || strpos($url, '127.0.0.1') !== false) {
            return 'https://mulemagc.com';
        }

        return $url;
    }

    /** Normalize to en|fr|null (null = bilingual). */
    public static function normalizeLocale($locale)
    {
        $locale = strtolower(trim((string) $locale));
        if (in_array($locale, ['en', 'fr'], true)) {
            return $locale;
        }

        return null;
    }

    /** Locale from current request/browser cookie, default en. */
    public static function currentLocale(): string
    {
        try {
            $locale = self::normalizeLocale(app()->getLocale());
            if ($locale) {
                return $locale;
            }
        } catch (\Throwable $e) {
            // fall through
        }

        return 'en';
    }

    /** Top brand line: system name alone on the first line, rest follows below. */
    public static function brandLine(): string
    {
        return '*' . self::systemName() . "*\n\n";
    }

    /** Bilingual section heading with separator. */
    public static function bilingualHeading(string $icon, string $titleFr, string $titleEn): string
    {
        return "{$icon} *{$titleFr} / {$titleEn}*\n" . str_repeat('─', 28) . "\n\n";
    }

    public static function monoHeading(string $icon, string $title): string
    {
        return "{$icon} *{$title}*\n" . str_repeat('─', 28) . "\n\n";
    }

    /** @deprecated Use bilingualHeading */
    public static function heading(string $icon, string $title): string
    {
        return self::bilingualHeading($icon, $title, $title);
    }

    public static function bilingualGreeting(string $name): string
    {
        return "Bonjour *{$name}*, / Hello *{$name}*,\n\n";
    }

    public static function monoGreeting(string $name, string $locale): string
    {
        if ($locale === 'fr') {
            return "Bonjour *{$name}*,\n\n";
        }

        return "Hello *{$name}*,\n\n";
    }

    /** @deprecated Use bilingualGreeting */
    public static function greeting(string $name): string
    {
        return self::bilingualGreeting($name);
    }

    public static function bilingualLine(string $labelFr, string $labelEn, string $value): string
    {
        return "■ *{$labelFr} / {$labelEn}:* {$value}\n";
    }

    public static function monoLine(string $label, string $value): string
    {
        return "■ *{$label}:* {$value}\n";
    }

    /** @deprecated Use bilingualLine */
    public static function line(string $label, string $value): string
    {
        return self::bilingualLine($label, $label, $value);
    }

    public static function bilingualBody(string $textFr, string $textEn): string
    {
        return "{$textFr}\n{$textEn}\n\n";
    }

    public static function monoBody(string $text): string
    {
        return "{$text}\n\n";
    }

    public static function footer(string $noteFr = '', string $noteEn = '', $locale = null): string
    {
        $locale = self::normalizeLocale($locale);
        $out = '';
        if ($locale === 'fr') {
            if ($noteFr !== '') {
                $out .= "\n👉 {$noteFr}\n";
            }
        } elseif ($locale === 'en') {
            if ($noteEn !== '') {
                $out .= "\n👉 {$noteEn}\n";
            }
        } elseif ($noteFr !== '' || $noteEn !== '') {
            $out .= "\n";
            if ($noteFr !== '') {
                $out .= "👉 {$noteFr}\n";
            }
            if ($noteEn !== '') {
                $out .= "👉 {$noteEn}\n";
            }
        }
        $out .= "\n🌐 " . self::siteName();

        return $out;
    }

    /**
     * Build a complete notification message.
     * Pass $locale = 'en'|'fr' for a single-language message; null keeps bilingual.
     */
    public static function compose(
        string $icon,
        string $titleFr,
        string $titleEn,
        string $name,
        string $bodyFr,
        string $bodyEn,
        array $lines = [],
        string $noteFr = '',
        string $noteEn = '',
        $locale = null
    ): string {
        $locale = self::normalizeLocale($locale);
        $msg = self::brandLine();

        if ($locale === 'fr') {
            $msg .= self::monoHeading($icon, $titleFr);
            $msg .= self::monoGreeting($name, 'fr');
            $msg .= self::monoBody($bodyFr);
            foreach ($lines as $line) {
                if (count($line) >= 3) {
                    $msg .= self::monoLine($line[0], (string) $line[2]);
                }
            }
            $msg .= self::footer($noteFr, $noteEn, 'fr');
            return $msg;
        }

        if ($locale === 'en') {
            $msg .= self::monoHeading($icon, $titleEn);
            $msg .= self::monoGreeting($name, 'en');
            $msg .= self::monoBody($bodyEn);
            foreach ($lines as $line) {
                if (count($line) >= 3) {
                    $msg .= self::monoLine($line[1], (string) $line[2]);
                }
            }
            $msg .= self::footer($noteFr, $noteEn, 'en');
            return $msg;
        }

        $msg .= self::bilingualHeading($icon, $titleFr, $titleEn);
        $msg .= self::bilingualGreeting($name);
        $msg .= self::bilingualBody($bodyFr, $bodyEn);
        foreach ($lines as $line) {
            if (count($line) === 3) {
                $msg .= self::bilingualLine($line[0], $line[1], (string) $line[2]);
            }
        }
        $msg .= self::footer($noteFr, $noteEn);

        return $msg;
    }

    /** Login / password-reset OTP message. */
    public static function otpMessage(string $name, string $otp, int $minutes = 3, $locale = null): string
    {
        return self::compose(
            '🔐',
            'AUTHENTIFICATION',
            'AUTHENTICATION',
            $name ?: 'Utilisateur',
            "Votre code OTP est : *{$otp}*",
            "Your OTP code is: *{$otp}*",
            [
                ['Code OTP', 'OTP Code', $otp],
                ['Validité', 'Valid for', "{$minutes} minutes"],
            ],
            "Ne partagez jamais ce code avec personne.",
            'Never share this code with anyone.',
            $locale ?: self::currentLocale()
        );
    }
}
