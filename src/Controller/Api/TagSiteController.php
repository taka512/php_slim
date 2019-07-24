<?php

namespace Taka512\Controller\Api;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Taka512\Controller\BaseController;

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
        $form = $this->get('form.api.tag_site.create_form');
        $input = $this->get('form.api.tag_site.create_input');
        $form->bind($input);
        if (!$request->isPost()) {
            return $this->get('form.api.error_renderer')->render405($response);
        }
        $json = $request->getBody();
        $data = json_decode($json, true);
        $this->get('logger')->info(print_r($data, true));
        $form->setData($data);
        if ($form->isValid()) {
            $tag = $this->get('repository.tag_site')->insert($form->getData()->getArrayCopy());

            return $this->get('form.api.tag_site.create_renderer')->render($response, $tag);
        } else {
            return $this->get('form.api.error_renderer')->render400($response, $form->getMessages());
        }

        return $response;
    }
}
