<?php

return [
    'newsapi' => [
        'api_key' => env('NEWSAPI_KEY', ''),
        'base_url' => 'https://newsapi.org/v2',
    ],
    'guardian' => [
        'api_key' => env('GUARDIAN_KEY'),
        'base_url' => 'https://content.guardianapis.com',
    ],
    // Add other sources as needed
    'categories' => [
        'Technology' => ['tech', 'technology', 'science'],
        'World' => ['world', 'international'],
        'Sports' => ['sports', 'games'],
        'General' => ['general', 'miscellaneous'],
    ],

];
