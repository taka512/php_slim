<?php

use Phpmig\Adapter;
use Taka512\ContainerFactory;

$container = ContainerFactory::getPimpleContainer();
$container['phpmig.adapter'] = function ($c) {
    return new Adapter\PDO\Sql($c['pdo.master'], 'migration');
};
$container['phpmig.migrations_path'] = __DIR__ . '/../' . 'migrations';
return $container;
