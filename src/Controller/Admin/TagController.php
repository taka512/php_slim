<?php

namespace Taka512\Controller\Admin;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpNotFoundException;
use Taka512\Controller\BaseController;
use Pagerfanta\View\TwitterBootstrap4View;
use Taka512\Form\Admin\Tag\CreateForm;
use Taka512\Form\Admin\Tag\CreateInput;
use Taka512\Form\Admin\Tag\EditForm;
use Taka512\Form\Admin\Tag\EditInput;
use Taka512\Form\Admin\Tag\DeleteForm;
use Taka512\Form\Admin\Tag\DeleteInput;
use Taka512\Repository\TagRepository;
use Taka512\Manager\TagManager;

class TagController extends BaseController
{
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $getParams = $request->getQueryParams();
        $page = $getParams['page'] ?? 1;
        $pagenate = $this->get(TagManager::class)->getTagPagenate($page);
        $routeGenerator = function ($page) {
            return htmlspecialchars(sprintf('/admin/tag?page=%s', $page), ENT_QUOTES);
        };
        $view = new TwitterBootstrap4View();
        $pageHtml = $view->render($pagenate, $routeGenerator, []);

        return $this->get('view')->render($response, 'admin/tag/index.html.twig', [
            'tags' => $pagenate->getCurrentPageResults(),
            'page_html' => $pageHtml,
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
                    return $this->get('view')->render($response, 'admin/tag/create_confirm.html.twig', [
                         'form' => $form,
                    ]);
                }
                $this->get(TagRepository::class)->insert($form->getData()->getArrayCopy());
                $url = $request->getAttribute('routeParser')->urlFor('admin_tag_index');

                return $response->withHeader('Location', $url)->withStatus(302);
            }
        }

        return $this->get('view')->render($response, 'admin/tag/create.html.twig', [
            'form' => $form,
        ]);
    }

    public function edit(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $tag = $this->get(TagRepository::class)->findOneById($args['id']);
        if (is_null($tag)) {
            throw new HttpNotFoundException($request);
        }

        $form = $this->get(EditForm::class);
        $input = $this->get(EditInput::class);

        $input->exchangeArray($tag->getFormArray());
        $form->bind($input);
        if ('POST' === strtoupper($request->getMethod())) {
            $form->setData($request->getParsedBody());
            if ($form->isValid() && !$form->getData()->isBack()) {
                if ($form->getData()->isConfirm()) {
                    return $this->get('view')->render($response, 'admin/tag/edit_confirm.html.twig', [
                         'form' => $form,
                    ]);
                }
                $tag->setFormArray($form->getData()->getArrayCopy());
                $tag->save();
                $url = $request->getAttribute('routeParser')->urlFor('admin_tag_edit', ['id' => $args['id']]);

                return $response->withHeader('Location', $url)->withStatus(302);
            }
        }

        return $this->get('view')->render($response, 'admin/tag/edit.html.twig', [
            'form' => $form,
        ]);
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $tag = $this->get(TagRepository::class)->findOneById($args['id']);
        if (is_null($tag)) {
            throw new HttpNotFoundException($request);
        }

        $form = $this->get(DeleteForm::class);
        $input = $this->get(DeleteInput::class);

        $input->exchangeArray($tag->getFormArray());
        $form->bind($input);
        if ('POST' === strtoupper($request->getMethod())) {
            $form->setData($request->getParsedBody());
            if ($form->isValid()) {
                $tag->delete();
                $url = $request->getAttribute('routeParser')->urlFor('admin_tag_index');

                return $response->withHeader('Location', $url)->withStatus(302);
            }
        }

        return $this->get('view')->render($response, 'admin/tag/delete.html.twig', [
            'form' => $form,
        ]);
    }
}
