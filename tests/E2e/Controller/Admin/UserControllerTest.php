<?php

namespace Taka512\Test\E2e\Controller\Admin;

use Nelmio\Alice\Loader\NativeLoader;
use Taka512\Http\ClientFactory;
use Taka512\Manager\EntityManager;
use Taka512\Test\E2eTestCase;

class UserControllerTest extends E2eTestCase
{
    protected function setUp(): void
    {
        $objectSet = $this->get(NativeLoader::class)->loadFile(__DIR__.'/UserController.yml');
        $this->get(EntityManager::class)->truncateTables(['user']);
        $this->get(EntityManager::class)->bulkInsertObjects($objectSet->getObjects());
    }

    public function testIndex()
    {
        $client = ClientFactory::createGoutte();
        $this->login($client);
        $crawler = $client->request('GET', $this->getUrl('/admin/user'));
        $this->assertMatchesRegularExpression('/admin/', $crawler->html());
    }

    public function testCreate()
    {
        $client = ClientFactory::createGoutte();
        $this->login($client);
        $crawler = $client->request('GET', $this->getUrl('/admin/user/create'));
        $form = $crawler->selectButton('登録')->form();
        $crawler = $client->submit($form, ['login_id' => 'hoge', 'password' => 'testtest']);
        $this->assertMatchesRegularExpression('/hoge/', $crawler->html());
    }

    public function testEdit()
    {
        $client = ClientFactory::createGoutte();
        $this->login($client);
        $crawler = $client->request('GET', $this->getUrl('/admin/user/1/edit'));
        $form = $crawler->selectButton('確認')->form();
        $crawler = $client->submit($form, ['login_id' => 'hoge']);
        $form = $crawler->selectButton('保存')->form();
        $crawler = $client->submit($form);
        $form = $crawler->selectButton('ログイン')->form();
        $crawler = $client->submit($form, ['login_id' => 'hoge', 'password' => '12345678']);
        $crawler = $client->request('GET', $this->getUrl('/admin/user'));
        $this->assertMatchesRegularExpression('/hoge/', $crawler->html());
    }
}
