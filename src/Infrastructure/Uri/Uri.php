<?php

    namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Uri;


    /**
     * Ури
     */
    class Uri
    {
        /**
         * http схема
         *
         * @var string
         */
        private string $schema;

        /**
         * Информация о пользователе
         *
         * @var string
         */
        private string $userInfo;

        /**
         * Хост (доменное имя)
         *
         * @var string
         */
        private string $host;

        /**
         * Порт
         *
         * @var int|null
         */
        private ?int $port;

        /**
         * Путь к ресурсу
         *
         * @var string
         */
        private string $path;

        /**
         * Параметры запроса
         *
         * @var string
         */
        private string $query;

        /**
         * фрагмент
         *
         * @var string
         */
        private string $fragment;


        /**
         * Конструктор Ури
         *
         * @param string $schema - http схема
         * @param string $userInfo - Информация о пользователе
         * @param string $host - Хост (доменное имя)
         * @param int|null $port - Порт
         * @param string $path - Путь к ресурсу
         * @param string $query - Параметры запроса
         * @param string $fragment - фрагмент
         */
        public function __construct(
            string $schema = '',
            string $userInfo = '',
            string $host = '',
            ?int $port = null,
            string $path = '',
            string $query = '',
            string $fragment = ''
        ) {
            $this->schema = $schema;
            $this->userInfo = $userInfo;
            $this->host = $host;
            $this->port = $port;
            $this->path = $path;
            $this->query = $query;
            $this->fragment = $fragment;
        }

        /**
         * Возвращает http схема
         *
         * @return string
         */
        public function getSchema(): string
        {
            return $this->schema;
        }

        /**
         * Возвращает Информация о пользователе
         *
         * @return string
         */
        public function getUserInfo(): string
        {
            return $this->userInfo;
        }

        /**
         * Возвращает Хост (доменное имя)
         *
         * @return string
         */
        public function getHost(): string
        {
            return $this->host;
        }

        /**
         * Возвращает Порт
         *
         * @return int
         */
        public function getPort(): ?int
        {
            return $this->port;
        }

        /**
         * Возвращает Путь к ресурсу
         *
         * @return string
         */
        public function getPath(): string
        {
            return $this->path;
        }

        /**
         * Возвращает Параметры запроса
         *
         * @return string
         */
        public function getQuery(): string
        {
            return $this->query;
        }

        /**
         * Возвращает фрагмент
         *
         * @return string
         */
        public function getFragment(): string
        {
            return $this->fragment;
        }

        /**
         * Создаёт строку
         *
         * @return string
         */
        public function __toString()
        {
            $schema = '' === $this->schema ? '' : "$this->schema://";
            $userInfo = '' === $this->userInfo ? $this->userInfo : "$this->userInfo@";
            $port = null === $this->port ? '' : ":$this->port";
            $query = '' === $this->query ? $this->query : "?$this->query";
            $fragment = '' === $this->fragment ? $this->fragment : "#$this->fragment";

            return "$schema$userInfo$this->host$port$this->path$query$fragment";
        }

        /**
         * Создаёт объект uri из строки
         *
         * @param string $uri
         * @return Uri
         */
        public static function createFromString(string $uri): Uri
        {
            $urlParts = parse_url($uri);
            if (false === is_array($urlParts)) {
                throw new Exception\ErrorUriException("Ошибка разбора строки '$uri' а состовные части");
            }

            $schema = array_key_exists('scheme', $urlParts) ? $urlParts['scheme'] : '';
            $host = array_key_exists('host', $urlParts) ? $urlParts['host'] : '';
            $port = $urlParts['port'] ?? null;
            $userInfo = array_key_exists('user', $urlParts) ? $urlParts['user'] : '';
            if (array_key_exists('pass', $urlParts)) {
                $userInfo .= ":{$urlParts['pass']}";
            }
            $path = array_key_exists('path', $urlParts) ? $urlParts['path'] : '';
            $query = array_key_exists('query', $urlParts) ? $urlParts['query'] : '';
            $fragment = array_key_exists('fragment', $urlParts) ? $urlParts['fragment'] : '';

            return new Uri(
                $schema,
                $userInfo,
                $host,
                $port,
                $path,
                $query,
                $fragment
            );
        }
    }