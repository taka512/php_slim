<?php

namespace Taka512\Test;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Panther\PantherTestCaseTrait;
use Taka512\ContainerFactory;

abstract class E2eTestCase extends TestCase
{
    use PantherTestCaseTrait;

    protected function get(string $name)
    {
        return ContainerFactory::getContainer()->get($name);
    }

    protected function getUrl($path)
    {
        return $this->get('settings')['test']['base_host'].$path;
    }

    protected function login($client): void
    {
        $crawler = $client->request('GET', $this->getUrl('/admin/user/signin'));
        $form = $crawler->selectButton('ログイン')->form();
        $crawler = $client->submit($form, $this->get('settings')['test']['user']);
    }
}
