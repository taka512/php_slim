<?php

namespace Taka512\Controller;

use Psr\Container\ContainerInterface;

class HomeController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function index($request, $response, $args)
    {
        return $this->container['view']->render($response, 'home/index.html.twig', []);
    }

    public function hello($request, $response, $args)
    {
        return $this->container['view']->render($response, 'home/hello.html.twig', [
            'name' => $args['name']
        ]);
    }
}
