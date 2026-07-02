<?php

namespace App\Helpers;

class WhatsAppFormatter
{
    public static function heading(string $icon, string $title): string
    {
        return "{$icon} *{$title}*\n" . str_repeat('─', 22) . "\n\n";
    }

    public static function line(string $label, string $value): string
    {
        return "☑ *{$label}:* {$value}\n";
    }

    public static function greeting(string $name): string
    {
        return "Hello *{$name}*,\n\n";
    }

    public static function footer(): string
    {
        return "\n🌐 " . (getenv('APP_NAME') ?: config('app.name', 'Mulema GC'));
    }
}
