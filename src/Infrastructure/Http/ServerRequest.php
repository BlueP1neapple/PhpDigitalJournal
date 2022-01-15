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
         * Атрибуты серверного запроса
         *
         * @var array
         */
        private array $attributes = [];


        // Методы

        /**
         * Возвращет атрибуты серверного запроса
         *
         * @return array
         */
        public function getAttributes(): array
        {
            return $this->attributes;
        }

        /**
         * Устанавливает атрибуты серверного запроса
         *
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