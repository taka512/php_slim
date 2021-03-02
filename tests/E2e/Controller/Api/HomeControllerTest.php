<?php

namespace Taka512\Test\E2e\Controller\Api;

use Nelmio\Alice\Loader\NativeLoader;
use Taka512\Http\ClientFactory;
use Taka512\Manager\EntityManager;
use Taka512\Test\E2eTestCase;

class HomeControllerTest extends E2eTestCase
{
    protected function setUp(): void
    {
        $objectSet = $this->get(NativeLoader::class)->loadFile(__DIR__.'/HomeController.yml');
        $this->get(EntityManager::class)->truncateTables(['site']);
        $this->get(EntityManager::class)->bulkInsertObjects($objectSet->getObjects());
    }

    public function testIndex()
    {
        $client = ClientFactory::createGoutte();
        $crawler = $client->request('GET', $this->getUrl('/api'));
        $this->assertSame(200, $client->getResponse()->getStatusCode(), 'ステータスコードが200');
        $this->assertSame(['hoge' => 'test'], json_decode($client->getResponse()->getContent(), true), 'コンテンツ');
    }
}
