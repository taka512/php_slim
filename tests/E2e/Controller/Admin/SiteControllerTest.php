<?php

namespace Taka512\Test\E2e\Controller\Admin;

use Nelmio\Alice\Loader\NativeLoader;
use Taka512\Http\ClientFactory;
use Taka512\Manager\EntityManager;
use Taka512\Test\E2eTestCase;

class SiteControllerTest extends E2eTestCase
{
    protected function setUp(): void
    {
        $objectSet = $this->get(NativeLoader::class)->loadFile(__DIR__.'/SiteController.yml');
        $this->get(EntityManager::class)->truncateTables(['user', 'site']);
        $this->get(EntityManager::class)->bulkInsertObjects($objectSet->getObjects());
    }

    public function testIndex()
    {
        $client = ClientFactory::createGoutte();
        $this->login($client);
        $crawler = $client->request('GET', $this->getUrl('/admin/site'));
        $this->assertMatchesRegularExpression('/test yahoo1 site name/', $crawler->html());
    }

    public function testCreate()
    {
        $client = ClientFactory::createGoutte();
        $this->login($client);
        $crawler = $client->request('GET', $this->getUrl('/admin/site/create'));
        $form = $crawler->selectButton('確認')->form();
        $crawler = $client->submit($form, ['name' => 'test google', 'url' => 'https://google.com']);
        $form = $crawler->selectButton('保存')->form();
        $crawler = $client->submit($form);
        $this->assertMatchesRegularExpression('/test google/', $crawler->html());
    }

    public function testEdit()
    {
        $client = ClientFactory::createGoutte();
        $this->login($client);
        $crawler = $client->request('GET', $this->getUrl('/admin/site/1/edit'));
        $form = $crawler->selectButton('確認')->form();
        $crawler = $client->submit($form, ['name' => 'test google', 'url' => 'https://google.com']);
        $form = $crawler->selectButton('保存')->form();
        $crawler = $client->submit($form);
        $this->assertMatchesRegularExpression('/test google/', $crawler->html());
    }
}
