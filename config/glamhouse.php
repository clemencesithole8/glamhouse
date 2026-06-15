<?php

return [
    'admin_email' => env('GLAMHOUSE_ADMIN_EMAIL', env('BUSINESS_EMAIL', 'esther2026@gmail.com')),

    'whatsapp_notifications' => [
        'enabled' => (bool) env('GLAMHOUSE_WHATSAPP_ENABLED', false),
        'provider' => env('GLAMHOUSE_WHATSAPP_PROVIDER', 'meta'),
        'admin_number' => env('GLAMHOUSE_WHATSAPP_ADMIN_NUMBER', env('BUSINESS_PHONE', '+263784721479')),

        'meta' => [
            'access_token' => env('GLAMHOUSE_META_WHATSAPP_TOKEN'),
            'phone_number_id' => env('GLAMHOUSE_META_PHONE_NUMBER_ID'),
            'api_version' => env('GLAMHOUSE_META_API_VERSION', 'v21.0'),
        ],
    ],

    'backups' => [
        'enabled' => (bool) env('GLAMHOUSE_BACKUPS_ENABLED', true),
        'daily_at' => env('GLAMHOUSE_BACKUPS_DAILY_AT', '02:15'),
        'retention_days' => (int) env('GLAMHOUSE_BACKUPS_RETENTION_DAYS', 14),
    ],
];
