<?php

use Slim\Http\Request;
use Slim\Http\Response;

$c = $app->getContainer();

// Routes
$app->get('/', \Taka512\Controller\HomeController::class . ':index')->setName('top');

$app->group('/api', function () {
    $this->map(['GET'], '/test', \Taka512\Controller\Api\TestController::class. ':index')->setName('api_test_index');
});

$app->map(['GET', 'POST'], '/admin/user/signin', \Taka512\Controller\Admin\UserController::class. ':signin')->setName('admin_user_signin');
$app->group('/admin', function () {
    $this->map(['GET'], '', \Taka512\Controller\Admin\HomeController::class. ':index')->setName('admin_home_index');
    // user
    $this->map(['GET'], '/user', \Taka512\Controller\Admin\UserController::class. ':index')->setName('admin_user_index');
    $this->map(['GET', 'POST'], '/user/create', \Taka512\Controller\Admin\UserController::class. ':create')->setName('admin_user_create');
    $this->map(['GET', 'POST'], '/user/{id}/edit', \Taka512\Controller\Admin\UserController::class. ':edit')->setName('admin_user_edit');
    $this->map(['GET'], '/user/signout', \Taka512\Controller\Admin\UserController::class. ':signout')->setName('admin_user_signout');
    // site
    $this->map(['GET'], '/site', \Taka512\Controller\Admin\SiteController::class. ':index')->setName('admin_site_index');
    $this->map(['GET', 'POST'], '/site/create', \Taka512\Controller\Admin\SiteController::class. ':create')->setName('admin_site_create');
    $this->map(['GET', 'POST'], '/site/{id}/edit', \Taka512\Controller\Admin\SiteController::class. ':edit')->setName('admin_site_edit');
})->add(
    new \Taka512\Middleware\AuthenticationMiddleware($c['router'], $c['auth'], $c['view'], $c['repository.user'])
);
