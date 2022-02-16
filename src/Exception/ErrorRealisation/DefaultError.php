<?php

namespace JoJoBizzareCoders\DigitalJournal\Exception\ErrorRealisation;

use JoJoBizzareCoders\DigitalJournal\Exception\ExceptionInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class DefaultError implements ErrorRealisationInterface
{

    private ServerResponseFactory $serverResponseFactory;

    /**
     * @param ServerResponseFactory $serverResponseFactory
     */
    public function __construct(ServerResponseFactory $serverResponseFactory)
    {
        $this->serverResponseFactory = $serverResponseFactory;
    }


    public function sendError(int $httpCode, array $jsonData, Throwable $e): ?ResponseInterface
    {
        return $this->serverResponseFactory->createJsonResponse($httpCode, $jsonData);
    }
}