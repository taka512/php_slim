<?php

namespace Taka512\Test\E2e\Controller;

use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Taka512\Test\E2eTestCase;
use Taka512\Http\Scraper\ClientFactory;

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
        $client = ClientFactory::createGoutte($this->get('settings')['test']['goutte']);
        $crawler = $client->request('GET', '/');
        $this->assertRegExp($expected, $crawler->html());
    }

    public function providerIndex()
    {
        return [
            ['test site name in site top', '/test yahoo site name/'],
        ];
    }
}
