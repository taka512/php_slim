<?php

namespace Taka512;

use Acclimate\Container\CompositeContainer;
use Acclimate\Container\ContainerAcclimator;
use DI\ContainerBuilder;
use Illuminate\Container\Container as IlluminateContainer;
use Illuminate\Database\Connection;
use Illuminate\Database\Connectors\ConnectionFactory;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\Views\Twig;
use Taka512\Util\StdUtil;

class ContainerFactory
{
    private static $pimpleContainer;
    private static $container;

    public static function initContainerOnHttp(ContainerInterface $pimpleContainer): ContainerInterface
    {
        if (isset(self::$container)) {
            return self::$container;
        }
        Env::loadDotenv();
        $builder = new ContainerBuilder();
        $builder->addDefinitions(Env::getSetting());
        self::addHttpDefinitions($builder);
        self::addCommonDefinitions($builder);
        self::$container = self::buildContainer($builder, $pimpleContainer);

        return self::$container;
    }

    public static function initTestContainer(ContainerInterface $pimpleContainer): ContainerInterface
    {
        if (isset(self::$container)) {
            return self::$container;
        }
        Env::loadDotenv();
        $builder = new ContainerBuilder();
        $builder->addDefinitions(Env::getTestSetting());
        self::addHttpDefinitions($builder);
        self::addCommonDefinitions($builder);
        self::$container = self::buildContainer($builder, $pimpleContainer);

        return self::$container;
    }

    public static function initContainerOnBatch(string $batchName, ContainerInterface $pimpleContainer): ContainerInterface
    {
        if (isset(self::$container)) {
            return self::$container;
        }
        Env::loadDotenv();
        $builder = new ContainerBuilder();
        $builder->addDefinitions(Env::getSetting());
        self::addBatchDefinitions($batchName, $builder);
        self::addCommonDefinitions($batchName, $builder);
        self::$container = self::buildContainer($builder, $pimpleContainer);

        return self::$container;
    }

    public static function addHttpDefinitions(ContainerBuilder $builder): void
    {
        $builder->addDefinitions([
            'auth' => function (ContainerInterface $c) {
                return new \Laminas\Authentication\AuthenticationService();
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

    public static function addCommonDefinitions(ContainerBuilder $builder): void
    {
        $builder->addDefinitions([
            Connection::class => function (ContainerInterface $container) {
                $factory = new ConnectionFactory(new IlluminateContainer());
                $connection = $factory->make($container->get('settings')['db']);
                $connection->disableQueryLog();

                return $connection;
            },
            \PDO::class => function (ContainerInterface $container) {
                return $container->get(Connection::class)->getPdo();
            },
        ]);
    }

    public static function buildContainer(ContainerBuilder $builder, ContainerInterface $pimpleContainer): CompositeContainer
    {
        $builder->useAutowiring(false);
        $builder->useAnnotations(true);
        $phpdiContainer = $builder->build();

        $acclimator = new ContainerAcclimator();
        $pimpleContainer = $acclimator->acclimate($pimpleContainer);
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
