<?php

namespace Taka512\Test\Functional\Repository;

use Nelmio\Alice\Loader\NativeLoader;
use Taka512\Manager\EntityManager;
use Taka512\Model\TagSite;
use Taka512\Repository\TagSiteRepository;
use Taka512\Test\DatabaseTestCase;

class TagSiteRepositoryTest extends DatabaseTestCase
{
    protected function setUp(): void
    {
        $loader = new NativeLoader();
        $objectSet = $loader->loadFile(__DIR__.'/TagSiteRepository.yml');
        $this->get(EntityManager::class)->truncateTables(['tag_site', 'site', 'tag']);
        $this->get(EntityManager::class)->bulkInsertObjects($objectSet->getObjects());
    }

    public function testInsert()
    {
        $data = [
            'tag_id' => '2',
            'site_id' => '1',
        ];
        $actual = $this->get(TagSiteRepository::class)->insert($data);
        $this->assertInstanceOf(TagSite::class, $actual, 'case:insert success and return object');
    }

    public function testFindOneByTagSite()
    {
        $actual = $this->get(TagSiteRepository::class)->findOneByTagSite(1, 1);
        $this->assertInstanceOf(TagSite::class, $actual, 'case tag_id:1 site_id:1 is found');

        $actual = $this->get(TagSiteRepository::class)->findOneByTagSite(99, 99);
        $this->assertNull($actual, 'case tag_id:99 site_id:99 is not found');
    }

    public function testDeleteBySiteId()
    {
        $actual = $this->get(TagSiteRepository::class)->deleteBySiteId(99);
        $this->assertSame(0, $actual, 'case:site delete is failure');

        $actual = $this->get(TagSiteRepository::class)->deleteBySiteId(1);
        $this->assertSame(1, $actual, 'case:site delete is success');
    }
}
