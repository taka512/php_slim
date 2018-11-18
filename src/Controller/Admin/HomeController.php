<?php

namespace Taka512\Controller\Admin;

use Taka512\Controller\BaseController;

class HomeController extends BaseController
{
    public function index($request, $response, $args)
    {
        return $this->get('view')->render($response, 'admin/home/index.html.twig', []);
    }
}
