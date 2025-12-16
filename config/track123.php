<?php
return [

    /*
    |--------------------------------------------------------------------------
    | API TOKEN
    |--------------------------------------------------------------------------
    |
    | To authenticate your API requests, Track123 uses an API key.
    | This key must be included in the request header for every API call.
    |
    */
    'api_token' => env('TRACK123_API_TOKEN', 'YOUR_API_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | API Endpoints
    |--------------------------------------------------------------------------
    |
    */
    'api_url' => env('TRACK123_API_URL', 'https://api.track123.com/gateway/open-api/'),

];