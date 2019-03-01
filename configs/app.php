<?php

return new \Phalcon\Config(
    [
        'env' => [
            'product' => 'prd', // production
            'test' => 'test', // test
            'stage' => 'stage', // stage
            'dev' => 'dev' // development
        ],

        'APP_ENV' => env('APP_ENV', 'dev'),

        'DEBUG_REPORT' => env('DEBUG_REPORT', 'none'),
    ]
);