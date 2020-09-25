<?php

namespace Taka512\Test\Unit\Model;

use Taka512\Model\Site;
use Taka512\Test\TestCase;

class SiteTest extends TestCase
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

    /**
     * @dataProvider providerSetFormArray
     */
    public function testSetFormArray($msg, $expected)
    {
        $site = new Site();
        $site->setFormArray($expected);
        $this->assertSame(
            $expected,
            [
                'name' => $site->name,
                'url' => $site->url,
                'del_flg' => $site->delFlg,
            ]
        );
    }

    public function providerSetFormArray()
    {
        return [
            [
                'set array data test',
                [
                    'name' => 'test name',
                    'url' => 'test url',
                    'del_flg' => Site::FLG_ON,
                ],
            ],
        ];
    }
}
