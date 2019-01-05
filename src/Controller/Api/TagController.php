<?php

namespace Taka512\Controller\Api;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Taka512\Controller\BaseController;

class TagController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/tag",
     *     @OA\Response(response="200", description="get tag list")
     * )
     */
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $form = $this->get('form.api.tag.search_form');
        $input = $this->get('form.api.tag.search_input');

        $form->bind($input);
        $form->setData($request->getQueryParams());
        $tags = [];
        if ($form->isValid()) {
            $tags = $this->get('repository.tag')->findBySearchConditions($form->getData()->getArrayCopy());
        }

        return $this->get('form.api.tag.search_renderer')->render($response, $tags);
    }
}
