<?php

namespace Taka512\Test\E2e\Controller\Api;

use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Taka512\Test\E2eTestCase;
use Taka512\Http\ClientFactory;

class TagSiteControllerTest extends E2eTestCase
{
    protected function getDataSet()
    {
        return new YamlDataSet(__DIR__.'/TagSiteController.yml');
    }

    /**
     * @dataProvider providerCreate
     */
    public function testCreate($msg, $json, $expectedStatus, $expectedContent)
    {
        $client = ClientFactory::createGoutte($this->get('settings')['test']['client']);
        $crawler = $client->request('POST', '/api/tag_site', [], [], ['HTTP_CONTENT_TYPE' => 'application/json'], json_encode($json));
        $this->assertSame($expectedStatus, $client->getResponse()->getStatus(), 'ステータスコードの比較');
        $this->assertSame($expectedContent, json_decode($client->getResponse()->getContent(), true), 'コンテンツの比較');
    }

    public function providerCreate()
    {
        return [
            ['create success tag_site data', ['tag_id' => '2', 'site_id' => '2'], 200, ['tag_id' => '2', 'site_id' => '2']],
        ];
    }
}
