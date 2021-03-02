<?php

declare(strict_types=1);

namespace Taka512;

use Phpmig\Migration\Migration as BaseMigration;

class Migration extends BaseMigration
{
    protected function getInstance(string $name)
    {
        return ContainerFactory::getContainer()->get($name);
    }
}
