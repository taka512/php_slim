<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes
$app->get('/', \Taka512\Controller\HomeController::class . ':index');
$app->get('/hello/{name}', \Taka512\Controller\HomeController::class . ':hello');
$app->map(['GET'], '/site', \Taka512\Controller\SiteController::class. ':index')->setName('site_index');
$app->map(['GET', 'POST'], '/site/create', \Taka512\Controller\SiteController::class. ':create')->setName('site_create');
$app->map(['GET', 'POST'], '/site/{id}/edit', \Taka512\Controller\SiteController::class. ':edit')->setName('site_edit');

$app->group('/api', function () {
    $this->map(['GET'], '/test', \Taka512\Controller\Api\TestController::class. ':index')->setName('api_test_index');
});

