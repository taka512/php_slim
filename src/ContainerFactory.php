<?php

namespace Taka512;

use Slim\Container;

class ContainerFactory
{
    private static $container;


    public static function getContainer()
    {
        if (isset(self::$container)) {
            return self::$container;
        }
        self::$container = new Container(Env::getSetting());
        self::loadCommonService();
        self::loadService();
        return self::$container;
    }

    public static function loadCommonService()
    {
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
    }

    public static function loadService()
    {
        self::loadFormService();
    }

    public static function loadFormService()
    {
        self::$container['form.site_create_form'] = function ($c) {
            return new \Taka512\Form\SiteCreateForm(
                    $c->get('session'),
                    $c->get('settings')['form']['csrf_timeout']
                    );
        };

        self::$container['form.site_create_input'] = function ($c) {
            return new \Taka512\Form\SiteCreateInput();
        };

        self::$container['form.site_edit_form'] = function ($c) {
            return new \Taka512\Form\SiteEditForm(
                    $c->get('session'),
                    $c->get('settings')['form']['csrf_timeout']
                    );
        };

        self::$container['form.site_edit_input'] = function ($c) {
            return new \Taka512\Form\SiteEditInput();
        };
    }
}
