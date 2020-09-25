<?php

namespace Taka512\Test\Unit\Model;

use Taka512\Model\TagSite;
use Taka512\Test\TestCase;

class TagSiteTest extends TestCase
{
    /**
     * @dataProvider providerSetFormArray
     */
    public function testSetFormArray($msg, $expected)
    {
        $tagSite = new TagSite();
        $tagSite->setFormArray($expected);
        $this->assertSame(
            $expected,
            [
                'tag_id' => $tagSite->tagId,
                'site_id' => $tagSite->siteId,
            ]
        );
    }

    public function providerSetFormArray()
    {
        return [
            [
                'set array data test',
                [
                    'tag_id' => '1',
                    'site_id' => '1',
                ],
            ],
        ];
    }

    /**
     * @dataProvider providerGetFormArray
     */
    public function testGetFormArray($msg, $data, $expected)
    {
        $tagSite = new TagSite();
        $tagSite->tagId = $data['tag_id'];
        $tagSite->siteId = $data['site_id'];
        $tagSite->createdAt = $data['created_at'];
        $actual = $tagSite->getFormArray();
        $this->assertSame($expected, $actual);
    }

    public function providerGetFormArray()
    {
        return [
            [
                'set array data test',
                [
                    'tag_id' => '1',
                    'site_id' => '1',
                    'created_at' => new \DateTime('2019/01/01 01:01:01'),
                ],
                [
                    'tag_id' => '1',
                    'site_id' => '1',
                    'created_at' => '2019-01-01 01:01:01',
                ],
            ],
        ];
    }
}
