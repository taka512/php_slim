<?php

namespace Taka512\Test\E2e\Controller\Api;

use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Taka512\Test\E2eTestCase;
use Taka512\Http\ClientFactory;

class TagSiteControllerTest extends E2eTestCase
{
    protected function getDataSet()
    {
        return new YamlDataSet(__DIR__.'/TagController.yml');
    }

    /**
     * @dataProvider providerCreate
     */
    public function testCreate($msg, $json, $expected)
    {
        $client = ClientFactory::createGoutte($this->get('settings')['test']['client']);
        $crawler = $client->request('POST', '/api/tag_site' , [], [], ['HTTP_CONTENT_TYPE' => 'application/json'], json_encode($json));
        var_dump($client->getResponse()->getContent());
        $this->assertSame($expected, $client->getResponse()->getStatus());
    }

    public function providerCreate()
    {
        return [
            ['create success tag_site data', ['tag_id' => '1', 'site_id' => '1'], 200],
        ];
    }
}
