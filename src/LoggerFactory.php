<?php

namespace Taka512;

use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Handler\StreamHandler;

class LoggerFactory
{
    protected static $logger;

    public static function getLogger(): LoggerInterface
    {
        return self::$logger;
    }

    public static function initLoggerByApp($path, int $level = Logger::DEBUG): void
    {
        $logger = new Logger('app');
        $logger->pushProcessor(new IntrospectionProcessor(Logger::WARNING));
        if (is_resource($path) || $path == 'php://stdout') {
            $logger->pushHandler(new StreamHandler($path, $level));
        } else {
            $now = new \DateTime();
            $logger->pushHandler(new StreamHandler($path.'/app.log-'.$now->format('Ymd'), $level));
        }
        self::$logger = $logger;
    }

    public static function initLoggerByBatch(string $name, $path, int $level = Logger::DEBUG): void
    {
        $logger = new Logger($name);
        $logger->pushProcessor(new IntrospectionProcessor(Logger::WARNING));
        if (is_resource($path)) {
            $logger->pushHandler(new StreamHandler($path, $level));
        } else {
            $now = new \DateTime();
            $logger->pushHandler(new StreamHandler($path.'/batch.log-'.$now->format('Ymd'), $level));
            $logger->pushHandler(new StreamHandler('php://stdout', $level));
        }
        self::$logger = $logger;
    }
}
