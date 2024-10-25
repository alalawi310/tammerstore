<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
        'iyzipay/callback/*',
        '/aamarpay/*',
        '*/aamarpay/callback?*',
        '*/get-payment-paytm',
        '*/get-payment-aamarpay?*',
        '*/get-payment-iyzico',
        'plan-get-phonepe-status',
        '*/store-payment-phonepe',
        '*/get-donation-payment-paytm',
        '*/get-donation-payment-iyzipay',
        '*/get-donation-payment-aamarpay',
        'plan-easebuzz-payment-notify*',
        '*get-payment-easebuzz*',
        '/login',
        '/duologin',
        'plan-get-Powertranz-status*',
        'store-get-Powertranz-status*',
        'campaigns-card-payment/',
        'campaigns-Powertranz'
    ];
}
