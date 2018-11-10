<?php
return [
    'settings' => [
        'displayErrorDetails' => false, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'view' => [
            'template_path' => __DIR__ . '/../../templates/',
            'cache_path' => '/var/tmp/cache',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => '/var/tmp/logs/app.log',
            'level' => \Monolog\Logger::INFO,
        ],

        // database
        'db' => [
            'driver' => 'mysql',
            'host' => 'db.local',
            'database' => 'sample_slim',
            'username' => 'slim_user',
            'password' => getenv('DB_PASS'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ],
        // session
        'session' => [
            'cookie_name' => 'taka',
        ],
        // form
        'form' => [
            'csrf_timeout' => 7200,
        ],
    ],
];
