<?php

namespace Taka512\Test\Functional\Repository;

use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Taka512\Model\Site;
use Taka512\Repository\SiteRepository;
use Taka512\Test\DatabaseTestCase;

class SiteRepositoryTest extends DatabaseTestCase
{
    protected function getDataSet()
    {
        return new YamlDataSet(__DIR__.'/SiteRepository.yml');
    }

    /**
     * @dataProvider providerInsert
     */
    public function testInsert($msg, $data, $expected)
    {
        $actual = $this->get(SiteRepository::class)->insert($data);
        $this->assertSame($expected, $actual->id);
    }

    public function providerInsert()
    {
        return [
            [
                'insert success and return id:2',
                [
                    'name' => 'test name2',
                    'url' => 'test url2',
                ],
                2,
            ],
        ];
    }

    /**
     * @dataProvider providerFindOneById
     */
    public function testFindOneById($msg, $id, $expected)
    {
        $actual = $this->get(SiteRepository::class)->findOneById($id);
        $this->assertSame($expected, ($actual instanceof Site));
    }

    public function providerFindOneById()
    {
        return [
            ['case id:1 is found', 1, true],
            ['case id:2 is not found(not Site)', 2, false],
        ];
    }

    /**
     * @dataProvider providerFindLatestSites
     */
    public function testFindLatestSites($msg, $limit, $expected)
    {
        $actual = $this->get(SiteRepository::class)->findLatestSites($limit);
        $this->assertCount($expected, $actual);
    }

    public function providerFindLatestSites()
    {
        return [
            ['site count is 1', 10, 1],
        ];
    }
}
