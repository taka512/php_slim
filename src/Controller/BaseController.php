<?php

namespace Taka512\Controller;

use Psr\Container\ContainerInterface;

abstract class BaseController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function get(string $name)
    {
        return $this->container->get($name);
    }
}
