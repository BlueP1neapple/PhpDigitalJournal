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
        /**
         * Атребуты серверного запроса
         * @var array
         */
        private array $attributes = [];
        // Методы

        /**
         * @return array
         */
        public function getAttributes(): array
        {
            return $this->attributes;
        }

        /**
         * Устанавливает атребуты
         * @param array $attributes
         * @return ServerRequest
         */
        public function setAttributes(array $attributes): ServerRequest
        {
            $this->attributes = $attributes;
            return $this;
        }




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