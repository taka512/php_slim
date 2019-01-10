<?php

namespace Taka512\Controller\Api;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Taka512\Controller\BaseController;

/**
 * @OA\Info(
 *   title="php samle api",
 *    version="1.0.0"
 * )
 */

/**
 * @OA\Server(
 *      url="{schema}://localhost",
 *      description="OpenApi parameters",
 *      @OA\ServerVariable(
 *          serverVariable="schema",
 *          enum={"https", "http"},
 *          default="https"
 *      )
 * )
 */
class HomeController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/home",
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
