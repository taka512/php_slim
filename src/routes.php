<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes
$app->get('/', \Taka512\Controller\HomeController::class . ':index');
$app->get('/hello/{name}', \Taka512\Controller\HomeController::class . ':hello');
$app->map(['GET'], '/site', \Taka512\Controller\SiteController::class. ':index')->setName('site_index');
$app->map(['GET', 'POST'], '/site/create', \Taka512\Controller\SiteController::class. ':create')->setName('site_create');
$app->map(['GET', 'POST'], '/site/{id}/edit', \Taka512\Controller\SiteController::class. ':edit')->setName('site_edit');
