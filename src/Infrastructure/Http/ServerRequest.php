<?php

    namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Http;

    /**
     * Серверный запрос
     */
    class ServerRequest extends HttpRequest
    {
        // Свойства
        /**
         * Параметры запроса
         *
         * @var array|null
         */
        private ?array $queryParams = null;


        // Методы

        /**
         * Возвращает параметры запроса
         *
         * @return array
         */
        public function getQueryParams(): array
        {
            if (null === $this->queryParams) {
                $queryParams = [];
                parse_str($this->getUri()->getQuery(), $queryParams);
                $this->queryParams = $queryParams;
            }
            return $this->queryParams;
        }
    }