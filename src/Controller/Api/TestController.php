<?php

namespace Taka512\Controller\Api;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Taka512\Controller\BaseController;

class TestController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/test",
     *     @OA\Response(response="200", description="An example resource")
     * )
     */
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        return $response
            ->withHeader('Content-type', 'application/json')
            ->withAddedHeader('Access-Control-Allow-Origin', '*')
            ->withJson(['hoge' => 'test']);
    }
}
