<?php

namespace Taka512\Controller\Admin;

use Taka512\Controller\BaseController;
use Taka512\Model\User;

class UserController extends BaseController
{
    public function signin($request, $response, $args)
    {
        $form = $this->get('form.admin.user.signin_form');
        $input = $this->get('form.admin.user.signin_input');
        $form->bind($input);
        if ($request->isPost()) {
            $form->setData($request->getParsedBody());
            if ($form->isValid()) {
                $authAdapter = $this->get('auth.authentication_adapter');
                $authAdapter->setLoginId($form->getData()->getLoginId());
                $authAdapter->setPassword($form->getData()->getPassword());
                $result = $this->get('auth')->authenticate($authAdapter);
                if ($result->isValid()) {
                    return $response->withRedirect($this->get('router')->pathFor('admin_home_index'));
                }
                $messages = $result->getMessages();
            }
        }

        return $this->get('view')->render($response, 'admin/user/signin.html.twig', [
            'form' => $form, 'errors' => $messages,
        ]);
    }

    public function create($request, $response, $args)
    {
        $form = $this->get('form.admin.user.create_form');
        $input = $this->get('form.admin.user.create_input');
        $form->bind($input);
        if ($request->isPost()) {
            $form->setData($request->getParsedBody());
            if ($form->isValid()) {
                $user = new User();
                $user->setCreateFormArray($form->getData()->getArrayCopy());
                $user->save();
            }
        }

        return $this->get('view')->render($response, 'admin/user/create.html.twig', [
            'form' => $form,
        ]);
    }
}
