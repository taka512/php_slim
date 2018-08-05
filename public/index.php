<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

// Instantiate the app
$app = new \Slim\App(\Taka512\ContainerFactory::getContainer());

// Set up dependencies
require __DIR__ . '/../bootstrap/dependencies.php';

// Register routes
require __DIR__ . '/../bootstrap/routes.php';

// Run app
$app->run();
