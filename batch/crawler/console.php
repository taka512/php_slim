<?php

require_once __DIR__.'/../../vendor/autoload.php';

use Taka512\ContainerFactory;
$c = ContainerFactory::getContainer() ;
$c['batch.crawler']->run();

