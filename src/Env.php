<?php

namespace Taka512;

use Dotenv\Dotenv;

class Env
{
    const LOCAL = 'local';
    const PROD = 'prod';

    protected static $isLoadDotenv = false;

    public static function loadDotenv($envFile = null): bool
    {
        if (self::$isLoadDotenv) {
            return true;
        }

        if (isset($envFile)) {
            $dotenv = Dotenv::createImmutable(__DIR__.'/../', $envFile);
        } else {
            $dotenv = Dotenv::createImmutable(__DIR__.'/../');
        }
        $dotenv->load();
        self::$isLoadDotenv = true;

        return true;
    }

    public static function getSetting(): array
    {
        return require sprintf('%s/../config/%s/setting.php', __DIR__, self::getEnvironment());
    }

    public static function getTestSetting(): array
    {
        return require sprintf('%s/../config/%s_test/setting.php', __DIR__, self::getEnvironment());
    }

    public static function getEnvironment(): string
    {
        $env = self::getenv('APP_ENV');
        if (self::LOCAL !== $env && self::PROD !== $env) {
            throw new \RuntimeException('APP_ENV is invalid value:'.$env);
        }

        return $env;
    }

    public static function isEnvProduction(): bool
    {
        return self::PROD === self::getEnvironment();
    }

    public static function getenv(string $key)
    {
        return $_ENV['APP_ENV'] ?? null;
    }
}
