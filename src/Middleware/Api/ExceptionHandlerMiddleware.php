<?php

namespace Taka512\Middleware\Api;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Taka512\Form\Api\ErrorRenderer;

class ExceptionHandlerMiddleware
{
    protected $errorRenderer;

    public function __construct(ErrorRenderer $errorRenderer)
    {
        $this->errorRenderer = $errorRenderer;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        try {
            return $next($request, $response);
        } catch (\Exception $e) {
            return $this->errorRenderer->render500($response, $e->getMessage());
        }
    }
}
