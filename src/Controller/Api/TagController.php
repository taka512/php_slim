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
     *     summary="find tag by search condition",
     *     description="Returns array tag",
     *     operationId="getTagsBySearchConditions",
     *     tags={"tag"},
     *     @OA\Parameter(ref="#/components/requestBodies/product_in_body"),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="search by tag name",
     *         required=false,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="site_id",
     *         in="query",
     *         description="search by site id",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             format="int32"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="maximum number of results to return",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             format="int32"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="offset",
     *         in="query",
     *         description="offset of results to return",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             format="int32"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid Search Conditions supplied"
     *     ),
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

            return $this->get('form.api.tag.search_renderer')->render($response, $tags);
        } else {
            return $this->get('form.api.error_renderer')->render400($response, $form->getMessages());
        }
    }
}
