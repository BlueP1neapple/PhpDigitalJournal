<?php

    namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Http;

    /**
     * Http ответ
     */
    class HttpResponse extends AbstractMessage
    {
        //Свойства
        /**
         * http код
         *
         * @var int
         */
        private int $statusCode;

        /**
         * Пояснение
         *
         * @var string
         */
        private string $reasonPhrase;

        //Методы
        /**
         * Конструктор
         *
         * @param string $protocolVersion - Версия протокола
         * @param int $statusCode - http код
         * @param string $reasonPhrase - Пояснение
         * @param array $headers - Заголовки
         * @param string|null $body - Тело
         */
        public function __construct(
            string $protocolVersion,
            int $statusCode,
            string $reasonPhrase,
            array $headers,
            ?string $body
        ) {
            parent::__construct($protocolVersion, $headers, $body);
            $this->statusCode = $statusCode;
            $this->reasonPhrase = $reasonPhrase;
        }

        /**
         * Возвращает http код
         *
         * @return int
         */
        public function getStatusCode(): int
        {
            return $this->statusCode;
        }

        /**
         * Возвращает Пояснение
         *
         * @return string
         */
        public function getReasonPhrase(): string
        {
            return $this->reasonPhrase;
        }

    }