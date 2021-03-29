<?php

use ITBrains\HiSMS\HiSMSServiceProvider;

return [
    'driver' => env('HISMS_DRIVER', HiSMSServiceProvider::API_DRIVER),

    'api_endpoint' => env('HISMS_API_ENDPOINT', 'https://www.hisms.ws/api.php'),

    'username' => env('HISMS_USERNAME'),
    'password' => env('HISMS_PASSWORD'),
    'sender_name' => env('HISMS_SENDER_NAME'),
];
