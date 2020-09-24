<?php

namespace Taka512\Test\Functional\Manager;

use Taka512\Manager\TagManager;
use Taka512\Test\DatabaseTestCase;
use PHPUnit\DbUnit\DataSet\YamlDataSet;

class TagManagerTest extends DatabaseTestCase
{
    protected function getDataSet()
    {
        return new YamlDataSet(__DIR__.'/TagManager.yml');
    }

    /**
     * @dataProvider providerGetTagPagenate
     */
    public function testGetTagPagenate($msg, $page, $limit, $expected)
    {
        $actual = $this->get(TagManager::class)->getTagPagenate($page, $limit);
        $this->assertCount($expected, $actual->getCurrentPageResults());
    }

    public function providerGetTagPagenate()
    {
        return [
            ['get pagenate (page:1 limit:10)', 1, 10, 4],
            ['get pagenate (page:1 limit:2)', 1, 2, 2],
        ];
    }
}
