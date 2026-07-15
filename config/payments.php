<?php

return [
    'mobile_money' => [
        'default' => env('MOBILE_MONEY_PROVIDER', 'campay'),

        'providers' => [
            'campay' => [
                'enabled' => env('CAMPAY_ENABLED', true),
                'base_url' => env('CAMPAY_BASE_URL', 'https://www.campay.net/api'),
                'demo_base_url' => env('CAMPAY_DEMO_BASE_URL', 'https://demo.campay.net/api'),
                'token' => env('CAMPAY_TOKEN', env('MOMO_TOKEN')),
                'username' => env('CAMPAY_USERNAME', env('MOMO_APP_USERNAME')),
                'password' => env('CAMPAY_PASSWORD', env('MOMO_APP_PASSWORD')),
                'app_username' => env('CAMPAY_APP_USERNAME', env('MOMO_APP_USERNAME')),
                'app_password' => env('CAMPAY_APP_PASSWORD', env('MOMO_APP_PASSWORD')),
                'webhook_secret' => env('CAMPAY_WEBHOOK_SECRET', env('MOMO_APP_WEBHOOK')),
                'simulate' => env('PAYMENT_SIMULATE'),
            ],

            'pawapay' => [
                'enabled' => env('PAWAPAY_ENABLED', false),
                'environment' => env('PAWAPAY_ENVIRONMENT', 'sandbox'),
                'sandbox_base_url' => env('PAWAPAY_SANDBOX_BASE_URL', 'https://api.sandbox.pawapay.io'),
                'live_base_url' => env('PAWAPAY_LIVE_BASE_URL', 'https://api.pawapay.io'),
                'api_token' => env('PAWAPAY_API_TOKEN'),
                'api_token_id' => env('PAWAPAY_API_TOKEN_ID'),
                'country' => env('PAWAPAY_COUNTRY', 'CMR'),
                'currency' => env('PAWAPAY_CURRENCY', 'XAF'),
                'callback_url' => env('PAWAPAY_DEPOSIT_CALLBACK_URL', env('PAWAPAY_CALLBACK_URL')),
                'callback_urls' => [
                    'checkout' => env('PAWAPAY_CHECKOUT_CALLBACK_URL'),
                    'deposit' => env('PAWAPAY_DEPOSIT_CALLBACK_URL', env('PAWAPAY_CALLBACK_URL')),
                    'payout' => env('PAWAPAY_PAYOUT_CALLBACK_URL'),
                    'refund' => env('PAWAPAY_REFUND_CALLBACK_URL'),
                ],
                'verify_callback_signature' => env('PAWAPAY_VERIFY_CALLBACK_SIGNATURE', false),
                'timeout' => (int) env('PAWAPAY_REQUEST_TIMEOUT', 30),
                'connect_timeout' => (int) env('PAWAPAY_CONNECT_TIMEOUT', 10),
            ],
        ],
    ],
];
