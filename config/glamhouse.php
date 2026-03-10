<?php

return [
    'admin_email' => env('GLAMHOUSE_ADMIN_EMAIL'),

    'whatsapp_notifications' => [
        'enabled' => (bool) env('GLAMHOUSE_WHATSAPP_ENABLED', false),
        'provider' => env('GLAMHOUSE_WHATSAPP_PROVIDER', 'meta'),
        'admin_number' => env('GLAMHOUSE_WHATSAPP_ADMIN_NUMBER'),

        'meta' => [
            'access_token' => env('GLAMHOUSE_META_WHATSAPP_TOKEN'),
            'phone_number_id' => env('GLAMHOUSE_META_PHONE_NUMBER_ID'),
            'api_version' => env('GLAMHOUSE_META_API_VERSION', 'v21.0'),
        ],
    ],
];
