<?php

namespace Taka512\Test;

use PHPUnit\Framework\TestCase;
use Taka512\ContainerFactory;

abstract class DatabaseTestCase extends TestCase
{
    protected function get(string $name)
    {
        return ContainerFactory::getContainer()->get($name);
    }
}
