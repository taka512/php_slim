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

    public static function getTestContainer()
    {
        if (isset(self::$container)) {
            return self::$container;
        }
        Env::loadDotenv('env.sample');
        self::$container = new Container(Env::getTestSetting());
        self::loadCommonService();
        self::loadService();

        return self::$container;
    }

    public static function loadCommonService()
    {
        self::$container['logger'] = function ($c) {
            $settings = $c['settings']['logger'];
            $logger = new \Monolog\Logger($settings['name']);
            $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
            $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));

            return $logger;
        };

        $capsule = new \Illuminate\Database\Capsule\Manager();
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
                    $c['settings']['db']['username'],
                    $c['settings']['db']['password']
                );
            } catch (\Exception $e) {
                throw new \RuntimeException(StdUtil::maskSecret($e->getMessage(), $c['settings']['db']['password']), $e->getCode());
            }
        };
    }

    public static function loadService()
    {
        self::loadAuthService();
        self::loadFormService();
        self::loadRepositoryService();
    }

    public static function loadAuthService()
    {
        self::$container['auth.authentication_adapter'] = function ($c) {
            return new \Taka512\Auth\AuthenticationAdapter(
                $c['repository.user']
            );
        };
    }

    public static function loadFormService()
    {
        self::$container['form.admin.user.signin_form'] = function ($c) {
            return new \Taka512\Form\Admin\User\SigninForm(
                $c['settings']['form']['csrf_timeout']
            );
        };

        self::$container['form.admin.user.signin_input'] = function ($c) {
            return new \Taka512\Form\Admin\User\SigninInput();
        };

        self::$container['form.admin.user.create_form'] = function ($c) {
            return new \Taka512\Form\Admin\User\CreateForm(
                $c['settings']['form']['csrf_timeout']
            );
        };

        self::$container['form.admin.user.create_input'] = function ($c) {
            return new \Taka512\Form\Admin\User\CreateInput(
                $c['repository.user']
            );
        };

        self::$container['form.admin.user.edit_form'] = function ($c) {
            return new \Taka512\Form\Admin\User\EditForm(
                $c['settings']['form']['csrf_timeout']
            );
        };

        self::$container['form.admin.user.edit_input'] = function ($c) {
            return new \Taka512\Form\Admin\User\EditInput(
                $c['repository.user']
            );
        };

        self::$container['form.site_create_form'] = function ($c) {
            return new \Taka512\Form\SiteCreateForm(
                $c['settings']['form']['csrf_timeout']
            );
        };

        self::$container['form.site_create_input'] = function ($c) {
            return new \Taka512\Form\SiteCreateInput();
        };

        self::$container['form.site_edit_form'] = function ($c) {
            return new \Taka512\Form\SiteEditForm(
                $c['settings']['form']['csrf_timeout']
            );
        };

        self::$container['form.site_edit_input'] = function ($c) {
            return new \Taka512\Form\SiteEditInput();
        };
    }

    public static function loadRepositoryService()
    {
        self::$container['repository.site'] = function ($c) {
            return new \Taka512\Repository\SiteRepository();
        };

        self::$container['repository.user'] = function ($c) {
            return new \Taka512\Repository\UserRepository();
        };
    }
}
