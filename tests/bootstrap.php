<?php

//$loader = require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../vendor/autoload.php';

use Taka512\ContainerFactory;

ContainerFactory::initTestContainer(ContainerFactory::getTestPimpleContainer());
// use Doctrine/Common/Annotations/AnnotationRegistory;
// AnnotationRegistory::registerLoader([$loader, 'loadClass']);
