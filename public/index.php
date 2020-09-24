<?php

use Taka512\HttpErrorHandler;
use Taka512\ShutdownHandler;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\Views\TwigMiddleware;
use Taka512\ContainerFactory;
use Taka512\LoggerFactory;

require __DIR__ . '/../vendor/autoload.php';

if (isset($_SERVER['TEST_REQUEST'])) {
    ContainerFactory::initContainerOnHttp(ContainerFactory::getTestPimpleContainer());
} else {
    ContainerFactory::initContainerOnHttp(ContainerFactory::getPimpleContainer());
}

$container = ContainerFactory::getContainer();
LoggerFactory::initLoggerByApp(
    $container->get('settings')['logger']['path'],
    $container->get('settings')['logger']['level']
);

// Instantiate the app
$app = AppFactory::createFromContainer($container);

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();// Add Routing Middleware

/** @var bool $displayErrorDetails */
$displayErrorDetails = $container->get('settings')['displayErrorDetails'];

$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();

$errorHandler = new HttpErrorHandler($app->getCallableResolver(), $app->getResponseFactory());
$errorHandler->setLogger($container->get('logger'));
$errorHandler->setTwigView($container->get('view'));

$shutdownHandler = new ShutdownHandler($request, $errorHandler, $displayErrorDetails);
register_shutdown_function($shutdownHandler);

// need after dependencies.php because view service
$app->add(TwigMiddleware::createFromContainer($app));
require __DIR__ . '/../bootstrap/routes.php';

// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, false, false);
$errorMiddleware->setDefaultErrorHandler($errorHandler);

$app->run($request);

