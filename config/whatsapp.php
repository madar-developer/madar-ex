<?php

return [

    /*
    |--------------------------------------------------------------------------
    | WhatsApp Cloud API
    |--------------------------------------------------------------------------
    |
    | Official Meta WhatsApp Cloud API settings. Secrets must come from the
    | environment only — never hardcode access tokens or app secrets.
    |
    */

    'enabled' => (bool) env('WHATSAPP_ENABLED', false),

    'access_token' => env('WHATSAPP_ACCESS_TOKEN'),

    'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),

    'business_account_id' => env('WHATSAPP_BUSINESS_ACCOUNT_ID'),

    'api_version' => env('WHATSAPP_API_VERSION', 'v25.0'),

    'graph_base_url' => env('WHATSAPP_GRAPH_BASE_URL', 'https://graph.facebook.com'),

    'default_language' => env('WHATSAPP_DEFAULT_LANGUAGE', 'ar'),

    'webhook_verify_token' => env('WHATSAPP_WEBHOOK_VERIFY_TOKEN'),

    'app_secret' => env('WHATSAPP_APP_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | HTTP client
    |--------------------------------------------------------------------------
    */

    'timeout' => (int) env('WHATSAPP_HTTP_TIMEOUT', 15),

    'connect_timeout' => (int) env('WHATSAPP_HTTP_CONNECT_TIMEOUT', 5),

    /*
    |--------------------------------------------------------------------------
    | Customer service window (hours)
    |--------------------------------------------------------------------------
    |
    | Free-form text/media is only allowed within this window after the
    | customer's most recent inbound message.
    |
    */

    'service_window_hours' => (int) env('WHATSAPP_SERVICE_WINDOW_HOURS', 24),

    /*
    |--------------------------------------------------------------------------
    | Queue
    |--------------------------------------------------------------------------
    */

    'queue' => env('WHATSAPP_QUEUE', 'whatsapp'),

];
