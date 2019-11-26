<?php

use Taka512\HttpErrorHandler;
use Taka512\ShutdownHandler;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\Views\TwigMiddleware;
use Taka512\ContainerFactory;

require __DIR__ . '/../vendor/autoload.php';

if (isset($_SERVER['TEST_REQUEST'])) {
    $container = ContainerFactory::getTestContainer();
} else {
    $container = ContainerFactory::getContainer();
}

// Instantiate the app
AppFactory::setContainer($container);
$app = AppFactory::create();

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();// Add Routing Middleware

// Set up http request dependencies
require __DIR__ . '/../bootstrap/http_dependencies.php';

/** @var bool $displayErrorDetails */
$displayErrorDetails = $container->get('settings')['displayErrorDetails'];

$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();

$shutdownHandler = new ShutdownHandler($request, $container->get('error_handler'), $displayErrorDetails);
register_shutdown_function($shutdownHandler);

// need after dependencies.php because view service
$app->add(TwigMiddleware::createFromContainer($app));
require __DIR__ . '/../bootstrap/routes.php';

// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, false, false);
$errorMiddleware->setDefaultErrorHandler($container->get('error_handler'));

$app->run($request);

