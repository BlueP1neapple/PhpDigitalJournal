<?php

    namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Http;

    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Uri\Uri;

    /**
     * http запрос
     */
    class HttpRequest extends AbstractMessage
    {
        // Свойтсва
        /**
         * http метод
         *
         * @var string
         */
        private string $method;

        /**
         * Цель запроса
         *
         * @var string
         */
        private string $requestTarget;

        /**
         * Uri
         *
         * @var Uri
         */
        private Uri $uri;


        // Методы
        /**
         * Конструктор
         *
         * @param string $method - http метод
         * @param string $protocolVersion - Версия протокола
         * @param string $requestTarget - Цель запроса
         * @param Uri $uri - Uri
         * @param array $headers - Заголовки
         * @param string|null $body - Тело сообщения
         */
        public function __construct(
            string $method,
            string $protocolVersion,
            string $requestTarget,
            Uri $uri,
            array $headers,
            ?string $body
        )
        {
            parent::__construct($protocolVersion, $headers, $body);
            $this->method = $method;
            $this->requestTarget = $requestTarget;
            $this->uri = $uri;
        }

        /**
         * Возвращает http метод
         *
         * @return string
         */
        public function getMethod(): string
        {
            return $this->method;
        }

        /**
         * Возвращает Цель запроса
         *
         * @return string
         */
        public function getRequestTarget(): string
        {
            return $this->requestTarget;
        }

        /**
         * Возвращает Uri
         *
         * @return Uri
         */
        public function getUri(): Uri
        {
            return $this->uri;
        }

    }