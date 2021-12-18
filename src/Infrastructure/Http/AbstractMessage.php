<?php

    namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Http;

    /**
     * Абстрактное http сообщение
     */
    abstract class AbstractMessage
    {
        // Свойства
        /**
         * Версия протокола
         *
         * @var string
         */
        private string $protocolVersion;

        /**
         * Заголовки
         *
         * @var array
         */
        private array $headers;

        /**
         * Тело сообщения
         *
         * @var string|null
         */
        private ?string $body;

        // Методы

        /**
         * Конструктор
         *
         * @param string $protocolVersion - Версия протокола
         * @param array $headers - Заголовки
         * @param string|null $body - Тело сообщения
         */
        public function __construct(string $protocolVersion, array $headers, ?string $body)
        {
            $this->protocolVersion = $protocolVersion;
            $this->headers = $headers;
            $this->body = $body;
        }

        /**
         * Возвращает ерсия протокола
         *
         * @return string
         */
        final public function getProtocolVersion(): string
        {
            return $this->protocolVersion;
        }

        /**
         * Возвращает Заголовки
         *
         * @return array
         */
        final public function getHeaders(): array
        {
            return $this->headers;
        }

        /**
         * Возвращает Тело сообщения
         *
         * @return string|null
         */
        final public function getBody(): ?string
        {
            return $this->body;
        }


    }