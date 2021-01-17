<?php

namespace Taka512\Test\E2e\Controller\Admin;

use Nelmio\Alice\Loader\NativeLoader;
use Taka512\Http\ClientFactory;
use Taka512\Manager\EntityManager;
use Taka512\Test\E2eTestCase;

class TagControllerTest extends E2eTestCase
{
    protected function setUp(): void
    {
        $loader = new NativeLoader();
        $objectSet = $loader->loadFile(__DIR__.'/TagController.yml');
        $this->get(EntityManager::class)->truncateTables(['user', 'tag']);
        $this->get(EntityManager::class)->bulkInsertObjects($objectSet->getObjects());
    }

    public function testIndex()
    {
        $client = ClientFactory::createGoutte();
        $this->login($client);
        $crawler = $client->request('GET', $this->getUrl('/admin/tag'));
        $this->assertMatchesRegularExpression('/test tag/', $crawler->html());
    }

    public function testCreate()
    {
        $client = ClientFactory::createGoutte();
        $this->login($client);
        $crawler = $client->request('GET', $this->getUrl('/admin/tag/create'));
        $form = $crawler->selectButton('確認')->form();
        $crawler = $client->submit($form, ['name' => 'hoge']);
        $form = $crawler->selectButton('保存')->form();
        $crawler = $client->submit($form);
        $this->assertMatchesRegularExpression('/hoge/', $crawler->html());
    }

    public function testEdit()
    {
        $client = ClientFactory::createGoutte();
        $this->login($client);
        $crawler = $client->request('GET', $this->getUrl('/admin/tag/1/edit'));
        $form = $crawler->selectButton('確認')->form();
        $crawler = $client->submit($form, ['name' => 'hoge']);
        $form = $crawler->selectButton('保存')->form();
        $crawler = $client->submit($form);
        $this->assertMatchesRegularExpression('/hoge/', $crawler->html());
    }

    public function testDelete()
    {
        $client = ClientFactory::createGoutte();
        $this->login($client);
        $crawler = $client->request('GET', $this->getUrl('/admin/tag/1/delete'));
        $form = $crawler->selectButton('削除')->form();
        $crawler = $client->submit($form);
        $crawler = $client->request('GET', $this->getUrl('/admin/tag/1/delete'));
        $this->assertSame($client->getResponse()->getStatusCode(), 404);
    }
}
