<?php

    namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Controller;

    use JoJoBizzareCoders\DigitalJournal\Exception\InvalidDataStructureException;
    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\HttpResponse;
    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerRequest;
    use JoJoBizzareCoders\DigitalJournal\ValueObject\Address;
    use JoJoBizzareCoders\DigitalJournal\ValueObject\Fio;

    /**
     * Интерфейс контроллера
     */
    interface ControllerInterface
    {
        //Методы
        /**
         * Обработка http запроса
         *
         * @param ServerRequest $serverRequest - http запроса
         * @return HttpResponse - http ответа
         */
        public function __invoke (ServerRequest $serverRequest):HttpResponse;
    }