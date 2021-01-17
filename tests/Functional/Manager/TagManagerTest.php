<?php

namespace Taka512\Test\Functional\Manager;

use Nelmio\Alice\Loader\NativeLoader;
use Taka512\Manager\EntityManager;
use Taka512\Manager\TagManager;
use Taka512\Test\DatabaseTestCase;

class TagManagerTest extends DatabaseTestCase
{
    protected function setUp(): void
    {
        $loader = new NativeLoader();
        $objectSet = $loader->loadFile(__DIR__.'/TagManager.yml');
        $this->get(EntityManager::class)->truncateTables(['tag']);
        $this->get(EntityManager::class)->bulkInsertObjects($objectSet->getObjects());
    }

    public function testGetTagPagenate()
    {
        $actual = $this->get(TagManager::class)->getTagPagenate(1, 10);
        $this->assertCount(4, $actual->getCurrentPageResults(), 'get pagenate limit:10');

        $actual = $this->get(TagManager::class)->getTagPagenate(1, 2);
        $this->assertCount(2, $actual->getCurrentPageResults(), 'get pagenate limit:2');
    }
}
