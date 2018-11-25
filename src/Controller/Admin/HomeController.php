<?php

namespace Taka512\Controller\Admin;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Taka512\Controller\BaseController;

class HomeController extends BaseController
{
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        return $this->get('view')->render($response, 'admin/home/index.html.twig', []);
    }
}
