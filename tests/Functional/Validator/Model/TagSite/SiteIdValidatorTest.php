<?php

namespace Taka512\Test\Functional\Validator\Model\TagSite;

use Nelmio\Alice\Loader\NativeLoader;
use Taka512\Manager\EntityManager;
use Taka512\Repository\SiteRepository;
use Taka512\Test\DatabaseTestCase;
use Taka512\Validator\Model\TagSite\SiteIdValidator;

class SiteIdValidatorTest extends DatabaseTestCase
{
    protected function setUp(): void
    {
        $objectSet = $this->get(NativeLoader::class)->loadFile(__DIR__.'/SiteIdValidator.yml');
        $this->get(EntityManager::class)->truncateTables(['site']);
        $this->get(EntityManager::class)->bulkInsertObjects($objectSet->getObjects());
    }

    /**
     * @dataProvider providerIsValid
     */
    public function testIsValid($msg, $value, $expected)
    {
        $validator = new SiteIdValidator();
        $validator->setSiteRepository($this->get(SiteRepository::class));
        $actual = $validator->isValid($value);
        $this->assertSame($expected, $actual);
    }

    public function providerIsValid()
    {
        return [
            ['site_id:1 is valid', 1, true],
            ['site_id: is invalid', '', false],
            ['site_id:2 is invalid', 2, false],
        ];
    }
}
