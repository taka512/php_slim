<?php

require __DIR__ . '/../vendor/autoload.php';

// Instantiate the app
if (isset($_SERVER['TEST_REQUEST'])) {
    $app = new \Slim\App(\Taka512\ContainerFactory::getTestContainer());
} else {
    $app = new \Slim\App(\Taka512\ContainerFactory::getContainer());
}

// Set up dependencies
require __DIR__ . '/../bootstrap/http_dependencies.php';

// Register routes
require __DIR__ . '/../bootstrap/routes.php';

// Run app
$app->run();
