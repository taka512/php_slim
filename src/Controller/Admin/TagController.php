<?php

namespace Taka512\Controller\Admin;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Taka512\Controller\BaseController;
use Pagerfanta\View\TwitterBootstrap4View;

class TagController extends BaseController
{
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $getParams = $request->getQueryParams();
        $page = $getParams['page'] ?? 1;
        $pagenate = $this->get('manager.tag')->getTagPagenate($page);
        $routeGenerator = function ($page) {
            return htmlspecialchars(sprintf('/admin/tag?page=%s', $page), ENT_QUOTES);
        };
        $view = new TwitterBootstrap4View();
        $pageHtml = $view->render($pagenate, $routeGenerator, []);

        return $this->container->get('view')->render($response, 'admin/tag/index.html.twig', [
            'tags' => $pagenate->getCurrentPageResults(),
            'page_html' => $pageHtml,
        ]);
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $form = $this->get('form.admin.tag.create_form');
        $input = $this->get('form.admin.tag.create_input');

        $form->bind($input);
        if ($request->isPost()) {
            $form->setData($request->getParsedBody());
            if ($form->isValid() && !$form->getData()->isBack()) {
                if ($form->getData()->isConfirm()) {
                    return $this->container->get('view')->render($response, 'admin/tag/create_confirm.html.twig', [
                         'form' => $form,
                    ]);
                }
                $this->get('repository.tag')->insert($form->getData()->getArrayCopy());

                return $response->withRedirect($this->container->get('router')->pathFor('admin_tag_index'));
            }
        }

        return $this->get('view')->render($response, 'admin/tag/create.html.twig', [
            'form' => $form,
        ]);
    }

    public function edit(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $tag = $this->get('repository.tag')->findOneById($args['id']);
        if (is_null($tag)) {
            return $this->get('notFoundHandler')($request, $response);
        }

        $form = $this->get('form.admin.tag.edit_form');
        $input = $this->get('form.admin.tag.edit_input');

        $input->exchangeArray($tag->getFormArray());
        $form->bind($input);
        if ($request->isPost()) {
            $form->setData($request->getParsedBody());
            if ($form->isValid() && !$form->getData()->isBack()) {
                if ($form->getData()->isConfirm()) {
                    return $this->container->get('view')->render($response, 'admin/tag/edit_confirm.html.twig', [
                         'form' => $form,
                    ]);
                }
                $tag->setFormArray($form->getData()->getArrayCopy());
                $tag->save();

                return $response->withRedirect($this->get('router')->pathFor('admin_tag_edit', ['id' => $args['id']]));
            }
        }

        return $this->get('view')->render($response, 'admin/tag/edit.html.twig', [
            'form' => $form,
        ]);
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $tag = $this->get('repository.tag')->findOneById($args['id']);
        if (is_null($tag)) {
            return $this->get('notFoundHandler')($request, $response);
        }

        $form = $this->get('form.admin.tag.delete_form');
        $input = $this->get('form.admin.tag.delete_input');

        $input->exchangeArray($tag->getFormArray());
        $form->bind($input);
        if ($request->isPost()) {
            $form->setData($request->getParsedBody());
            if ($form->isValid()) {
                $tag->delete();

                return $response->withRedirect($this->get('router')->pathFor('admin_tag_index'));
            }
        }

        return $this->get('view')->render($response, 'admin/tag/delete.html.twig', [
            'form' => $form,
        ]);
    }
}
