<?php

return [
    'api' => [
        'base_url' => env('FUEL_API_BASE_URL', 'https://nasgunawann.github.io/bensin-api'),
        'timeout' => (int) env('FUEL_API_TIMEOUT', 15),
        'retry_attempts' => (int) env('FUEL_API_RETRY_ATTEMPTS', 3),
        'retry_sleep_ms' => (int) env('FUEL_API_RETRY_SLEEP_MS', 500),
        'user_agent' => env('FUEL_API_USER_AGENT', 'PantauBBM/1.0'),
    ],

    'sync' => [
        'lock_store' => env('FUEL_SYNC_LOCK_STORE', 'file'),
        'lock_key' => env('FUEL_SYNC_LOCK_KEY', 'fuel:sync'),
        'lock_seconds' => (int) env('FUEL_SYNC_LOCK_SECONDS', 600),
        'cache_store' => env('FUEL_SYNC_CACHE_STORE', 'file'),
        'schedule_cron' => env('FUEL_SYNC_SCHEDULE_CRON', '0 */6 * * *'),
    ],
];
