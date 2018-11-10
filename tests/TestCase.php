<?php

namespace Taka512\Test;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Taka512\ContainerFactory;

abstract class TestCase extends BaseTestCase
{
    protected function get($name)
    {
        return ContainerFactory::getTestContainer()[$name];
    }
}
