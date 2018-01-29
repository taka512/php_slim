<?php

namespace Taka512\Controller\Api;

use Psr\Container\ContainerInterface;

class TestController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function index($request, $response, $args)
    {
        return $response
            ->withHeader('Content-type', 'application/json')
            ->withJson(['hoge' => 'test']);
    }
}
