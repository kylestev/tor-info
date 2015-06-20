<?php

return [

    'caching' => [
        'enabled' => env('TOR_IP_CACHING', true),

        'tags'    => ['tor'],

        // TTL in minutes for how long the IPs should stay active
        'expiry'  => env('TOR_CACHING_TTL', 60),
    ],

];
