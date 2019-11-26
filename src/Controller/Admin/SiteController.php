<?php

namespace Taka512\Controller\Admin;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpNotFoundException;
use Taka512\Controller\BaseController;

class SiteController extends BaseController
{
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $sites = $this->get('repository.site')->findLatestSites();

        return $this->container->get('view')->render($response, 'admin/site/index.html.twig', [
            'sites' => $sites,
        ]);
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $form = $this->get('form.admin.site.create_form');
        $input = $this->get('form.admin.site.create_input');

        $form->bind($input);
        if ('POST' === strtoupper($request->getMethod())) {
            $form->setData($request->getParsedBody());
            if ($form->isValid() && !$form->getData()->isBack()) {
                if ($form->getData()->isConfirm()) {
                    return $this->container->get('view')->render($response, 'admin/site/create_confirm.html.twig', [
                         'form' => $form,
                    ]);
                }
                $this->get('repository.site')->insert($form->getData()->getArrayCopy());
                $url = $request->getAttribute('routeParser')->urlFor('admin_site_index');

                return $response->withHeader('Location', $url)->withStatus(302);
            }
        }

        return $this->get('view')->render($response, 'admin/site/create.html.twig', [
            'form' => $form,
        ]);
    }

    public function edit(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $site = $this->get('repository.site')->findOneById($args['id']);
        if (is_null($site)) {
            throw new HttpNotFoundException($request);
        }

        $form = $this->get('form.admin.site.edit_form');
        $input = $this->get('form.admin.site.edit_input');

        $input->exchangeArray($site->getFormArray());
        $form->bind($input);
        if ('POST' === strtoupper($request->getMethod())) {
            $form->setData($request->getParsedBody());
            if ($form->isValid() && !$form->getData()->isBack()) {
                if ($form->getData()->isConfirm()) {
                    return $this->container->get('view')->render($response, 'admin/site/edit_confirm.html.twig', [
                         'form' => $form,
                    ]);
                }
                $site->setFormArray($form->getData()->getArrayCopy());
                $site->save();
                $this->get('repository.tag_site')->deleteBySiteId($site->id);
                foreach ($form->getData()->getTagSiteData() as $tag) {
                    $this->get('repository.tag_site')->insert($tag);
                }
                $url = $request->getAttribute('routeParser')->urlFor('admin_site_edit', ['id' => $args['id']]);

                return $response->withHeader('Location', $url)->withStatus(302);
            }
        }

        return $this->get('view')->render($response, 'admin/site/edit.html.twig', [
            'form' => $form,
        ]);
    }
}
