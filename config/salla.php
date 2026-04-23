<?php

return [
    'base_url' => env('SALLA_BASE_URL', 'https://api.salla.dev/admin/v2'),
    'oauth_base_url' => env('SALLA_OAUTH_BASE_URL', 'https://accounts.salla.sa/oauth2'),

    'client_id' => env('SALLA_CLIENT_ID'),
    'client_secret' => env('SALLA_CLIENT_SECRET'),
    'redirect_uri' => env('SALLA_REDIRECT_URI'),

    // لو عندك token ثابت للتجربة فقط
    'access_token' => env('SALLA_ACCESS_TOKEN'),
];