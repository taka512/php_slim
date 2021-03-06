<?php

namespace Taka512\Controller\Api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Taka512\Controller\BaseController;
use Taka512\Form\Api\ErrorRenderer;
use Taka512\Form\Api\Tag\SearchForm;
use Taka512\Form\Api\Tag\SearchInput;
use Taka512\Form\Api\Tag\SearchRenderer;
use Taka512\Repository\TagRepository;

class TagController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/tag",
     *     summary="find tag by search condition",
     *     description="find tag by search condition and Returns array tag",
     *     operationId="/api/tag-get",
     *     tags={"tag"},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="search by tag name",
     *         required=false,
     *         @OA\Schema(
     *             ref="#/components/schemas/Tag/properties/name"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="site_id",
     *         in="query",
     *         description="search by site id",
     *         required=false,
     *         @OA\Schema(
     *             ref="#/components/schemas/Site/properties/id"
     *         )
     *     ),
     *     @OA\Parameter(
     *         ref="#/components/parameters/limit"
     *     ),
     *     @OA\Parameter(
     *         ref="#/components/parameters/offset"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/Tag"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         ref="#/components/responses/BadRequest"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         ref="#/components/responses/Forbidden"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         ref="#/components/responses/NotFound"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         ref="#/components/responses/MethodNotAllowed"
     *     ),
     *     @OA\Response(
     *         response="500",
     *         ref="#/components/responses/Unexpected"
     *     ),
     * )
     */
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $form = $this->get(SearchForm::class);
        $input = $this->get(SearchInput::class);

        $form->bind($input);
        $form->setData($request->getQueryParams());
        $tags = [];
        if ($form->isValid()) {
            $tags = $this->get(TagRepository::class)->findBySearchConditions($form->getData()->getArrayCopy());

            return $this->get(SearchRenderer::class)->render($response, $tags);
        } else {
            return $this->get(ErrorRenderer::class)->render400($response, $form->getMessages());
        }
    }
}
