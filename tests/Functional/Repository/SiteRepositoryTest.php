<?php

namespace Taka512\Test\Functional\Repository;

use Nelmio\Alice\Loader\NativeLoader;
use Taka512\Manager\EntityManager;
use Taka512\Model\Site;
use Taka512\Repository\SiteRepository;
use Taka512\Test\DatabaseTestCase;

class SiteRepositoryTest extends DatabaseTestCase
{
    protected function setUp(): void
    {
        $objectSet = $this->get(NativeLoader::class)->loadFile(__DIR__.'/SiteRepository.yml');
        $this->get(EntityManager::class)->truncateTables(['site']);
        $this->get(EntityManager::class)->bulkInsertObjects($objectSet->getObjects());
    }

    public function testInsert()
    {
        $data = [
            'name' => 'test name2',
            'url' => 'test url2',
        ];
        $actual = $this->get(SiteRepository::class)->insert($data);
        $this->assertInstanceOf(Site::class, $actual);
    }

    public function testFindOneById()
    {
        $actual = $this->get(SiteRepository::class)->findOneById(1);
        $this->assertInstanceOf(Site::class, $actual, 'case:id is found');

        $actual = $this->get(SiteRepository::class)->findOneById(99);
        $this->assertNull($actual, 'case:id is not found');
    }

    public function testFindLatestSites()
    {
        $actual = $this->get(SiteRepository::class)->findLatestSites(10);
        $this->assertCount(1, $actual, 'case: search site');
    }
}
