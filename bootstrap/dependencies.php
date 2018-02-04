<?php
// DIC configuration

$container = $app->getContainer();

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

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// Service factory for the ORM
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();
$container['db'] = function () use ($capsule) {
    return $capsule;
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

$container['form.site_create_form'] = function ($c) {
    return new \Taka512\Form\SiteCreateForm(
        $c->get('session'),
        $c->get('settings')['form']['csrf_timeout']
    );
};

$container['form.site_create_input'] = function ($c) {
    return new \Taka512\Form\SiteCreateInput();
};

$container['form.site_edit_form'] = function ($c) {
    return new \Taka512\Form\SiteEditForm(
        $c->get('session'),
        $c->get('settings')['form']['csrf_timeout']
    );
};

$container['form.site_edit_input'] = function ($c) {
    return new \Taka512\Form\SiteEditInput();
};
