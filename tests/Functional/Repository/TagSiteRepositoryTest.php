<?php

namespace Taka512\Test\Functional\Repository;

use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Taka512\Model\TagSite;
use Taka512\Repository\TagSiteRepository;
use Taka512\Test\DatabaseTestCase;

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
        $actual = $this->get(TagSiteRepository::class)->insert($data);
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
        $actual = $this->get(TagSiteRepository::class)->findOneByTagSite($tagId, $siteId);
        $this->assertSame($expected, ($actual instanceof TagSite));
    }

    public function providerFindOneByTagSite()
    {
        return [
            ['case tag_id:1 site_id:1 is found', 1, 1, true],
            ['case tag_id:2 site_id:1 is not found(not TagSite)', 2, 1, false],
        ];
    }

    /**
     * @dataProvider providerDeleteBySiteId
     */
    public function testDeleteBySiteId($msg, $siteId, $expected)
    {
        $actual = $this->get(TagSiteRepository::class)->deleteBySiteId($siteId);
        $this->assertSame($expected, $actual);
    }

    public function providerDeleteBySiteId()
    {
        return [
            ['case site_id:1(one data) is delete', 1, 1],
            ['case site_id:99(no data) is delete', 99, 0],
        ];
    }
}
