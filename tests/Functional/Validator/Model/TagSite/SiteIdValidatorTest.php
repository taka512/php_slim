<?php

namespace Taka512\Test\Functional\Validator\Model\TagSite;

use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Taka512\Repository\SiteRepository;
use Taka512\Test\DatabaseTestCase;
use Taka512\Validator\Model\TagSite\SiteIdValidator;

class SiteIdValidatorTest extends DatabaseTestCase
{
    protected function getDataSet()
    {
        return new YamlDataSet(__DIR__.'/SiteIdValidator.yml');
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
