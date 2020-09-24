<?php

namespace Taka512\Controller;

use Psr\Container\ContainerInterface;
use Taka512\ContainerFactory;

abstract class BaseController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = ContainerFactory::getContainer();
    }

    public function get(string $name)
    {
        return $this->container->get($name);
    }
}
