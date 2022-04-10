<?php

namespace JoJoBizzareCoders\DigitalJournal\Exception\ErrorRealisation;

use Psr\Http\Message\ResponseInterface;
use Spatie\Ignition\Ignition;
use Throwable;

class IgnitionError implements ErrorRealisationInterface
{

    /**
     * @inheritDoc
     */
    public function sendError(int $httpCode, array $jsonData, Throwable $e): ?ResponseInterface
    {
        Ignition::make()->useDarkMode()->register();
        throw $e;

    }
}