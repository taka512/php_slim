<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes
$app->get('/', \Taka512\Controller\HomeController::class . ':index');
$app->get('/hello/{name}', \Taka512\Controller\HomeController::class . ':hello');
