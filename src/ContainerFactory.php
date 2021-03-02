<?php

namespace Taka512;

use DI\Container;
use DI\ContainerBuilder;
use Illuminate\Container\Container as IlluminateContainer;
use Illuminate\Database\Connection;
use Illuminate\Database\ConnectionResolver;
use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Database\Eloquent\Model;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Slim\Views\Twig;

class ContainerFactory
{
    private static $container;

    public static function initContainerOnHttp(): ContainerInterface
    {
        if (isset(self::$container)) {
            return self::$container;
        }
        $builder = self::createHttpContainerBuilder();
        self::addCommonDefinitions($builder);
        self::$container = self::buildContainer($builder);
        self::initConnection();

        return self::$container;
    }

    public static function initTestContainer(): ContainerInterface
    {
        if (isset(self::$container)) {
            return self::$container;
        }
        $builder = self::createTestContainerBuilder();
        self::addCommonDefinitions($builder);
        self::$container = self::buildContainer($builder);
        self::initConnection();

        return self::$container;
    }

    public static function initContainerOnBatch(string $batchName): ContainerInterface
    {
        if (isset(self::$container)) {
            return self::$container;
        }
        $builder = self::createBatchContainerBuilder($batchName);
        self::addCommonDefinitions($builder);
        self::$container = self::buildContainer($builder);
        self::initConnection();

        return self::$container;
    }

    public static function initConnection(): void
    {
        $resolver = new ConnectionResolver();
        $resolver->addConnection('default', self::$container->get(Connection::class));
        $resolver->setDefaultConnection('default');
        Model::setConnectionResolver($resolver);
    }

    public static function createHttpContainerBuilder(): ContainerBuilder
    {
        $builder = new ContainerBuilder();
        Env::loadDotenv();
        $builder->addDefinitions(Env::getSetting());
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

        return $builder;
    }

    public static function createTestContainerBuilder(): ContainerBuilder
    {
        $builder = new ContainerBuilder();
        Env::loadDotenv('env.sample');
        $builder->addDefinitions(Env::getTestSetting());
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
                return new NullLogger();
            },
        ]);

        return $builder;
    }

    public static function createBatchContainerBuilder(string $batchName): ContainerBuilder
    {
        $builder = new ContainerBuilder();
        Env::loadDotenv();
        $builder->addDefinitions(Env::getSetting());
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
        return $builder;
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

    public static function buildContainer(ContainerBuilder $builder): Container
    {
        $builder->useAutowiring(false);
        $builder->useAnnotations(true);

        return $builder->build();
    }

    public static function getContainer(): ContainerInterface
    {
        return self::$container;
    }
}
