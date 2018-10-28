<?php

namespace Taka512\Controller\Api;

use Taka512\Controller\BaseController;

class TestController extends BaseController
{
    public function index($request, $response, $args)
    {
        return $response
            ->withHeader('Content-type', 'application/json')
            ->withJson(['hoge' => 'test']);
    }
}
