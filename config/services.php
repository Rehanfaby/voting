<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'ultramsg' => [
        'instance' => env('ULTRAMSG_INSTANCE'),
        'token' => env('ULTRAMSG_TOKEN'),
        // Minimum seconds between any two UltraMsg API calls (anti-ban).
        'min_interval_seconds' => (int) env('ULTRAMSG_MIN_INTERVAL_SECONDS', 6),
    ],

    'momo' => [
        'token' => env('MOMO_TOKEN'),
        'app_id' => env('MOMO_APP_ID'),
        'username' => env('MOMO_APP_USERNAME'),
        'password' => env('MOMO_APP_PASSWORD'),
        'webhook' => env('MOMO_APP_WEBHOOK'),
        'simulate' => env('PAYMENT_SIMULATE'),
    ],

    'admin_number' => env('ADMIN_NUMBER', '237675321739'),

    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

];
