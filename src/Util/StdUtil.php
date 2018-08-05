<?php

namespace Taka512\Util;

class StdUtil
{
    public static function maskSecret($str, $secret)
    {
        return str_replace($secret, str_repeat('*', 10), $str);
    }
}
