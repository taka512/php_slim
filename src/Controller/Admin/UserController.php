<?php

namespace Taka512\Controller\Admin;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteContext;
use Taka512\Auth\AuthenticationAdapter;
use Taka512\Controller\BaseController;
use Taka512\Form\Admin\User\CreateForm;
use Taka512\Form\Admin\User\CreateInput;
use Taka512\Form\Admin\User\EditForm;
use Taka512\Form\Admin\User\EditInput;
use Taka512\Form\Admin\User\SigninForm;
use Taka512\Form\Admin\User\SigninInput;
use Taka512\Repository\UserRepository;
use Taka512\Util\StdUtil;

class UserController extends BaseController
{
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $users = $this->get(UserRepository::class)->findLatestUsers();

        return $this->get('view')->render($response, 'admin/user/index.html.twig', [
            'users' => $users,
        ]);
    }

    public function signin(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $form = $this->get(SigninForm::class);
        $input = $this->get(SigninInput::class);
        $form->bind($input);
        $messages = [];
        if ('POST' === strtoupper($request->getMethod())) {
            $form->setData($request->getParsedBody());
            if ($form->isValid()) {
                try {
                    $authAdapter = $this->get(AuthenticationAdapter::class);
                    $authAdapter
                        ->setLoginId($form->getData()->getLoginId())
                        ->setPassword($form->getData()->getPassword());
                    $result = $this->get('auth')->authenticate($authAdapter);
                    if ($result->isValid()) {
                        $url = RouteContext::fromRequest($request)->getRouteParser()->urlFor('admin_home_index');

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
        $url = RouteContext::fromRequest($request)->getRouteParser()->urlFor('top');

        return $response->withHeader('Location', $url)->withStatus(302);
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $form = $this->get(CreateForm::class);
        $input = $this->get(CreateInput::class);
        $form->bind($input);
        if ('POST' === strtoupper($request->getMethod())) {
            $form->setData($request->getParsedBody());
            if ($form->isValid()) {
                $this->get(UserRepository::class)->insert($form->getData()->getArrayCopy());
            }
        }

        return $this->get('view')->render($response, 'admin/user/create.html.twig', [
            'form' => $form,
        ]);
    }

    public function edit(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $user = $this->get(UserRepository::class)->findOneById($args['id']);
        if (is_null($user)) {
            throw new HttpNotFoundException($request);
        }

        $form = $this->get(EditForm::class);
        $input = $this->get(EditInput::class);

        $input->exchangeArray($user->getFormArray());
        $form->bind($input);
        if ('POST' === strtoupper($request->getMethod())) {
            $form->setData($request->getParsedBody());
            if ($form->isValid() && !$form->getData()->isBack()) {
                if ($form->getData()->isConfirm()) {
                    return $this->get('view')->render($response, 'admin/user/edit_confirm.html.twig', [
                         'form' => $form,
                    ]);
                }
                $user->setEditFormArray($form->getData()->getArrayCopy());
                $user->save();
                $url = RouteContext::fromRequest($request)->getRouteParser()->urlFor('admin_user_edit', ['id' => $args['id']]);

                return $response->withHeader('Location', $url)->withStatus(302);
            }
        }

        return $this->get('view')->render($response, 'admin/user/edit.html.twig', [
            'form' => $form,
        ]);
    }
}
