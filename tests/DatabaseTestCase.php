<?php

namespace Taka512\Test;

use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\Framework\TestCase;
use Taka512\ContainerFactory;

abstract class DatabaseTestCase extends TestCase
{
    use TestCaseTrait;

    abstract protected function getDataSet();

    protected function getConnection()
    {
        return $this->createDefaultDBConnection($this->get('pdo.master'));
    }

    protected function get(string $name)
    {
        return ContainerFactory::getContainer()->get($name);
    }
}
