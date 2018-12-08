<?php

use Taka512\LoggerFactory;

// WEBからアクセスされる事が前提のサービスを定義
$container = $app->getContainer();
LoggerFactory::initLoggerByApp(
    $container['settings']['logger']['path'],
    $container['settings']['logger']['level']
);

$container['view'] = function ($c) {
    $settings = $c->get('settings')['view'];
    $view = new \Slim\Views\Twig($settings['template_path'], [
#        'cache' => $settings['cache_path']
        'cache' => false
    ]);
    
    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));

    return $view;
};

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
