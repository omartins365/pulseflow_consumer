<?php

return [
    'api' => [
        'pulse' => [
            'key' => env('PULSE_API_KEY'),
            'domain' => env('PULSE_VENDOR_DOMAIN'),
            'pin' => env('PULSE_API_PIN'),
            'secret_key' => env('PULSE_SECRET_KEY'),
        ]
    ]
];
