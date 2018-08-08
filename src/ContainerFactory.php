<?php

namespace Taka512;

use Slim\Container;
use Taka512\Util\StdUtil;

class ContainerFactory
{
    private static $container;


    public static function getContainer()
    {
        if (isset(self::$container)) {
            return self::$container;
        }
        Env::loadDotenv();
        self::$container = new Container(Env::getSetting());
        self::loadCommonService();
        self::loadService();
        return self::$container;
    }

    public static function loadCommonService()
    {
        self::$container['logger'] = function ($c) {
            $settings = $c['settings']['logger'];
            $logger = new Monolog\Logger($settings['name']);
            $logger->pushProcessor(new Monolog\Processor\UidProcessor());
            $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
            return $logger;
        };

        $capsule = new \Illuminate\Database\Capsule\Manager;
        $capsule->addConnection(self::$container['settings']['db']);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
        self::$container['db'] = function () use ($capsule) {
            return $capsule;
        };

        self::$container['pdo.master'] = function ($c) {
            try {
                $settings = $c['settings']['db'];
                return new \PDO(
                        sprintf('mysql:host=%s;port=3306;dbname=%s', $settings['host'], $settings['database']),
                        $settings['username'],
                        $settings['password']
                        );
            } catch (\Exception $e) {
                throw new \RuntimeException(StdUtil::maskSecret($e->getMessage(), $settings['password']), $e->getCode());
            }
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
                $c['session'],
                $c['settings']['form']['csrf_timeout']
            );
        };

        self::$container['form.site_create_input'] = function ($c) {
            return new \Taka512\Form\SiteCreateInput();
        };

        self::$container['form.site_edit_form'] = function ($c) {
            return new \Taka512\Form\SiteEditForm(
                $c['session'],
                $c['settings']['form']['csrf_timeout']
            );
        };

        self::$container['form.site_edit_input'] = function ($c) {
            return new \Taka512\Form\SiteEditInput();
        };
    }
}
