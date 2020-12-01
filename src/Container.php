<?php

namespace Taka512;

use Pimple\Container as PimpleContainer;
use Interop\Container\ContainerInterface;

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
