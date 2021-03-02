<?php

namespace Taka512\Test\Functional\Repository;

use Nelmio\Alice\Loader\NativeLoader;
use Taka512\Manager\EntityManager;
use Taka512\Model\Tag;
use Taka512\Repository\TagRepository;
use Taka512\Test\DatabaseTestCase;

class TagRepositoryTest extends DatabaseTestCase
{
    protected function setUp(): void
    {
        $objectSet = $this->get(NativeLoader::class)->loadFile(__DIR__.'/TagRepository.yml');
        $this->get(EntityManager::class)->truncateTables(['tag', 'site', 'tag_site']);
        $this->get(EntityManager::class)->bulkInsertObjects($objectSet->getObjects());
    }

    public function testInsert()
    {
        $data = [
           'name' => 'test name5',
        ];
        $actual = $this->get(TagRepository::class)->insert($data);
        $this->assertSame(5, $actual, 'case:insert success and return id');
    }

    public function testFindOneById()
    {
        $actual = $this->get(TagRepository::class)->findOneById(1);
        $this->assertInstanceOf(Tag::class, $actual, 'case:id is exists and return instance');
        $actual = $this->get(TagRepository::class)->findOneById(99);
        $this->assertNull($actual, 'case:id is not found');
    }

    public function testFindBySearchConditions()
    {
        $conditions = ['name' => 'tag', 'offset' => 0, 'limit' => 30];
        $actual = $this->get(TagRepository::class)->findBySearchConditions($conditions);
        $this->assertCount(3, $actual, 'case:search name');

        $conditions = ['site_id' => '1', 'offset' => 0, 'limit' => 30];
        $actual = $this->get(TagRepository::class)->findBySearchConditions($conditions);
        $this->assertCount(2, $actual, 'case:search site_id');

        $conditions = ['name' => 'tag1', 'site_id' => '1', 'offset' => 0, 'limit' => 30];
        $actual = $this->get(TagRepository::class)->findBySearchConditions($conditions);
        $this->assertCount(1, $actual, 'case:search site_id, name');

        $conditions = ['offset' => 0, 'limit' => 30];
        $actual = $this->get(TagRepository::class)->findBySearchConditions($conditions);
        $this->assertCount(4, $actual, 'case:search all');

        $conditions = ['offset' => 0, 'limit' => 1];
        $actual = $this->get(TagRepository::class)->findBySearchConditions($conditions);
        $this->assertCount(1, $actual, 'case:search limit 1');

        $conditions = ['offset' => 1, 'limit' => 30];
        $actual = $this->get(TagRepository::class)->findBySearchConditions($conditions);
        $this->assertCount(3, $actual, 'case:search offset 1');
    }

    public function testFindLatestTags()
    {
        $actual = $this->get(TagRepository::class)->findLatestTags(0, 2);
        $this->assertCount(2, $actual, 'case:count limit 2');

        $actual = $this->get(TagRepository::class)->findLatestTags(0, null);
        $this->assertCount(4, $actual, 'case:count limit is null');

        $actual = $this->get(TagRepository::class)->findLatestTags(null, null);
        $this->assertCount(4, $actual, 'case:count offset,limit is null');

        $actual = $this->get(TagRepository::class)->findLatestTags(3, 10);
        $this->assertCount(1, $actual, 'case:offset is 3,limit is 10');
    }

    public function testCount()
    {
        $actual = $this->get(TagRepository::class)->count();
        $this->assertSame(4, $actual, 'count all tag');
    }
}
