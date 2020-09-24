<?php

namespace Taka512\Controller;

use Taka512\ContainerFactory;

abstract class BaseController
{
    public function get(string $name)
    {
        return ContainerFactory::getContainer()->get($name);
    }
}
