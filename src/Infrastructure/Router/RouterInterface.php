<?php

namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Router;

use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerRequest;

interface RouterInterface
{
    /**
     * Должен возвращать обработчик запросов
     * @param ServerRequest $serverRequest
     * @return callable|null
     */
    public function getDispatcher(ServerRequest $serverRequest): ?callable;
}