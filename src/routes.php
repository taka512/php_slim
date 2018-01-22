<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes
$app->get('/', \Taka512\Controller\HomeController::class . ':index');
$app->get('/hello/{name}', \Taka512\Controller\HomeController::class . ':hello');
$app->map(['GET'], '/site', \Taka512\Controller\SiteController::class. ':index')->setName('site_index');
$app->map(['GET', 'POST'], '/site/new', \Taka512\Controller\SiteController::class. ':new')->setName('site_new');
$app->map(['GET', 'POST'], '/site/{id}/edit', \Taka512\Controller\SiteController::class. ':edit')->setName('site_edit');
