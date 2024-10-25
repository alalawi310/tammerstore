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
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'cashfree' => [
        'key' => '',
        'secret' => '',
        'url' => 'https://sandbox.cashfree.com/pg/orders',
    ],
    'google' => [
        'client_id' => '',
        'client_secret' => '',
        'redirect' => '',
    ],
    'github' => [
        'client_id' => '',
        'client_secret' => '',
        'redirect' => '',
    ],
    'linkedin-openid' => [
        'client_id' => '',
        'client_secret' => '',
        'redirect' => '',
    ],
    'twitter' => [
        'client_id' => '',
        'client_secret' => '',
        'redirect' => '',
    ],
    'bitbucket'=> [
        'client_id' => '',
        'client_secret' => '',
        'redirect' => '',
    ],
    'slack'=> [
        'client_id' => '',
        'client_secret' => '',
        'redirect' => '',
    ],
    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_CALLBACK_URL'),
    ],
    'nmi' => [
        'endpoint' => env('NMI_API_ENDPOINT'),
        'username' => env('NMI_API_USERNAME'),
        'password' => env('NMI_API_PASSWORD'),
    ],

];
