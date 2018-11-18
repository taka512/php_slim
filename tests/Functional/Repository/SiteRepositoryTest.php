<?php

namespace Taka512\Test\Functional\Repository;

use Taka512\Test\DatabaseTestCase;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Taka512\Model\Site;

class SiteRepositoryTest extends DatabaseTestCase
{
    protected function getDataSet()
    {
        return new YamlDataSet(__DIR__.'/SiteRepository.yml');
    }

    /**
     * @dataProvider providerFindOneById
     */
    public function testFindOneById($msg, $id, $expected)
    {
        $actual = $this->get('repository.site')->findOneById($id);
        $this->assertSame($expected, ($actual instanceof Site));
    }

    public function providerFindOneById()
    {
        return [
            ['case id:1 is found', 1, true],
            ['case id:2 is not found(not Site)', 2, false],
        ];
    }
}
