<?php

namespace Taka512\Test\Functional\Repository;

use Taka512\Test\DatabaseTestCase;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Taka512\Model\TagSite;

class TagSiteRepositoryTest extends DatabaseTestCase
{
    protected function getDataSet()
    {
        return new YamlDataSet(__DIR__.'/TagSiteRepository.yml');
    }

    /**
     * @dataProvider providerInsert
     */
    public function testInsert($msg, $data, $expected)
    {
        $actual = $this->get('repository.tag_site')->insert($data);
        $this->assertSame($expected, ($actual instanceof TagSite));
    }

    public function providerInsert()
    {
        return [
            [
                'insert success and return object',
                [
                    'tag_id' => '2',
                    'site_id' => '1',
                ],
                true,
            ],
        ];
    }

    /**
     * @dataProvider providerFindOneByTagSite
     */
    public function testFindOneByTagSite($msg, $tagId, $siteId, $expected)
    {
        $actual = $this->get('repository.tag_site')->findOneByTagSite($tagId, $siteId);
        $this->assertSame($expected, ($actual instanceof TagSite));
    }

    public function providerFindOneByTagSite()
    {
        return [
            ['case tag_id:1 site_id:1 is found', 1, 1, true],
            ['case tag_id:2 site_id:1 is not found(not TagSite)', 2, 1, false],
        ];
    }
}
