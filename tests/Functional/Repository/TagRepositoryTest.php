<?php

namespace Taka512\Test\Functional\Repository;

use Taka512\Test\DatabaseTestCase;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Taka512\Model\Tag;

class TagRepositoryTest extends DatabaseTestCase
{
    protected function getDataSet()
    {
        return new YamlDataSet(__DIR__.'/TagRepository.yml');
    }

    /**
     * @dataProvider providerInsert
     */
    public function testInsert($msg, $data, $expected)
    {
        $actual = $this->get('repository.tag')->insert($data);
        $this->assertSame($expected, $actual);
    }

    public function providerInsert()
    {
        return [
            [
                'insert success and return id:2',
                [
                    'name' => 'test name2',
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
        $actual = $this->get('repository.tag')->findOneById($id);
        $this->assertSame($expected, ($actual instanceof Tag));
    }

    public function providerFindOneById()
    {
        return [
            ['case id:1 is found', 1, true],
            ['case id:2 is not found(not Tag)', 2, false],
        ];
    }

    /**
     * @dataProvider providerFindLatestTags
     */
    public function testFindLatestTags($msg, $limit, $expected)
    {
        $actual = $this->get('repository.tag')->findLatestTags($limit);
        $this->assertCount($expected, $actual);
    }

    public function providerFindLatestTags()
    {
        return [
            ['site count is 1', 10, 1],
        ];
    }
}
