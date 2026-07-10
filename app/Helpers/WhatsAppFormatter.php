<?php

namespace App\Helpers;

class WhatsAppFormatter
{
    public static function siteName(): string
    {
        return 'Mulemagc';
    }

    public static function siteUrl(): string
    {
        $url = rtrim((string) config('app.url', url('/')), '/');
        if (strpos($url, 'localhost') !== false || strpos($url, '127.0.0.1') !== false) {
            return 'https://mulemagc.com';
        }

        return $url;
    }

    /** Top brand line (shown like WhatsApp link preview header). */
    public static function brandLine(): string
    {
        return "🔗 *Mulemagc*\n\n";
    }

    /** Bilingual section heading with separator. */
    public static function bilingualHeading(string $icon, string $titleFr, string $titleEn): string
    {
        return "{$icon} *{$titleFr} / {$titleEn}*\n" . str_repeat('─', 28) . "\n\n";
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

    /** @deprecated Use bilingualGreeting */
    public static function greeting(string $name): string
    {
        return self::bilingualGreeting($name);
    }

    public static function bilingualLine(string $labelFr, string $labelEn, string $value): string
    {
        return "■ *{$labelFr} / {$labelEn}:* {$value}\n";
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

    public static function footer(string $noteFr = '', string $noteEn = ''): string
    {
        $out = '';
        if ($noteFr !== '' || $noteEn !== '') {
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

    /** Build a complete notification message. */
    public static function compose(
        string $icon,
        string $titleFr,
        string $titleEn,
        string $name,
        string $bodyFr,
        string $bodyEn,
        array $lines = [],
        string $noteFr = '',
        string $noteEn = ''
    ): string {
        $msg = self::brandLine();
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
    public static function otpMessage(string $name, string $otp, int $minutes = 3): string
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
            'Never share this code with anyone.'
        );
    }
}
