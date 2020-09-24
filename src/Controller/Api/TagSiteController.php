<?php

namespace Taka512\Controller\Api;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Taka512\Controller\BaseController;
use Taka512\Form\Api\TagSite\CreateInput;
use Taka512\Form\Api\TagSite\CreateForm;
use Taka512\Form\Api\TagSite\CreateRenderer;
use Taka512\Repository\TagSiteRepository;
use Taka512\Form\Api\ErrorRenderer;

class TagSiteController extends BaseController
{
    /**
     * @OA\Post(
     *     path="/api/tag_site",
     *     summary="create for tag site relations",
     *     description="add data in tag_site table ",
     *     operationId="/api/tag_site-post",
     *     tags={"tag"},
     *     @OA\RequestBody(
     *         description="List of user object",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/TagSite"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
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
     *         response=405,
     *         ref="#/components/responses/MethodNotAllowed"
     *     ),
     *     @OA\Response(
     *         response="500",
     *         ref="#/components/responses/Unexpected"
     *     ),
     * )
     */
    public function create(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $form = $this->get(CreateForm::class);
        $form->bind($this->get(CreateInput::class));
        if ('POST' !== strtoupper($request->getMethod())) {
            return $this->get(ErrorRenderer::class)->render405($response);
        }
        $json = $request->getBody();
        $data = json_decode($json, true);
        $form->setData($data);
        if ($form->isValid()) {
            $tag = $this->get(TagSiteRepository::class)->insert($form->getData()->getArrayCopy());

            return $this->get(CreateRenderer::class)->render($response, $tag);
        } else {
            return $this->get(ErrorRenderer::class)->render400($response, $form->getMessages());
        }

        return $response;
    }
}
