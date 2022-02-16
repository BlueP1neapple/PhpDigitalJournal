<?php

namespace JoJoBizzareCoders\DigitalJournal\Exception\ErrorRealisation;


use Psr\Http\Message\ResponseInterface;
use Throwable;

interface ErrorRealisationInterface
{
    /**
     * @param int $httpCode - хттп код
     * @param array $jsonData
     * @param Throwable $e
     * @return mixed
     */
    public function sendError(int $httpCode, array $jsonData, Throwable $e): ?ResponseInterface;
}