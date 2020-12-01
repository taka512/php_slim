<?php

namespace Taka512\Util;

class StdUtil
{
    public static function maskSecret(string $str, string $secret): string
    {
        return str_replace($secret, str_repeat('*', 10), $str);
    }

    public static function snakeCase($string)
    {
	$string = preg_replace('/([A-Z])/', '_$1', $string);
	$string = strtolower($string);
	return ltrim($string, '_');
    }
}
