<?php

namespace Taka512\Test;

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;
use Taka512\ContainerFactory;

abstract class DatabaseTestCase extends TestCase
{
    use TestCaseTrait;

    abstract protected function getDataSet();

    protected function getConnection()
    {
        return $this->createDefaultDBConnection($this->get('pdo.master'));
    }

    protected function get($name)
    {
        return ContainerFactory::getTestContainer()[$name];
    }
}
