<?php

namespace Taka512\Test\Functional\Validator\Model\TagSite;

use Nelmio\Alice\Loader\NativeLoader;
use Taka512\Manager\EntityManager;
use Taka512\Repository\TagRepository;
use Taka512\Repository\TagSiteRepository;
use Taka512\Test\DatabaseTestCase;
use Taka512\Validator\Model\TagSite\TagIdValidator;

class TagIdValidatorTest extends DatabaseTestCase
{
    protected function setUp(): void
    {
        $objectSet = $this->get(NativeLoader::class)->loadFile(__DIR__.'/TagIdValidator.yml');
        $this->get(EntityManager::class)->truncateTables(['tag_site', 'tag', 'site']);
        $this->get(EntityManager::class)->bulkInsertObjects($objectSet->getObjects());
    }

    /**
     * @dataProvider providerIsValid
     */
    public function testIsValid($msg, $value, $context, $expected)
    {
        $validator = new TagIdValidator();
        $validator->setTagRepository($this->get(TagRepository::class));
        $validator->setTagSiteRepository($this->get(TagSiteRepository::class));
        $actual = $validator->isValid($value, $context);
        $this->assertSame($expected, $actual);
    }

    public function providerIsValid()
    {
        return [
            ['tag_id:2 site_id:1 is valid', 2, ['site_id' => 1], true],
            ['tag_id: site_id:1 is invalid', '', ['site_id' => 1], false],
            ['tag_id:3 site_id:1 is invalid', 3, ['site_id' => 1], false],
            ['tag_id:1 site_id:1 is invalid', 1, ['site_id' => 1], false],
        ];
    }

    /**
     * @dataProvider providerIsDuplicated
     */
    public function testIsDuplicated($msg, $tagId, $siteId, $expected)
    {
        $validator = new TagIdValidator();
        $validator->setTagRepository($this->get(TagRepository::class));
        $validator->setTagSiteRepository($this->get(TagSiteRepository::class));
        $actual = $validator->isDuplicated($tagId, $siteId);
        $this->assertSame($expected, $actual);
    }

    public function providerIsDuplicated()
    {
        return [
            ['tag_id:2 site_id:1 is duplicated false', 2, 1, false],
            ['tag_id:1 site_id:1 is duplicated', 1, 1, true],
        ];
    }
}
