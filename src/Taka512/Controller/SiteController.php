<?php

namespace Taka512\Controller;

use Psr\Container\ContainerInterface;
use Taka512\Model\Site;
use Taka512\Form\SiteEditForm;
use Taka512\Form\SiteEditInput;

class SiteController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function index($request, $response, $args)
    {
        $sites = Site::where('del_flg', Site::DEL_FLG_OFF)->orderBy('id', 'desc')->take(10)->get();

        return $this->container->get('view')->render($response, 'site/index.html.twig', [
            'sites' => $sites
        ]);
    }

    public function create($request, $response, $args)
    {
        $form = $this->container->get('form.site_create_form');
        $input = $this->container->get('form.site_create_input');

        $form->bind($input);
        if ($request->isPost()) {
            $form->setData($request->getParsedBody());
            if ($form->isValid() && !$form->getData()->isBack()) {
                $site = new Site();
                $site->setFormArray($form->getData()->getArrayCopy());
                if ($form->getData()->isConfirm()) {
                    return $this->container->get('view')->render($response, 'site/create_confirm.html.twig', [
                         'form' => $form
                    ]);
                }
                $site->save();
                return $response->withRedirect($this->container->get('router')->pathFor('site_index'), 303);
            }

        }

        return $this->container->get('view')->render($response, 'site/create.html.twig', [
            'form' => $form,
        ]);
    }

    public function edit($request, $response, $args)
    {
        $site = Site::find($args['id']);
        if (!$site) {
            return $this->container->get('notFoundHandler')($request, $response);
        }

        $form = $this->container->get('form.site_edit_form');
        $input = $this->container->get('form.site_edit_input');

        $input->exchangeArray($site->getFormArray());
        $form->bind($input);
        if ($request->isPost()) {
            $form->setData($request->getParsedBody());
            if ($form->isValid() && !$form->getData()->isBack()) {
                $site->setFormArray($form->getData()->getArrayCopy());
                if ($form->getData()->isConfirm()) {
                    return $this->container->get('view')->render($response, 'site/edit_confirm.html.twig', [
                         'form' => $form
                    ]);
                }
                $site->save();
                return $response->withRedirect($this->container->get('router')->pathFor('site_edit', ['id' => $args['id']]), 303);

            }
        }

        return $this->container->get('view')->render($response, 'site/edit.html.twig', [
            'form' => $form,
        ]);
    }
}
