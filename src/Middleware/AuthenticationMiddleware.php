<?php

namespace Taka512\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;
use Taka512\Repository\UserRepository;
use Zend\Authentication\AuthenticationServiceInterface;

class AuthenticationMiddleware implements MiddlewareInterface
{
    protected $auth;
    protected $view;
    protected $userRepository;

    public function __construct(AuthenticationServiceInterface $auth, Twig $view, UserRepository $userRepository)
    {
        $this->auth = $auth;
        $this->view = $view;
        $this->userRepository = $userRepository;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!$this->auth->hasIdentity()) {
            $response = new Response();
            $url = RouteContext::fromRequest($request)->getRouteParser()->urlFor('admin_user_signin');

            return $response->withHeader('Location', $url)->withStatus(302);
        }
        $user = $this->userRepository->findOneByLoginId($this->auth->getIdentity());
        if (is_null($user) || $user->isDelete()) {
            $this->auth->clearIdentity();
            $response = new Response();
            $url = RouteContext::fromRequest($request)->getRouteParser()->urlFor('admin_user_signin');

            return $response->withHeader('Location', $url)->withStatus(302);
        }
        $this->view->offsetSet('user', $user);

        return $handler->handle($request);
    }
}
