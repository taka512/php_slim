<?php

namespace Taka512\Controller\Admin;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpNotFoundException;
use Taka512\Controller\BaseController;
use Taka512\Util\StdUtil;

class UserController extends BaseController
{
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $users = $this->get('repository.user')->findLatestUsers();

        return $this->container->get('view')->render($response, 'admin/user/index.html.twig', [
            'users' => $users,
        ]);
    }

    public function signin(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $form = $this->get('form.admin.user.signin_form');
        $input = $this->get('form.admin.user.signin_input');
        $form->bind($input);
        $messages = [];
        if ('POST' === strtoupper($request->getMethod())) {
            $form->setData($request->getParsedBody());
            if ($form->isValid()) {
                try {
                    $authAdapter = $this->get('auth.authentication_adapter');
                    $authAdapter
                        ->setLoginId($form->getData()->getLoginId())
                        ->setPassword($form->getData()->getPassword());
                    $result = $this->get('auth')->authenticate($authAdapter);
                    if ($result->isValid()) {
                        $url = $request->getAttribute('routeParser')->urlFor('admin_home_index');

                        return $response->withHeader('Location', $url)->withStatus(302);
                    }
                    $messages = $result->getMessages();
                } catch (\Exception $e) {
                    throw new \RuntimeException(StdUtil::maskSecret($e->getMessage(), $form->getData()->getPassword()), $e->getCode());
                }
            }
        }

        return $this->get('view')->render($response, 'admin/user/signin.html.twig', [
            'form' => $form, 'errors' => $messages,
        ]);
    }

    public function signout(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->get('auth')->clearIdentity();
        $url = $request->getAttribute('routeParser')->urlFor('top');

        return $response->withHeader('Location', $url)->withStatus(302);
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $form = $this->get('form.admin.user.create_form');
        $input = $this->get('form.admin.user.create_input');
        $form->bind($input);
        if ('POST' === strtoupper($request->getMethod())) {
            $form->setData($request->getParsedBody());
            if ($form->isValid()) {
                $this->get('repository.user')->insert($form->getData()->getArrayCopy());
            }
        }

        return $this->get('view')->render($response, 'admin/user/create.html.twig', [
            'form' => $form,
        ]);
    }

    public function edit(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $user = $this->get('repository.user')->findOneById($args['id']);
        if (is_null($user)) {
            throw new HttpNotFoundException($request);
        }

        $form = $this->get('form.admin.user.edit_form');
        $input = $this->get('form.admin.user.edit_input');

        $input->exchangeArray($user->getFormArray());
        $form->bind($input);
        if ('POST' === strtoupper($request->getMethod())) {
            $form->setData($request->getParsedBody());
            if ($form->isValid() && !$form->getData()->isBack()) {
                if ($form->getData()->isConfirm()) {
                    return $this->container->get('view')->render($response, 'admin/user/edit_confirm.html.twig', [
                         'form' => $form,
                    ]);
                }
                $user->setEditFormArray($form->getData()->getArrayCopy());
                $user->save();
                $url = $request->getAttribute('routeParser')->urlFor('admin_user_edit', ['id' => $args['id']]);

                return $response->withHeader('Location', $url)->withStatus(302);
            }
        }

        return $this->get('view')->render($response, 'admin/user/edit.html.twig', [
            'form' => $form,
        ]);
    }
}
