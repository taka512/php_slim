<?php

use Slim\Views\Twig;
use Taka512\Env;
use Taka512\LoggerFactory;
use Taka512\HttpErrorHandler;

// WEBからアクセスされる事が前提のサービスを定義
$container = $app->getContainer();
$callableResolver = $app->getCallableResolver();
$responseFactory = $app->getResponseFactory();

LoggerFactory::initLoggerByApp(
    $container['settings']['logger']['path'],
    $container['settings']['logger']['level']
);

// session
$config = new \Zend\Session\Config\SessionConfig();
$config->setOptions([
    'name'  => $container['settings']['session']['cookie_name'],
]);
$session = new \Zend\Session\Container(
    'storage_key',
    new \Zend\Session\SessionManager($config)
);
$container['session'] = function () use ($session) {
    return $session;
};

$container['auth'] = new Zend\Authentication\AuthenticationService();

$container['view'] = function ($c) {
    $settings = $c->get('settings')['view'];
    $twig = Twig::create($settings['template_path'], [
#        'cache' => $settings['cache_path']
        'cache' => false
    ]);
#    if (!Env::isEnvProduction()) {
#        $twig->offsetSet('is_development', true);
#    }

    return $twig;
};

$container['error_handler'] = function ($c) use($callableResolver, $responseFactory) {
    $errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);
    $errorHandler->setLogger($c->get('logger'));
    $errorHandler->setTwigView($c->get('view'));

    return $errorHandler;
};
