<?php

    namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Controller;

    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\HttpResponse;
    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerRequest;

    /**
     * Интерфейс контроллера
     */
    interface ControllerInterface
    {
        /**
         * Обработка http запроса
         *
         * @param ServerRequest $serverRequest - http запроса
         * @return HttpResponse - http ответа
         */
        public function __invoke (ServerRequest $serverRequest):HttpResponse;
    }