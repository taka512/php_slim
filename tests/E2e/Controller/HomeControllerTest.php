<?php

namespace Taka512\Test\E2e\Controller;

use Nelmio\Alice\Loader\NativeLoader;
use Taka512\Http\ClientFactory;
use Taka512\Manager\EntityManager;
use Taka512\Test\E2eTestCase;

class HomeControllerTest extends E2eTestCase
{
    protected function setUp(): void
    {
        $loader = new NativeLoader();
        $objectSet = $loader->loadFile(__DIR__.'/HomeController.yml');
        $this->get(EntityManager::class)->truncateTables(['site']);
        $this->get(EntityManager::class)->bulkInsertObjects($objectSet->getObjects());
    }

    /**
     * @dataProvider providerIndex
     */
    public function testIndex($msg, $expected)
    {
        $client = ClientFactory::createGoutte();
        $crawler = $client->request('GET', $this->getUrl('/'));
        $this->assertMatchesRegularExpression($expected, $crawler->html());
    }

    public function providerIndex()
    {
        return [
            ['test site name in site top', '/test yahoo site name/'],
        ];
    }
}
