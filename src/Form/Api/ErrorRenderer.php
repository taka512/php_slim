<?php

namespace Taka512\Form\Api;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface;

class ErrorRenderer
{
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function render400(ResponseInterface $response, array $errors): ResponseInterface
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
        $response->getBody()->write(json_encode($data));

        return $response
            ->withStatus(400)
            ->withHeader('Content-type', 'application/json');
    }

    // Method Not Allow
    public function render405(ResponseInterface $response): ResponseInterface
    {
        return $response
            ->withStatus(405);
    }

    public function render500(ResponseInterface $response, $msg): ResponseInterface
    {
        $this->logger->error($msg);

        return $response
            ->withStatus(500);
    }
}
