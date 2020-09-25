<?php

namespace Taka512\Test\E2e\Controller\Admin;

use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Taka512\Http\ClientFactory;
use Taka512\Test\E2eTestCase;

class HomeControllerTest extends E2eTestCase
{
    protected function getDataSet()
    {
        return new YamlDataSet(__DIR__.'/HomeController.yml');
    }

    public function testIndex()
    {
        $client = ClientFactory::createGoutte($this->get('settings')['test']['client']);
        $this->login($client);
        $crawler = $client->request('GET', '/admin');
        $this->assertRegExp('/管理画面TOP/', $crawler->html());
    }
}
