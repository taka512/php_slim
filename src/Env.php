<?php

namespace Taka512;

use Dotenv\Dotenv;

class Env
{
    const LOCAL = 'local';
    const PROD = 'prod';

    protected static $isLoadDotenv = false;

    public static function loadDotenv($envFile = null)
    {
        if (self::$isLoadDotenv) {
            return true;
        }

        if (isset($envFile)) {
            $dotenv = new Dotenv(__DIR__.'/../', $envFile);
        } else {
            $dotenv = new Dotenv(__DIR__.'/../');
        }
        $dotenv->load();
        self::$isLoadDotenv = true;

        return true;
    }


    public static function getSetting()
    {
        return require sprintf('%s/../config/%s/setting.php', __DIR__, self::getEnvironment());
    }

    public static function getTestSetting()
    {
        return require sprintf('%s/../config/%s_test/setting.php', __DIR__, self::getEnvironment());
    }

    public static function getEnvironment()
    {
        $env = getenv('APP_ENV');
        if ($env !== self::LOCAL && $env !== self::PROD) {
            throw new \RuntimeException('APP_ENV is invalid value:'.$env);
        }

        return $env;
    }

}
