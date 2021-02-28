<?php

declare(strict_types=1);

use Phpmig\Adapter;
use Pimple\Container;
use Taka512\ContainerFactory;

ContainerFactory::initContainerOnBatch('seed');
$pimpleContainer = new Container();
$pimpleContainer['db'] = ContainerFactory::getContainer()->get(\PDO::class);
$pimpleContainer['phpmig.adapter'] = function ($c) {
    return new Adapter\PDO\Sql($c['db'], 'migration_seed');
};
$pimpleContainer['phpmig.migrations_path'] = __DIR__ . '/../' . 'seeds';
return $pimpleContainer;
