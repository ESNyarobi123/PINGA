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

    'snippe' => [
        'key' => env('SNIPPE_API_KEY', 'snp_0d35efa325afd1690a3729a0eb9138b138f3b647edceb92910de115b6dbf3af5'),
        'url' => env('SNIPPE_URL', 'https://api.snippe.sh'),
        'webhook_secret' => env('SNIPPE_WEBHOOK_SECRET'),
        'webhook_base_url' => env('SNIPPE_WEBHOOK_BASE_URL', 'https://winga.ericksky.online'),
    ],

    'selcom' => [
        'api_key' => env('SELCOM_API_KEY'),
        'api_secret' => env('SELCOM_API_SECRET'),
        'vendor' => env('SELCOM_VENDOR', 'WINGA'),
        'base_url' => env('SELCOM_BASE_URL', 'https://apigw.selcommobile.com/v1'),
    ],

    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'base_url' => env('OPENAI_BASE_URL', 'https://api.groq.com/openai/v1'),
        'model' => env('OPENAI_MODEL', 'llama-3.1-8b-instant'),
    ],

    /*
    | OpenStreetMap Nominatim (geocoding job locations for the map).
    | Set NOMINATIM_ENABLED=false in testing or if you do not want outbound HTTP.
    */
    'nominatim' => [
        'enabled' => env('NOMINATIM_ENABLED', true),
        'timeout' => env('NOMINATIM_TIMEOUT', 12),
        'user_agent' => env('NOMINATIM_USER_AGENT', 'WingaApp/1.0 (+https://winga.co.tz)'),
    ],

];
