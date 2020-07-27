<?php

namespace Taka512;

use Acclimate\Container\CompositeContainer;
use Acclimate\Container\ContainerAcclimator;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Taka512\Util\StdUtil;
use Slim\Views\Twig;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Handler\StreamHandler;

class ContainerFactory
{
    private static $pimpleContainer;
    private static $container;

    public static function initContainerOnHttp(ContainerInterface $pimpleContainer): ContainerInterface
    {
        Env::loadDotenv();
        $builder = new ContainerBuilder();
        $builder->addDefinitions(Env::getSetting());
        self::addHttpDefinitions($builder);
        self::$container = self::buildContainer($builder, $pimpleContainer);

        return self::$container;
    }

    public static function initContainerOnBatch(string $batchName, ContainerInterface $pimpleContainer): ContainerInterface
    {
        Env::loadDotenv();
        $builder = new ContainerBuilder();
        $builder->addDefinitions(Env::getSetting());
        self::addBatchDefinitions($batchName, $builder);
        self::$container = self::buildContainer($builder, $pimpleContainer);

        return self::$container;
    }

    public static function addHttpDefinitions(ContainerBuilder $builder): void
    {
        $builder->addDefinitions([
            'auth' => function (ContainerInterface $c) {
                return new \Zend\Authentication\AuthenticationService();
            },
            'view' => function (ContainerInterface $c) {
                $settings = $c->get('settings')['view'];
                $twig = Twig::create($settings['template_path'], [
                    //'cache' => $settings['cache_path']
                    'cache' => false,
                ]);
                if (!Env::isEnvProduction()) {
                    //$twig->offsetSet('is_development', true);
                }

                return $twig;
            },
            'session' => function (ContainerInterface $c) {
                $settings = $c->get('settings');

                $config = new \Zend\Session\Config\SessionConfig();
                $config->setOptions([
                    'name' => $settings['session']['cookie_name'],
                ]);

                return new \Zend\Session\Container(
                    'storage_key',
                    new \Zend\Session\SessionManager($config)
                );
            },
            LoggerInterface::class => function (ContainerInterface $c) {
                $path = $c->get('settings')['logger']['path'];
                $level = $c->get('settings')['logger']['level'];

                $logger = new Logger('app');
                $logger->pushProcessor(new IntrospectionProcessor(Logger::WARNING));
                if (is_resource($path) || 'php://stdout' == $path) {
                    $logger->pushHandler(new StreamHandler($path, $level));
                } else {
                    $now = new \DateTime();
                    $logger->pushHandler(new StreamHandler($path.'/app.log-'.$now->format('Ymd'), $level));
                }

                return $logger;
            },
        ]);
    }

    public static function addBatchDefinitions(string $batchName, ContainerBuilder $builder): void
    {
        $builder->addDefinitions([
            LoggerInterface::class => function (ContainerInterface $c) use ($batchName) {
                $path = $c->get('settings')['logger']['path'];
                $level = $c->get('settings')['logger']['level'];

                $logger = new Logger($batchName);
                $logger->pushProcessor(new IntrospectionProcessor(Logger::WARNING));
                if (is_resource($path)) {
                    $logger->pushHandler(new StreamHandler($path, $level));
                } else {
                    $now = new \DateTime();
                    $logger->pushHandler(new StreamHandler($path.'/batch.log-'.$now->format('Ymd'), $level));
                    $logger->pushHandler(new StreamHandler('php://stdout', $level));
                }

                return $logger;
            },
        ]);
    }

    public static function buildContainer(ContainerBuilder $builder, ContainerInterface $pimpleContainer): CompositeContainer
    {
        $builder->useAutowiring(false);
        $builder->useAnnotations(false);
        $phpdiContainer = $builder->build();

        $acclimator = new ContainerAcclimator();
        if (is_null($pimpleContainer)) {
            $pimpleContainer = $acclimator->acclimate(self::$pimpleContainer);
        }
        $phpdiContainer = $acclimator->acclimate($phpdiContainer);

        return new CompositeContainer([$pimpleContainer, $phpdiContainer]);
    }

    public static function getContainer(): ContainerInterface
    {
        return self::$container;
    }

    public static function getPimpleContainer(): ContainerInterface
    {
        if (isset(self::$pimpleContainer)) {
            return self::$pimpleContainer;
        }
        Env::loadDotenv();
        self::$pimpleContainer = new Container(Env::getSetting());
        self::loadCommonService();
        self::loadService();

        return self::$pimpleContainer;
    }

    public static function getTestPimpleContainer(): ContainerInterface
    {
        if (isset(self::$pimpleContainer)) {
            return self::$pimpleContainer;
        }
        Env::loadDotenv('env.sample');
        self::$pimpleContainer = new Container(Env::getTestSetting());
        self::loadCommonService();
        self::loadService();

        return self::$pimpleContainer;
    }

    public static function loadCommonService(): void
    {
        self::$pimpleContainer['logger'] = function ($c) {
            return \Taka512\LoggerFactory::getLogger();
        };

        $capsule = new \Illuminate\Database\Capsule\Manager();
        $capsule->addConnection(self::$pimpleContainer['settings']['db']);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
        self::$pimpleContainer['db'] = function () use ($capsule) {
            return $capsule;
        };

        // use migration
        self::$pimpleContainer['pdo.master'] = function ($c) {
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
        self::loadFormService();
        self::loadManagerService();
        self::loadRepositoryService();
    }

    public static function loadAuthService(): void
    {
        self::$pimpleContainer['auth.authentication_adapter'] = function ($c) {
            return new \Taka512\Auth\AuthenticationAdapter(
                $c['repository.user']
            );
        };
    }

    public static function loadFormService(): void
    {
        self::$pimpleContainer['form.admin.user.signin_form'] = function ($c) {
            return new \Taka512\Form\Admin\User\SigninForm(
                $c['settings']['form']['csrf_timeout']
            );
        };

        self::$pimpleContainer['form.admin.user.signin_input'] = function ($c) {
            return new \Taka512\Form\Admin\User\SigninInput();
        };

        self::$pimpleContainer['form.admin.user.create_form'] = function ($c) {
            return new \Taka512\Form\Admin\User\CreateForm(
                $c['settings']['form']['csrf_timeout']
            );
        };

        self::$pimpleContainer['form.admin.user.create_input'] = function ($c) {
            return new \Taka512\Form\Admin\User\CreateInput(
                $c['repository.user']
            );
        };

        self::$pimpleContainer['form.admin.user.edit_form'] = function ($c) {
            return new \Taka512\Form\Admin\User\EditForm(
                $c['settings']['form']['csrf_timeout']
            );
        };

        self::$pimpleContainer['form.admin.user.edit_input'] = function ($c) {
            return new \Taka512\Form\Admin\User\EditInput(
                $c['repository.user']
            );
        };

        self::$pimpleContainer['form.admin.tag.create_form'] = function ($c) {
            return new \Taka512\Form\Admin\Tag\CreateForm(
                $c['settings']['form']['csrf_timeout']
            );
        };

        self::$pimpleContainer['form.admin.tag.create_input'] = function ($c) {
            return new \Taka512\Form\Admin\Tag\CreateInput();
        };

        self::$pimpleContainer['form.admin.tag.edit_form'] = function ($c) {
            return new \Taka512\Form\Admin\Tag\EditForm(
                $c['settings']['form']['csrf_timeout']
            );
        };

        self::$pimpleContainer['form.admin.tag.edit_input'] = function ($c) {
            return new \Taka512\Form\Admin\Tag\EditInput();
        };

        self::$pimpleContainer['form.admin.tag.delete_form'] = function ($c) {
            return new \Taka512\Form\Admin\Tag\DeleteForm(
                $c['settings']['form']['csrf_timeout']
            );
        };

        self::$pimpleContainer['form.admin.tag.delete_input'] = function ($c) {
            return new \Taka512\Form\Admin\Tag\DeleteInput();
        };

        self::$pimpleContainer['form.admin.site.create_form'] = function ($c) {
            return new \Taka512\Form\Admin\Site\CreateForm(
                $c['settings']['form']['csrf_timeout']
            );
        };

        self::$pimpleContainer['form.admin.site.create_input'] = function ($c) {
            return new \Taka512\Form\Admin\Site\CreateInput();
        };

        self::$pimpleContainer['form.admin.site.edit_form'] = function ($c) {
            return new \Taka512\Form\Admin\Site\EditForm(
                $c['repository.tag'],
                $c['settings']['form']['csrf_timeout']
            );
        };

        self::$pimpleContainer['form.admin.site.edit_input'] = function ($c) {
            return new \Taka512\Form\Admin\Site\EditInput();
        };

        self::$pimpleContainer['form.api.error_renderer'] = function ($c) {
            return new \Taka512\Form\Api\ErrorRenderer(
                $c['logger']
            );
        };

        self::$pimpleContainer['form.api.tag.search_form'] = function ($c) {
            return new \Taka512\Form\Api\Tag\SearchForm();
        };

        self::$pimpleContainer['form.api.tag.search_input'] = function ($c) {
            return new \Taka512\Form\Api\Tag\SearchInput();
        };

        self::$pimpleContainer['form.api.tag.search_renderer'] = function ($c) {
            return new \Taka512\Form\Api\Tag\SearchRenderer();
        };

        self::$pimpleContainer['form.api.tag_site.create_form'] = function ($c) {
            return new \Taka512\Form\Api\TagSite\CreateForm();
        };

        self::$pimpleContainer['form.api.tag_site.create_input'] = function ($c) {
            return new \Taka512\Form\Api\TagSite\CreateInput(
                $c['repository.tag'],
                $c['repository.site'],
                $c['repository.tag_site']
            );
        };

        self::$pimpleContainer['form.api.tag_site.create_renderer'] = function ($c) {
            return new \Taka512\Form\Api\TagSite\CreateRenderer();
        };
    }

    public static function loadManagerService(): void
    {
        self::$pimpleContainer['manager.tag'] = function ($c) {
            return new \Taka512\Manager\TagManager(
                $c['repository.tag']
            );
        };
    }

    public static function loadRepositoryService(): void
    {
        self::$pimpleContainer['repository.site'] = function ($c) {
            return new \Taka512\Repository\SiteRepository();
        };

        self::$pimpleContainer['repository.tag'] = function ($c) {
            return new \Taka512\Repository\TagRepository();
        };

        self::$pimpleContainer['repository.tag_site'] = function ($c) {
            return new \Taka512\Repository\TagSiteRepository();
        };

        self::$pimpleContainer['repository.user'] = function ($c) {
            return new \Taka512\Repository\UserRepository();
        };
    }
}
