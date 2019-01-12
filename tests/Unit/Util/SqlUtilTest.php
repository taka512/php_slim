<?php

namespace Taka512\Test\Unit\Util;

use Taka512\Test\TestCase;
use Taka512\Util\SqlUtil;

class SqlUtilTest extends TestCase
{
    /**
     * @dataProvider providerEscapeLike
     */
    public function testEscapeLike($msg, $str, $expected)
    {
        $actual = SqlUtil::escapeLike($str);
        $this->assertSame($expected, $actual);
    }

    public function providerEscapeLike()
    {
        return [
            ['[hoge%] is escape [hoge\%]', 'hoge%', 'hoge\%'],
        ];
    }
}
