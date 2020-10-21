<?php

namespace Taka512\Test\E2e\Controller;

use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Taka512\Http\ClientFactory;
use Taka512\Test\E2eTestCase;

class HomeControllerTest extends E2eTestCase
{
    protected function getDataSet()
    {
        return new YamlDataSet(__DIR__.'/HomeController.yml');
    }

    /**
     * @dataProvider providerIndex
     */
    public function testIndex($msg, $expected)
    {
        $client = ClientFactory::createGoutte();
        $crawler = $client->request('GET', $this->getUrl('/'));
        $this->assertRegExp($expected, $crawler->html());
    }

    public function providerIndex()
    {
        return [
            ['test site name in site top', '/test yahoo site name/'],
        ];
    }
}
