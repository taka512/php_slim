<?php

namespace Taka512\Controller\Admin;

use Psr\Container\ContainerInterface;
use Taka512\Model\User;
use Taka512\Auth\AuthenticationAdapter;

class UserController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function signin($request, $response, $args)
    {
        $form = $this->container->get('form.admin.user.signin_form');
        $input = $this->container->get('form.admin.user.signin_input');
        $form->bind($input);
        if ($request->isPost()) {
            $form->setData($request->getParsedBody());
            if ($form->isValid()) {
                $authAdapter = new AuthenticationAdapter($form->getData()->getLoginId(), $form->getData()->getPassword());
                $result = $this->container->get('auth')->authenticate($authAdapter);


            }

        }

        return $this->container->get('view')->render($response, 'admin/user/signin.html.twig', [
            'form' => $form,
        ]);
    }
}
