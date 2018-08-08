<?php

namespace Taka512;

use Dotenv\Dotenv;

class Env
{
    protected static $isLoadDotenv = false;

    public static function loadDotenv($envFile = null)
    {
        if (self::$isLoadDotenv) {
            return true;
        }

        if (isset($envFile)) {
            $dotenv = new Dotenv(__DIR__.'/../'.$envFile);
        } else {
            $dotenv = new Dotenv(__DIR__.'/../');
        }
        $dotenv->load();
        self::$isLoadDotenv = true;

        return true;
    }


    public static function getSetting()
    {
        return require sprintf('%s/../config/local/setting.php', __DIR__);
    }
}
