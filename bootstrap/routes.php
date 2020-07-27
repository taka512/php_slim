<?php

use Slim\Interfaces\RouteCollectorProxyInterface;

$c = $app->getContainer();

// Routes
$app->get('/', \Taka512\Controller\HomeController::class . ':index')->setName('top');

$app->group('/api', function (RouteCollectorProxyInterface $group) {
    $group->map(['GET'], '', \Taka512\Controller\Api\HomeController::class. ':index')->setName('api_home');
    $group->map(['GET'], '/tag', \Taka512\Controller\Api\TagController::class. ':index')->setName('api_tag');
    $group->map(['POST'], '/tag_site', \Taka512\Controller\Api\TagSiteController::class. ':create')->setName('api_tag_site_create');
})->add(
    new \Taka512\Middleware\Api\ExceptionHandlerMiddleware($c->get('form.api.error_renderer'))
);

$app->map(['GET', 'POST'], '/admin/user/signin', \Taka512\Controller\Admin\UserController::class. ':signin')->setName('admin_user_signin');
$app->group('/admin', function (RouteCollectorProxyInterface $group) {
    $group->map(['GET'], '', \Taka512\Controller\Admin\HomeController::class. ':index')->setName('admin_home_index');
    // user
    $group->map(['GET'], '/user', \Taka512\Controller\Admin\UserController::class. ':index')->setName('admin_user_index');
    $group->map(['GET', 'POST'], '/user/create', \Taka512\Controller\Admin\UserController::class. ':create')->setName('admin_user_create');
    $group->map(['GET', 'POST'], '/user/{id}/edit', \Taka512\Controller\Admin\UserController::class. ':edit')->setName('admin_user_edit');
    $group->map(['GET'], '/user/signout', \Taka512\Controller\Admin\UserController::class. ':signout')->setName('admin_user_signout');
    // site
    $group->map(['GET'], '/site', \Taka512\Controller\Admin\SiteController::class. ':index')->setName('admin_site_index');
    $group->map(['GET', 'POST'], '/site/create', \Taka512\Controller\Admin\SiteController::class. ':create')->setName('admin_site_create');
    $group->map(['GET', 'POST'], '/site/{id}/edit', \Taka512\Controller\Admin\SiteController::class. ':edit')->setName('admin_site_edit');
    // tag
    $group->map(['GET'], '/tag', \Taka512\Controller\Admin\TagController::class. ':index')->setName('admin_tag_index');
    $group->map(['GET', 'POST'], '/tag/create', \Taka512\Controller\Admin\TagController::class. ':create')->setName('admin_tag_create');
    $group->map(['GET', 'POST'], '/tag/{id}/edit', \Taka512\Controller\Admin\TagController::class. ':edit')->setName('admin_tag_edit');
    $group->map(['GET', 'POST'], '/tag/{id}/delete', \Taka512\Controller\Admin\TagController::class. ':delete')->setName('admin_tag_delete');
})->add(
    new \Taka512\Middleware\AuthenticationMiddleware($c->get('auth'), $c->get('view'), $c->get('repository.user'))
);
