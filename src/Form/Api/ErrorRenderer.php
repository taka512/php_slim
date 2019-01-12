<?php

namespace Taka512\Form\Api;

use Psr\Http\Message\ResponseInterface;

class ErrorRenderer
{
    public function render400(ResponseInterface $response, array $errors)
    {
        $data = [];
        foreach ($errors as $error) {
        }

        return $response
            ->withHeader('Content-type', 'application/json')
            ->withAddedHeader('Access-Control-Allow-Origin', '*')
            ->withJson($data);
    }
}
