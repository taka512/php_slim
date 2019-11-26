<?php

namespace Taka512;

use Psr\Container\ContainerInterface;
use Taka512\Util\StdUtil;

class ContainerFactory
{
    private static $container;

    public static function getContainer(): ContainerInterface
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

    public static function getTestContainer(): ContainerInterface
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

    public static function loadCommonService(): void
    {
        self::$container['logger'] = function ($c) {
            return \Taka512\LoggerFactory::getLogger();
        };

        $capsule = new \Illuminate\Database\Capsule\Manager();
        $capsule->addConnection(self::$container['settings']['db']);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
        self::$container['db'] = function () use ($capsule) {
            return $capsule;
        };

        // use migration
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

    public static function loadService(): void
    {
        self::loadAuthService();
        self::loadBatchService();
        self::loadFormService();
        self::loadManagerService();
        self::loadRepositoryService();
    }

    public static function loadAuthService(): void
    {
        self::$container['auth.authentication_adapter'] = function ($c) {
            return new \Taka512\Auth\AuthenticationAdapter(
                $c['repository.user']
            );
        };
    }

    public static function loadBatchService(): void
    {
        self::$container['batch.crawler'] = function ($c) {
            $app = new \Symfony\Component\Console\Application();
            $app->add(new \Taka512\Command\Crawler\SiteCrawlerCommand());

            return $app;
        };
    }

    public static function loadFormService(): void
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

        self::$container['form.admin.tag.create_form'] = function ($c) {
            return new \Taka512\Form\Admin\Tag\CreateForm(
                $c['settings']['form']['csrf_timeout']
            );
        };

        self::$container['form.admin.tag.create_input'] = function ($c) {
            return new \Taka512\Form\Admin\Tag\CreateInput();
        };

        self::$container['form.admin.tag.edit_form'] = function ($c) {
            return new \Taka512\Form\Admin\Tag\EditForm(
                $c['settings']['form']['csrf_timeout']
            );
        };

        self::$container['form.admin.tag.edit_input'] = function ($c) {
            return new \Taka512\Form\Admin\Tag\EditInput();
        };

        self::$container['form.admin.tag.delete_form'] = function ($c) {
            return new \Taka512\Form\Admin\Tag\DeleteForm(
                $c['settings']['form']['csrf_timeout']
            );
        };

        self::$container['form.admin.tag.delete_input'] = function ($c) {
            return new \Taka512\Form\Admin\Tag\DeleteInput();
        };

        self::$container['form.admin.site.create_form'] = function ($c) {
            return new \Taka512\Form\Admin\Site\CreateForm(
                $c['settings']['form']['csrf_timeout']
            );
        };

        self::$container['form.admin.site.create_input'] = function ($c) {
            return new \Taka512\Form\Admin\Site\CreateInput();
        };

        self::$container['form.admin.site.edit_form'] = function ($c) {
            return new \Taka512\Form\Admin\Site\EditForm(
                $c['repository.tag'],
                $c['settings']['form']['csrf_timeout']
            );
        };

        self::$container['form.admin.site.edit_input'] = function ($c) {
            return new \Taka512\Form\Admin\Site\EditInput();
        };

        self::$container['form.api.error_renderer'] = function ($c) {
            return new \Taka512\Form\Api\ErrorRenderer(
                $c['logger']
            );
        };

        self::$container['form.api.tag.search_form'] = function ($c) {
            return new \Taka512\Form\Api\Tag\SearchForm();
        };

        self::$container['form.api.tag.search_input'] = function ($c) {
            return new \Taka512\Form\Api\Tag\SearchInput();
        };

        self::$container['form.api.tag.search_renderer'] = function ($c) {
            return new \Taka512\Form\Api\Tag\SearchRenderer();
        };

        self::$container['form.api.tag_site.create_form'] = function ($c) {
            return new \Taka512\Form\Api\TagSite\CreateForm();
        };

        self::$container['form.api.tag_site.create_input'] = function ($c) {
            return new \Taka512\Form\Api\TagSite\CreateInput(
                $c['repository.tag'],
                $c['repository.site'],
                $c['repository.tag_site']
            );
        };

        self::$container['form.api.tag_site.create_renderer'] = function ($c) {
            return new \Taka512\Form\Api\TagSite\CreateRenderer();
        };
    }

    public static function loadManagerService(): void
    {
        self::$container['manager.tag'] = function ($c) {
            return new \Taka512\Manager\TagManager(
                $c['repository.tag']
            );
        };
    }

    public static function loadRepositoryService(): void
    {
        self::$container['repository.site'] = function ($c) {
            return new \Taka512\Repository\SiteRepository();
        };

        self::$container['repository.tag'] = function ($c) {
            return new \Taka512\Repository\TagRepository();
        };

        self::$container['repository.tag_site'] = function ($c) {
            return new \Taka512\Repository\TagSiteRepository();
        };

        self::$container['repository.user'] = function ($c) {
            return new \Taka512\Repository\UserRepository();
        };
    }
}
