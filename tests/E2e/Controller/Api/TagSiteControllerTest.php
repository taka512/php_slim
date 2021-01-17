<?php

namespace Taka512\Test\E2e\Controller\Api;

use Nelmio\Alice\Loader\NativeLoader;
use Taka512\Http\ClientFactory;
use Taka512\Manager\EntityManager;
use Taka512\Test\E2eTestCase;

class TagSiteControllerTest extends E2eTestCase
{
    protected function setUp(): void
    {
        $loader = new NativeLoader();
        $objectSet = $loader->loadFile(__DIR__.'/TagSiteController.yml');
        $this->get(EntityManager::class)->truncateTables(['tag_site', 'tag', 'site']);
        $this->get(EntityManager::class)->bulkInsertObjects($objectSet->getObjects());
    }

    /**
     * @dataProvider providerCreate
     */
    public function testCreate($msg, $json, $expectedStatus, $expectedContent)
    {
        $client = ClientFactory::createGoutte();
        $crawler = $client->request('POST', $this->getUrl('/api/tag_site'), [], [], ['HTTP_CONTENT_TYPE' => 'application/json'], json_encode($json));
        $this->assertSame($expectedStatus, $client->getResponse()->getStatusCode(), 'ステータスコードの比較');
        $this->assertSame($expectedContent, json_decode($client->getResponse()->getContent(), true), 'コンテンツの比較');
    }

    public function providerCreate()
    {
        return [
            ['create success tag_site data', ['tag_id' => '2', 'site_id' => '2'], 200, ['tag_id' => '2', 'site_id' => '2']],
        ];
    }
}
