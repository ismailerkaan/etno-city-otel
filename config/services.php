<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'hms' => [
        // HMS Online Booking API (https://www.hmsdev.com/online-booking)
        'enabled' => (bool) env('HMS_ENABLED', false),
        'base_url' => env('HMS_BASE_URL', 'https://test.hms.gen.tr'),
        'api_key' => env('HMS_API_KEY'),
        'api_secret' => env('HMS_API_SECRET'),
        'hotel_code' => env('HMS_HOTEL_CODE'),
        'timeout' => (int) env('HMS_TIMEOUT', 15),
    ],

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
