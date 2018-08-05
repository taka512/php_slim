<?php

namespace Taka512\Test\Unit\Model;

use Taka512\Model\Site;

class SiteTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerIsDelete
     */
    public function testIsDelete($msg, $delFlg, $expected)
    {
        $site = new Site();
        $site->setFormArray(['del_flg' => $delFlg]);
        $actual = $site->isDelete();
        $this->assertSame($expected, $actual);
    }

    public function providerIsDelete()
    {
        return [
            ['FLAGがONの場合、true', Site::FLG_ON, true],
            ['FLAGがOFFの場合、false', Site::FLG_OFF, false],
        ];
    }
}
