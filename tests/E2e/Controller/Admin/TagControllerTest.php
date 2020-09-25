<?php

namespace Taka512\Test\E2e\Controller\Admin;

use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Taka512\Http\ClientFactory;
use Taka512\Test\E2eTestCase;

class TagControllerTest extends E2eTestCase
{
    protected function getDataSet()
    {
        return new YamlDataSet(__DIR__.'/TagController.yml');
    }

    public function testIndex()
    {
        $client = ClientFactory::createGoutte($this->get('settings')['test']['client']);
        $this->login($client);
        $crawler = $client->request('GET', '/admin/tag');
        $this->assertRegExp('/test tag/', $crawler->html());
    }

    public function testCreate()
    {
        $client = ClientFactory::createGoutte($this->get('settings')['test']['client']);
        $this->login($client);
        $crawler = $client->request('GET', '/admin/tag/create');
        $form = $crawler->selectButton('確認')->form();
        $crawler = $client->submit($form, ['name' => 'hoge']);
        $form = $crawler->selectButton('保存')->form();
        $crawler = $client->submit($form);
        $this->assertRegExp('/hoge/', $crawler->html());
    }

    public function testEdit()
    {
        $client = ClientFactory::createGoutte($this->get('settings')['test']['client']);
        $this->login($client);
        $crawler = $client->request('GET', '/admin/tag/1/edit');
        $form = $crawler->selectButton('確認')->form();
        $crawler = $client->submit($form, ['name' => 'hoge']);
        $form = $crawler->selectButton('保存')->form();
        $crawler = $client->submit($form);
        $this->assertRegExp('/hoge/', $crawler->html());
    }

    public function testDelete()
    {
        $client = ClientFactory::createGoutte($this->get('settings')['test']['client']);
        $this->login($client);
        $crawler = $client->request('GET', '/admin/tag/1/delete');
        $form = $crawler->selectButton('削除')->form();
        $crawler = $client->submit($form);
        $crawler = $client->request('GET', '/admin/tag/1/delete');
        $this->assertSame($client->getResponse()->getStatus(), 404);
    }
}
