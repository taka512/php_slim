<?php

namespace Taka512\Form\Api\Tag;

use Psr\Http\Message\ResponseInterface;

class SearchRenderer
{
    public function render(ResponseInterface $response, $tags)
    {
        $data = [];
        foreach ($tags as $tag) {
            $data[] = ['id' => $tag->id, 'name' => $tag->name];
        }

        return $response
            ->withHeader('Content-type', 'application/json')
            ->withAddedHeader('Access-Control-Allow-Origin', '*')
            ->withJson($data);
    }
}
