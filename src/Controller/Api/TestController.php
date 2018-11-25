<?php

namespace Taka512\Controller\Api;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Taka512\Controller\BaseController;

class TestController extends BaseController
{
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        return $response
            ->withHeader('Content-type', 'application/json')
            ->withJson(['hoge' => 'test']);
    }
}
