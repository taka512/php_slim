<?php

namespace Taka512\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Taka512\Repository\SiteRepository;

class HomeController extends BaseController
{
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $sites = $this->get(SiteRepository::class)->findLatestSites();

        return $this->get('view')->render($response, 'home/index.html.twig', ['sites' => $sites]);
    }
}
