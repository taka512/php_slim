<?php

namespace Taka512\Controller\Admin;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpNotFoundException;
use Taka512\Controller\BaseController;
use Taka512\Form\Admin\Site\CreateForm;
use Taka512\Form\Admin\Site\CreateInput;
use Taka512\Form\Admin\Site\EditForm;
use Taka512\Form\Admin\Site\EditInput;
use Taka512\Repository\SiteRepository;
use Taka512\Repository\TagSiteRepository;

class SiteController extends BaseController
{
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $sites = $this->get(SiteRepository::class)->findLatestSites();

        return $this->get('view')->render($response, 'admin/site/index.html.twig', [
            'sites' => $sites,
        ]);
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $form = $this->get(CreateForm::class);
        $input = $this->get(CreateInput::class);

        $form->bind($input);
        if ('POST' === strtoupper($request->getMethod())) {
            $form->setData($request->getParsedBody());
            if ($form->isValid() && !$form->getData()->isBack()) {
                if ($form->getData()->isConfirm()) {
                    return $this->get('view')->render($response, 'admin/site/create_confirm.html.twig', [
                         'form' => $form,
                    ]);
                }
                $this->get(SiteRepository::class)->insert($form->getData()->getArrayCopy());
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
        $site = $this->get(SiteRepository::class)->findOneById($args['id']);
        if (is_null($site)) {
            throw new HttpNotFoundException($request);
        }

        $form = $this->get(EditForm::class);
        $input = $this->get(EditInput::class);

        $input->exchangeArray($site->getFormArray());
        $form->bind($input);
        if ('POST' === strtoupper($request->getMethod())) {
            $form->setData($request->getParsedBody());
            if ($form->isValid() && !$form->getData()->isBack()) {
                if ($form->getData()->isConfirm()) {
                    return $this->get('view')->render($response, 'admin/site/edit_confirm.html.twig', [
                         'form' => $form,
                    ]);
                }
                $site->setFormArray($form->getData()->getArrayCopy());
                $site->save();
                $this->get(TagSiteRepository::class)->deleteBySiteId($site->id);
                foreach ($form->getData()->getTagSiteData() as $tag) {
                    $this->get(TagSiteRepository::class)->insert($tag);
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
