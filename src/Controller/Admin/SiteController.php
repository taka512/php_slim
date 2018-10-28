<?php

namespace Taka512\Controller\Admin;

use Taka512\Controller\BaseController;
use Taka512\Model\Site;

class SiteController extends BaseController
{
    public function index($request, $response, $args)
    {
        $sites = $this->get('repository.site')->findLatestSites();

        return $this->container->get('view')->render($response, 'admin/site/index.html.twig', [
            'sites' => $sites
        ]);
    }

    public function create($request, $response, $args)
    {
        $form = $this->get('form.site_create_form');
        $input = $this->get('form.site_create_input');

        $form->bind($input);
        if ($request->isPost()) {
            $form->setData($request->getParsedBody());
            if ($form->isValid() && !$form->getData()->isBack()) {
                if ($form->getData()->isConfirm()) {
                    return $this->container->get('view')->render($response, 'admin/site/create_confirm.html.twig', [
                         'form' => $form
                    ]);
                }
                $this->get('repository.site')->insert($form->getData()->getArrayCopy());
                return $response->withRedirect($this->container->get('router')->pathFor('admin_site_index'));
            }

        }

        return $this->get('view')->render($response, 'admin/site/create.html.twig', [
            'form' => $form,
        ]);
    }

    public function edit($request, $response, $args)
    {
        $site = $this->get('repository.site')->findOneById($args['id']);
        if (is_null($site)) {
            return $this->get('notFoundHandler')($request, $response);
        }

        $form = $this->get('form.site_edit_form');
        $input = $this->get('form.site_edit_input');

        $input->exchangeArray($site->getFormArray());
        $form->bind($input);
        if ($request->isPost()) {
            $form->setData($request->getParsedBody());
            if ($form->isValid() && !$form->getData()->isBack()) {
                if ($form->getData()->isConfirm()) {
                    return $this->container->get('view')->render($response, 'admin/site/edit_confirm.html.twig', [
                         'form' => $form
                    ]);
                }
                $site->setFormArray($form->getData()->getArrayCopy());
                $site->save();
                return $response->withRedirect($this->get('router')->pathFor('admin_site_edit', ['id' => $args['id']]));

            }
        }

        return $this->get('view')->render($response, 'admin/site/edit.html.twig', [
            'form' => $form,
        ]);
    }
}
