<?php

namespace Taka512\Form\Api;

use Psr\Http\Message\ResponseInterface;

class ErrorRenderer
{
    public function render400(ResponseInterface $response, array $errors)
    {
        $data = [];
        foreach ($errors as $k => $v) {
            foreach ($v as $message) {
                if (!isset($data[$k])) {
                    $data[$k] = [];
                }
                $data[$k][] = ['message' => $message, 'code' => $k];
            }
        }

        return $response
            ->withStatus(400)
            ->withHeader('Content-type', 'application/json')
#            ->withAddedHeader('Access-Control-Allow-Origin', '*')
            ->withJson($data);
    }

    // Method Not Allow
    public function render405(ResponseInterface $response)
    {
        return $response
            ->withStatus(405);
    }
}
