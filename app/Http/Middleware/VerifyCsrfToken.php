<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'campay/webhook',
        'api/payments/pawapay/callback',
        'api/payments/pawapay/checkouts/callback',
        'api/payments/pawapay/deposits/callback',
        'api/payments/pawapay/payouts/callback',
        'api/payments/pawapay/refunds/callback',
    ];
}
