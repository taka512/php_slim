<?php

use Phpmig\Adapter;
use Pimple\Container;

$container = new Container();

$container['db'] = function () {
    $dbh = new PDO('mysql:dbname=sample_slim;host=db.local','slim_user','slim_pass');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
};

$container['phpmig.adapter'] = function ($c) {
    return new Adapter\PDO\Sql($c['db'], 'migrations');
};

$container['phpmig.migrations_path'] = __DIR__ . '/../' . 'migrations';

return $container;
