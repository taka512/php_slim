<?php

namespace Taka512\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Authentication\AuthenticationServiceInterface;


class AuthenticationMiddleware
{
    protected $auth;

    public function __construct(AuthenticationServiceInterface $auth)
    {
        $this->auth = $auth;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        if (!$this->auth->hasIdentity()) {
//            $authAdapter = new Adapter('taka', 'testpass');

//            $result = $this->auth->authenticate($authAdapter);

//            if (! $result->isValid()) {
                // Authentication failed; print the reasons why
//                foreach ($result->getMessages() as $message) {
//                    echo "$message\n";
//                }
//            } else {
                // Authentication succeeded; the identity ($username) is stored
                // in the session:
                // $result->getIdentity() === $auth->getIdentity()
                // $result->getIdentity() === $username
//            }
        }
        $response = $next($request, $response);

        return $response;
    }
}
