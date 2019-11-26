<?php

namespace Taka512\Controller\Api;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Taka512\Controller\BaseController;

/**
 * @OA\Info(
 *   title="php samle api",
 *   version="1.0.0"
 * )
 */

/**
 * @OA\Server(
 *     url="{schema}://localhost",
 *     description="local develop server",
 *     @OA\ServerVariable(
 *         serverVariable="schema",
 *         enum={"https", "http"},
 *         default="https"
 *     )
 * )
 */

/**
 * @OA\Parameter(
 *     name="limit",
 *     in="query",
 *     description="maximum number of results to return",
 *     required=false,
 *     @OA\Schema(
 *         type="integer",
 *         format="int32",
 *         maximum=1000,
 *         minimum=1,
 *         default=20
 *     )
 * )
 * @OA\Parameter(
 *     name="offset",
 *     in="query",
 *     description="offset of results to return",
 *     required=false,
 *     @OA\Schema(
 *         type="integer",
 *         format="int32",
 *         minimum=0
 *     )
 * )
 * @OA\Response(
 *     response="BadRequest",
 *     description="400 Bad Request",
 *     @OA\JsonContent(
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Error")
 *     )
 * )
 * @OA\Response(
 *     response="Unauthorized",
 *     description="401 Unauthorized",
 * )
 * @OA\Response(
 *     response="Forbidden",
 *     description="403 Forbidden",
 * )
 * @OA\Response(
 *     response="NotFound",
 *     description="404 Not Found",
 * )
 * @OA\Response(
 *     response="MethodNotAllowed",
 *     description="405 Method Not Allowed",
 * )
 * @OA\Response(
 *     response="Unexpected",
 *     description="500 Unexpected error",
 *     @OA\JsonContent(
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Error")
 *     )
 * )
 */
class HomeController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/home",
     *     description="sample test api",
     *     operationId="/api/home/index",
     *     @OA\Response(response="200", description="example resource")
     * )
     */
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $response->getBody()->write(json_encode(['hoge' => 'test']));

        return $response
            ->withHeader('Content-type', 'application/json')
            ->withAddedHeader('Access-Control-Allow-Origin', '*');
    }
}
