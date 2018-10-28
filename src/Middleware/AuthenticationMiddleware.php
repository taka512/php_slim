<?php

namespace Taka512\Middleware;

use Slim\Router;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Authentication\AuthenticationServiceInterface;


class AuthenticationMiddleware
{
    protected $router;
    protected $auth;

    public function __construct(Router $router, AuthenticationServiceInterface $auth)
    {
        $this->router = $router;
        $this->auth = $auth;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        if (!$this->auth->hasIdentity()) {
            return $response->withRedirect($this->router->pathFor('admin_user_signin'));
        }
        $response = $next($request, $response);

        return $response;
    }
}
