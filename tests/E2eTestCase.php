<?php

namespace Taka512\Test;

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;
use Symfony\Component\Panther\PantherTestCaseTrait;
use Taka512\ContainerFactory;

abstract class E2eTestCase extends TestCase
{
    use TestCaseTrait;
    use PantherTestCaseTrait;

    abstract protected function getDataSet();

    protected function getConnection()
    {
        return $this->createDefaultDBConnection($this->get('pdo.master'));
    }

    protected function get(string $name)
    {
        return ContainerFactory::getContainer()->get($name);
    }

    protected function login($client): void
    {
        $crawler = $client->request('GET', '/admin/user/signin');
        $form = $crawler->selectButton('ログイン')->form();
        $crawler = $client->submit($form, $this->get('settings')['test']['user']);
    }
}
