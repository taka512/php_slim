<?php

namespace Taka512;

class Env
{
    public static function getSetting()
    {
        return require sprintf('%s/../config/local/setting.php', __DIR__);
    }
}
