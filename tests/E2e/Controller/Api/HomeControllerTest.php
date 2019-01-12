<?php

namespace Taka512\Test\E2e\Controller\Api;

use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Taka512\Test\E2eTestCase;
use Taka512\Http\ClientFactory;

class HomeControllerTest extends E2eTestCase
{
    protected function getDataSet()
    {
        return new YamlDataSet(__DIR__.'/HomeController.yml');
    }

    public function testIndex()
    {
        $client = ClientFactory::createGoutte($this->get('settings')['test']['client']);
        $crawler = $client->request('GET', '/api');
        $this->assertSame(200, $client->getResponse()->getStatus(), 'ステータスコードが200');
        $this->assertSame(['hoge' => 'test'], json_decode($client->getResponse()->getContent(), true), 'コンテンツ');
    }
}
