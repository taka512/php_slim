<?php

namespace Taka512\Controller\Admin;

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
        return $this->container->get('view')->render($response, 'admin/home/index.html.twig',[]);
    }
}
