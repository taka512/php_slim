<?php

use Phpmig\Adapter;
use Taka512\ContainerFactory;

$container = ContainerFactory::getPimpleContainer();
$container['phpmig.adapter'] = function ($c) {
    return new Adapter\PDO\Sql($c['pdo.master'], 'migration_seed');
};
$container['phpmig.migrations_path'] = __DIR__ . '/../' . 'seeds';
return $container;
