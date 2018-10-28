<?php

namespace Taka512\Controller;

use Psr\Container\ContainerInterface;
use Taka512\Model\Site;

class HomeController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function index($request, $response, $args)
    {
        $sites = Site::all();
        return $this->container->get('view')->render($response, 'home/index.html.twig', ['sites' => $sites]);
    }
}
