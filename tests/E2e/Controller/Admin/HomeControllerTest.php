<?php

namespace Taka512\Test\E2e\Controller\Admin;

use Nelmio\Alice\Loader\NativeLoader;
use Taka512\Http\ClientFactory;
use Taka512\Manager\EntityManager;
use Taka512\Test\E2eTestCase;

class HomeControllerTest extends E2eTestCase
{
    protected function setUp(): void
    {
        $objectSet = $this->get(NativeLoader::class)->loadFile(__DIR__.'/HomeController.yml');
        $this->get(EntityManager::class)->truncateTables(['user']);
        $this->get(EntityManager::class)->bulkInsertObjects($objectSet->getObjects());
    }

    public function testIndex()
    {
        $client = ClientFactory::createGoutte();
        $this->login($client);
        $crawler = $client->request('GET', $this->getUrl('/admin'));
        $this->assertMatchesRegularExpression('/管理画面TOP/', $crawler->html());
    }
}
