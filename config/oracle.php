<?php

return [
    'oracle' => [
        'driver'         => 'oracle',
//        'tns'            => env('DB_M4_TNS', ''),
        'host'           => env('DB_M4_HOST', ''),
        'port'           => env('DB_M4_PORT', '1521'),
        'database'       => env('DB_M4_DATABASE', ''),
        'service_name'   => env('DB_M4_SERVICE_NAME', ''),
        'username'       => env('DB_M4_USERNAME', ''),
        'password'       => env('DB_M4_PASSWORD', ''),
        'charset'        => env('DB_M4_CHARSET', 'AL32UTF8'),
        'prefix'         => env('DB_M4_PREFIX', ''),
        'prefix_schema'  => env('DB_M4_SCHEMA_PREFIX', ''),
        'edition'        => env('DB_M4_EDITION', 'ora$base'),
        'server_version' => env('DB_M4_SERVER_VERSION', '11g'),
    ],
];
