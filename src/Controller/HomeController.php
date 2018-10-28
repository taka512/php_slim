<?php

namespace Taka512\Controller;

use Taka512\Model\Site;

class HomeController extends BaseController
{
    public function index($request, $response, $args)
    {
        $sites = Site::all();
        return $this->get('view')->render($response, 'home/index.html.twig', ['sites' => $sites]);
    }
}
