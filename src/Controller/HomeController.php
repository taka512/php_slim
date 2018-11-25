<?php

namespace Taka512\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Taka512\Model\Site;

class HomeController extends BaseController
{
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $sites = Site::all();

        return $this->get('view')->render($response, 'home/index.html.twig', ['sites' => $sites]);
    }
}
