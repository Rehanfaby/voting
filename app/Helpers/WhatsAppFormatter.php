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

    /**
     * Public vote standing for a contestant (successful votes only).
     * Position = 1 + number of active approved contestants with strictly more votes.
     *
     * @return array{total:int,position:int}
     */
    public static function contestantPublicVoteStanding(int $musicianId): array
    {
        $total = 0;
        $position = 1;
        try {
            $total = (int) \DB::table('votes')
                ->where('musician_id', $musicianId)
                ->where('status', 1)
                ->sum('vote');

            $ahead = (int) \DB::table('employees')
                ->where('employees.is_active', true)
                ->where('employees.is_approve', true)
                ->where('employees.id', '!=', $musicianId)
                ->whereRaw(
                    '(SELECT COALESCE(SUM(v.vote), 0) FROM votes v WHERE v.musician_id = employees.id AND v.status = 1) > ?',
                    [$total]
                )
                ->count();

            $position = $ahead + 1;
        } catch (\Throwable $e) {
            // keep defaults
        }

        return ['total' => $total, 'position' => $position];
    }

    /** Voter confirmation after a successful vote (bilingual, OTP-style layout). */
    public static function voterVoteConfirmedMessage(
        string $voterName,
        string $contestantName,
        $voteCount,
        $contestantTotal,
        string $paymentLabel = '',
        $extraLines = [],
        $locale = null
    ): string {
        $count = max(1, (int) $voteCount);
        $name = $contestantName ?: '—';
        $total = (int) $contestantTotal;
        $lines = [
            ['Candidat', 'Contestant', $name],
            ['Votes donnés', 'Votes cast', (string) $count],
            ['Total du candidat', 'Contestant total votes', (string) $total],
        ];
        if ($paymentLabel !== '') {
            $lines[] = ['Paiement', 'Payment', $paymentLabel];
        }
        if (is_array($extraLines)) {
            foreach ($extraLines as $line) {
                if (is_array($line) && count($line) >= 3) {
                    $lines[] = $line;
                }
            }
        }

        return self::compose(
            '✅',
            'VOTE CONFIRMÉ',
            'VOTE CONFIRMED',
            $voterName ?: 'Voter',
            "Merci ! Votre vote pour *{$name}* a été enregistré. Ce candidat a maintenant *{$total}* vote(s).",
            "Thank you! Your vote for *{$name}* has been recorded. This contestant now has *{$total}* vote(s).",
            $lines,
            'Chaque vote compte — merci pour votre soutien !',
            'Every vote counts — thank you for your support!',
            $locale // null = bilingual FR + EN
        );
    }

    /** Contestant alert: a voter just cast votes for them (bilingual, OTP-style layout). */
    public static function contestantVoteReceivedMessage(
        string $contestantName,
        string $voterName,
        $voteCount,
        string $paymentLabel,
        $newTotal = null,
        $locale = null,
        $position = null
    ): string {
        $count = max(1, (int) $voteCount);
        $voter = $voterName ?: 'Voter';
        $lines = [
            ['Électeur', 'Voter', $voter],
            ['Votes reçus', 'Votes cast', (string) $count],
        ];
        if ($newTotal !== null && $newTotal !== '') {
            $lines[] = ['Total des votes', 'Total votes', (string) $newTotal];
        }
        if ($position !== null && $position !== '') {
            $lines[] = ['Position', 'Position', (string) $position];
        }
        if ($paymentLabel !== '') {
            $lines[] = ['Paiement', 'Payment', $paymentLabel];
        }

        return self::compose(
            '🗳️',
            'NOUVEAU VOTE REÇU',
            'NEW VOTE RECEIVED',
            $contestantName ?: 'Candidat',
            "*{$voter}* a voté *{$count}* fois pour vous.",
            "*{$voter}* has cast *{$count}* vote(s) for you.",
            $lines,
            'Continuez — chaque vote compte !',
            'Keep going — every vote counts!',
            $locale // null = bilingual FR + EN
        );
    }

    /** Voter alert: Visa/card payment failed (funds, timeout, risk, etc.). */
    public static function voteCardPaymentFailedMessage(
        string $voterName,
        string $contestantName,
        $voteCount,
        string $reasonFr,
        string $reasonEn,
        $amount = null,
        $locale = null
    ): string {
        $locale = self::normalizeLocale($locale) ?: self::currentLocale();
        $reasonValue = $locale === 'fr' ? $reasonFr : ($locale === 'en' ? $reasonEn : "{$reasonFr} / {$reasonEn}");
        $lines = [
            ['Candidat', 'Contestant', $contestantName ?: '—'],
            ['Votes', 'Votes', (string) max(1, (int) $voteCount)],
            ['Motif', 'Reason', $reasonValue],
        ];
        if ($amount !== null && $amount !== '') {
            $lines[] = ['Montant', 'Amount', number_format((float) $amount) . ' XAF'];
        }

        return self::compose(
            '⚠️',
            'PAIEMENT CARTE ÉCHOUÉ',
            'CARD PAYMENT FAILED',
            $voterName ?: 'Voter',
            'Votre paiement Visa / Mastercard n\'a pas abouti. Aucun vote n\'a été comptabilisé.',
            'Your Visa / Mastercard payment did not go through. No votes were counted.',
            $lines,
            'Vous pouvez réessayer avec une autre carte ou payer par MoMo / Orange Money.',
            'You can retry with another card or pay with MoMo / Orange Money.',
            $locale
        );
    }

    /**
     * Map Stripe / card failure codes to bilingual human reasons.
     * @return array{0:string,1:string} [fr, en]
     */
    public static function cardFailureReasonPair($code, $message = null): array
    {
        $code = strtolower(trim((string) $code));
        $message = strtolower(trim((string) $message));

        if ($code === 'insufficient_funds' || strpos($message, 'insufficient funds') !== false) {
            return ['Fonds insuffisants', 'Insufficient funds'];
        }
        if ($code === 'expired_card' || strpos($message, 'expired') !== false && strpos($message, 'card') !== false) {
            return ['Carte expirée', 'Expired card'];
        }
        if (
            strpos($message, 'too risky') !== false
            || strpos($message, 'blocked this payment') !== false
            || $code === 'fraudulent'
            || $code === 'highest_risk_level'
        ) {
            return ['Paiement bloqué (risque / sécurité)', 'Payment blocked (risk / security)'];
        }
        if (
            $code === 'authentication_required'
            || $code === 'payment_intent_authentication_failure'
            || strpos($message, 'authenticat') !== false
            || strpos($message, '3d secure') !== false
        ) {
            return ['Authentification échouée (3D Secure)', 'Authentication failed (3D Secure)'];
        }
        if ($code === 'checkout_expired' || $code === 'session_expired' || strpos($message, 'timed out') !== false) {
            return ['Délai dépassé / session expirée', 'Timed out / session expired'];
        }
        if ($code === 'card_declined' || strpos($message, 'declined') !== false) {
            return ['Carte refusée par la banque', 'Card declined by the bank'];
        }
        if ($code === 'lost_card' || $code === 'stolen_card') {
            return ['Carte signalée perdue / volée', 'Card reported lost / stolen'];
        }
        if ($code === 'processing_error') {
            return ['Erreur de traitement du paiement', 'Payment processing error'];
        }

        if ($message !== '') {
            return ['Paiement non abouti', 'Payment did not complete'];
        }

        return ['Paiement non abouti', 'Payment did not complete'];
    }
}
