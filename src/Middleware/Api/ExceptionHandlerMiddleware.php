<?php

namespace Taka512\Middleware\Api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use Taka512\Form\Api\ErrorRenderer;

class ExceptionHandlerMiddleware implements MiddlewareInterface
{
    protected $errorRenderer;

    public function __construct(ErrorRenderer $errorRenderer)
    {
        $this->errorRenderer = $errorRenderer;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (\Exception $e) {
            return $this->errorRenderer->render500(new Response(), $e->getMessage());
        }
    }
}
