<?php

return new \Phalcon\Config(
    [
        'adapter' => 'mysql',
        'mysql' => [
            'host' => env('DB_HOST', ''),
            'port' => env('DB_PORT', ''),
            'username' => env('DB_USERNAME', ''),
            'password' => env('DB_PASSWORD', ''),
            'dbname' => env('DB_NAME', ''),
            'charset' => 'utf8',
        ],
        'redis' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'port' => env('REDIS_PORT', '6379'),
            'lifetime' => env('REDIS_LIFETIME', '3600'),
            'auth' => env('REDIS_AUTH', '')
        ],
        'memcache' => [
            'host' => env('MEMCACHE_HOST', '127.0.0.1'),
            'port' => env('MEMCACHE_PORT', '11211'),
        ],
        'redisKeys' => [
            "*" => "common", 
        ],
    ]
);