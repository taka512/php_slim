<?php

namespace Taka512\Test\E2e\Controller\Admin;

use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Taka512\Http\ClientFactory;
use Taka512\Test\E2eTestCase;

class UserControllerTest extends E2eTestCase
{
    protected function getDataSet()
    {
        return new YamlDataSet(__DIR__.'/UserController.yml');
    }

    public function testIndex()
    {
        $client = ClientFactory::createGoutte($this->get('settings')['test']['client']);
        $this->login($client);
        $crawler = $client->request('GET', '/admin/user');
        $this->assertRegExp('/admin/', $crawler->html());
    }

    public function testCreate()
    {
        $client = ClientFactory::createGoutte($this->get('settings')['test']['client']);
        $this->login($client);
        $crawler = $client->request('GET', '/admin/user/create');
        $form = $crawler->selectButton('登録')->form();
        $crawler = $client->submit($form, ['login_id' => 'hoge', 'password' => 'testtest']);
        $this->assertRegExp('/hoge/', $crawler->html());
    }

    public function testEdit()
    {
        $client = ClientFactory::createGoutte($this->get('settings')['test']['client']);
        $this->login($client);
        $crawler = $client->request('GET', '/admin/user/1/edit');
        $form = $crawler->selectButton('確認')->form();
        $crawler = $client->submit($form, ['login_id' => 'hoge']);
        $form = $crawler->selectButton('保存')->form();
        $crawler = $client->submit($form);
        $form = $crawler->selectButton('ログイン')->form();
        $crawler = $client->submit($form, ['login_id' => 'hoge', 'password' => '12345678']);
        $crawler = $client->request('GET', '/admin/user');
        $this->assertRegExp('/hoge/', $crawler->html());
    }
}
