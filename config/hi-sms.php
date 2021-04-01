<?php

use ITBrains\HiSMS\HiSMSServiceProvider;

return [
    /**
     * Available values: 'hisms', 'log' and 'null'
     *
     * The 'log' driver is useful for development and 'null' for running unit tests.
     */
    'driver' => env('HISMS_DRIVER', HiSMSServiceProvider::API_DRIVER),

    'api_endpoint' => env('HISMS_API_ENDPOINT', 'https://www.hisms.ws/api.php'),

    'username' => env('HISMS_USERNAME'),
    'password' => env('HISMS_PASSWORD'),
    'sender_name' => env('HISMS_SENDER_NAME'),

    'log' => [
        /** By default will be used the default from the app */
        'channel' => env('HISMS_LOG_CHANNEL'),

        'level' => env('HISMS_LOG_LEVEL', 'info'),
    ],
];
