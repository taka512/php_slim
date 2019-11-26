<?php

namespace Taka512;

use Psr\Container\ContainerInterface;
use Pimple\Container as PimpleContainer;

class Container extends PimpleContainer implements ContainerInterface
{
    public function get($id)
    {
        return $this->offsetGet($id);
    }

    public function has($id)
    {
        return $this->offsetExists($id);
    }
}
