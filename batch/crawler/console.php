<?php

require_once __DIR__.'/../../vendor/autoload.php';

use Symfony\Component\Console\Application;
use Taka512\Command\Crawler\SiteCrawlerCommand;

$app = new \Symfony\Component\Console\Application();
$app->add(new \Taka512\Command\Crawler\SiteCrawlerCommand());

$app->run();
