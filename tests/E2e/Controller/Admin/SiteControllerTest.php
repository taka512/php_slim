<?php

namespace Taka512\Test\E2e\Controller\Admin;

use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Taka512\Http\ClientFactory;
use Taka512\Test\E2eTestCase;

class SiteControllerTest extends E2eTestCase
{
    protected function getDataSet()
    {
        return new YamlDataSet(__DIR__.'/SiteController.yml');
    }

    public function testIndex()
    {
        $client = ClientFactory::createGoutte();
        $this->login($client);
        $crawler = $client->request('GET', $this->getUrl('/admin/site'));
        $this->assertRegExp('/test yahoo1 site name/', $crawler->html());
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
        $this->assertRegExp('/test google/', $crawler->html());
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
        $this->assertRegExp('/test google/', $crawler->html());
    }
}
