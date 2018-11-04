<?php

namespace Taka512\Middleware;

use Slim\Views\Twig;
use Slim\Router;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Authentication\AuthenticationServiceInterface;
use Taka512\Repository\UserRepository;

class AuthenticationMiddleware
{
    protected $router;
    protected $auth;
    protected $view;
    protected $userRepository;

    public function __construct(Router $router, AuthenticationServiceInterface $auth, Twig $view, UserRepository $userRepository)
    {
        $this->router = $router;
        $this->auth = $auth;
        $this->view = $view;
        $this->userRepository = $userRepository;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        if (!$this->auth->hasIdentity()) {
            return $response->withRedirect($this->router->pathFor('admin_user_signin'));
        }
        $user = $this->userRepository->findOneByLoginId($this->auth->getIdentity());
        if (is_null($user) || $user->isDelete()) {
            $this->auth->clearIdentity();
            return $response->withRedirect($this->router->pathFor('admin_user_signin'));
        }
        $this->view->offsetSet('user', $user);
        $response = $next($request, $response);

        return $response;
    }
}
