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

    public function get($name)
    {
        return $this->container->get($name);
    }
}
