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
                'insert success and return id:5',
                [
                    'name' => 'test name5',
                ],
                5,
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
            ['case id:99 is not found(not Tag)', 99, false],
        ];
    }

    /**
     * @dataProvider providerFindBySearchConditions
     */
    public function testFindBySearchConditions($msg, $conditions, $expected)
    {
        $actual = $this->get('repository.tag')->findBySearchConditions($conditions);
        $this->assertCount($expected, $actual);
    }

    public function providerFindBySearchConditions()
    {
        return [
            ['tag count is 3(search name:tag)', ['name' => 'tag', 'offset' => 0, 'limit' => 30], 3],
            ['tag count is 2(search site_id:1)', ['site_id' => '1', 'offset' => 0, 'limit' => 30], 2],
            ['tag count is 1(search site_id:1, name:tag1)', ['name' => 'tag1', 'site_id' => '1', 'offset' => 0, 'limit' => 30], 1],
            ['tag count is 4(search all)', ['offset' => 0, 'limit' => 30], 4],
            ['tag count is 1(search limit 1)', ['offset' => 0, 'limit' => 1], 1],
            ['tag count is 3(search offset 1)', ['offset' => 1, 'limit' => 30], 3],
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
            ['tag count is 4(limit 10)', 10, 4],
        ];
    }
}
