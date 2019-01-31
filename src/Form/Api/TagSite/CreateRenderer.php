<?php

namespace Taka512\Form\Api\TagSite;

use Psr\Http\Message\ResponseInterface;

class CreateRenderer
{
    public function render(ResponseInterface $response, $tagSite)
    {
        $data = ['tag_id' => $tagSite->tagId, 'site_id' => $tagSite->siteId];

        return $response
            ->withHeader('Content-type', 'application/json')
            ->withJson($data);
    }
}
