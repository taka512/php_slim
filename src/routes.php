<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes
$app->get('/', function ($request, $response, $args) {
    return $this->view->render($response, 'index.html.twig', []);
})->setName('top');

$app->get('/hello/{name}', function ($request, $response, $args) {
    return $this->view->render($response, 'hello.html.twig', [
        'name' => $args['name']
    ]);
})->setName('hello');
