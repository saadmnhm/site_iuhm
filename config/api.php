<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Content Management API (ERP)
    |--------------------------------------------------------------------------
    | CONTENT_API_BASE_URL   – API root, e.g. http://127.0.0.1:8000/api/v1
    | CONTENT_API_TOKEN      – Bearer token sent on every request (optional)
    | CONTENT_API_TIMEOUT    – Max seconds to wait for a response
    | CONTENT_API_CACHE_TTL  – Seconds to cache GET responses (0 = disabled)
    | CONTENT_API_PLATFORM_URL – Root URL of the ERP platform (for images)
    */

    'base_url'     => env('CONTENT_API_BASE_URL', 'http://127.0.0.1:8000/api/v1'),
    'token'        => env('CONTENT_API_TOKEN', ''),
    'timeout'      => (int) env('CONTENT_API_TIMEOUT', 10),
    'cache_ttl'    => (int) env('CONTENT_API_CACHE_TTL', 300),
    'platform_url' => rtrim(env('CONTENT_API_PLATFORM_URL', 'http://127.0.0.1:8000'), '/'),

];

