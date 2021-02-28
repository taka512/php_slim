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
use Slim\Views\Twig;

class ContainerFactory
{
    private static $container;

    public static function initContainerOnHttp(): ContainerInterface
    {
        if (isset(self::$container)) {
            return self::$container;
        }
        Env::loadDotenv();
        $builder = new ContainerBuilder();
        $builder->addDefinitions(Env::getSetting());
        self::addHttpDefinitions($builder);
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
        Env::loadDotenv();
        $builder = new ContainerBuilder();
        $builder->addDefinitions(Env::getTestSetting());
        self::addHttpDefinitions($builder);
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
        Env::loadDotenv();
        $builder = new ContainerBuilder();
        $builder->addDefinitions(Env::getSetting());
        self::addBatchDefinitions($batchName, $builder);
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
