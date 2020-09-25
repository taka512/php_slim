<?php

declare(strict_types=1);

namespace Taka512;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Handlers\ErrorHandler;
use Slim\Views\Twig;
use Throwable;

class HttpErrorHandler extends ErrorHandler
{
    protected $logger;
    protected $view;

    const BAD_REQUEST = 'BAD_REQUEST';
    const INSUFFICIENT_PRIVILEGES = 'INSUFFICIENT_PRIVILEGES';
    const NOT_ALLOWED = 'NOT_ALLOWED';
    const NOT_IMPLEMENTED = 'NOT_IMPLEMENTED';
    const RESOURCE_NOT_FOUND = 'RESOURCE_NOT_FOUND';
    const SERVER_ERROR = 'SERVER_ERROR';
    const UNAUTHENTICATED = 'UNAUTHENTICATED';

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    protected function logError(string $error): void
    {
        if ($this->exception instanceof HttpException) {
            if (500 <= $this->exception->getCode()) {
                $this->logger->error($error);
            }
        } else {
            $this->logger->error($error);
        }
    }

    public function setTwigView(Twig $view)
    {
        $this->view = $view;
    }

    protected function respond(): ResponseInterface
    {
        $exception = $this->exception;
        $statusCode = 500;
        $type = self::SERVER_ERROR;
        $description = 'An internal error has occurred while processing your request.';

        if ($exception instanceof HttpException) {
            $statusCode = $exception->getCode();
            $description = $exception->getMessage();

            if ($exception instanceof HttpNotFoundException) {
                $type = self::RESOURCE_NOT_FOUND;
            } elseif ($exception instanceof HttpMethodNotAllowedException) {
                $type = self::NOT_ALLOWED;
            } elseif ($exception instanceof HttpUnauthorizedException) {
                $type = self::UNAUTHENTICATED;
            } elseif ($exception instanceof HttpForbiddenException) {
                $type = self::UNAUTHENTICATED;
            } elseif ($exception instanceof HttpBadRequestException) {
                $type = self::BAD_REQUEST;
            } elseif ($exception instanceof HttpNotImplementedException) {
                $type = self::NOT_IMPLEMENTED;
            }
        }

        if (
            !($exception instanceof HttpException)
            && ($exception instanceof Exception || $exception instanceof Throwable)
            && $this->displayErrorDetails
        ) {
            $description = $exception->getMessage();
        }
        $response = $this->responseFactory->createResponse($statusCode);

        return $this->view
            ->render($response, 'error.html.twig', [
                'description' => $description,
                'status' => $statusCode,
                'title' => $type,
            ])
            ->withHeader('Content-type', 'text/html');
    }
}
