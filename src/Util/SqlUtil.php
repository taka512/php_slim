<?php

namespace Taka512\Util;

class SqlUtil
{
    public static function escapeLike(string $str): string
    {
        // LIKEで使われるワイルドカード(%)をエスケープ処理
        return mb_ereg_replace('%', '\%', $str);
    }
}
