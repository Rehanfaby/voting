<?php

namespace App\Helpers;

use App\User;
use Illuminate\Support\Collection;

class VoterVcf
{
    /** Unique voters (role_id = 3), one row per phone — same rule as the admin list. */
    public static function uniqueVoters(): Collection
    {
        $users = User::where('is_deleted', false)->where('role_id', 3)->orderBy('id')->get();
        $seen = [];
        $unique = collect();
        foreach ($users as $user) {
            $key = PhoneHelper::identityKey($user->phone ?: $user->whatsapp_number) ?: ('id:' . $user->id);
            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;
            $unique->push($user);
        }

        return $unique;
    }

    public static function build(): string
    {
        $cards = [];
        foreach (self::uniqueVoters() as $user) {
            $card = self::cardForUser($user);
            if ($card) {
                $cards[] = $card;
            }
        }

        return $cards ? implode("\r\n", $cards) . "\r\n" : '';
    }

    public static function cardForUser(User $user): ?string
    {
        $tel = self::bestWhatsAppNumber($user);
        if (!$tel) {
            return null;
        }

        $name = trim((string) $user->name);
        if ($name === '' || PhoneHelper::looksLikePhone($name)) {
            $local = preg_replace('/\D/', '', $tel);
            if (strpos($local, '237') === 0) {
                $local = substr($local, 3);
            }
            $name = 'Mulema Voter ' . $local;
        }

        $fn = self::escape($name);
        $parts = preg_split('/\s+/', $name, -1, PREG_SPLIT_NO_EMPTY) ?: [$name];
        $family = self::escape((string) array_pop($parts));
        $given = self::escape(implode(' ', $parts));

        return "BEGIN:VCARD\r\n"
            . "VERSION:3.0\r\n"
            . "FN:{$fn}\r\n"
            . "N:{$family};{$given};;;\r\n"
            . "TEL;TYPE=CELL:{$tel}\r\n"
            . "END:VCARD";
    }

    public static function bestWhatsAppNumber(User $user): ?string
    {
        foreach ([$user->whatsapp_number, $user->phone] as $raw) {
            if (PhoneHelper::isValidWhatsApp($raw)) {
                return PhoneHelper::cameroon($raw);
            }
        }

        return null;
    }

    private static function escape($value): string
    {
        return str_replace(
            ["\\", "\r\n", "\n", ";", ","],
            ["\\\\", "\\n", "\\n", "\\;", "\\,"],
            (string) $value
        );
    }
}
