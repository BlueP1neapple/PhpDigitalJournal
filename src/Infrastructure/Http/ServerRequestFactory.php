<?php

    namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Http;


    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\Exception\ErrorHttpRequestException;
    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Uri\Uri;

    /**
     * Фабрика отвечающая за создание объекта ServerRequest
     */
    class ServerRequestFactory
    {
// Свойства
        /**
         * Обязательные ключи в массиве входящих данных
         */
        private const REQUIRED_FIELDS = [
            'SERVER_PROTOCOL',
            'SERVER_PORT',
            'REQUEST_URI',
            'REQUEST_METHOD',
            'SERVER_NAME'
        ];

        /**
         * Разрешенные http методы
         */
        private const ALLOWED_HTTP_METHOD = [
            'GET',
            'POST',
            'PUT',
            'DELETE'
        ];

        // Методы

        /**
         * Валидация наличия обязательных полей. Также проверяеться, что заданные поля соотвествуют задонному типу.
         *
         * @param array $globalServer - валидируемые данные из $_SERVER
         * @return void
         */
        private static function validateRequiredFields(array $globalServer): void
        {
            foreach (self::REQUIRED_FIELDS as $fieldName) {
                if (false === array_key_exists($fieldName, $globalServer)) {
                    throw new ErrorHttpRequestException(
                        "Для создания объекта серверного http запроса необходимо знать: '$fieldName'"
                    );
                }
                if (false === is_string($globalServer[$fieldName])) {
                    throw new ErrorHttpRequestException(
                        "Для создания объекта серверного http запроса необходимо чтобы '$fieldName' было представленно строкой"
                    );
                }
            }
        }

        /**
         * Валидация  http методов
         *
         * @param string $httpMethod - Методы http
         * @return void
         */
        private static function httpValidationMethod(string $httpMethod): void
        {
            if (false === in_array($httpMethod, self::ALLOWED_HTTP_METHOD)) {
                throw new ErrorHttpRequestException(
                    "Некорректный http метод '$httpMethod'"
                );
            }
        }

        /**
         * Серверный объект запроса из глобальных перемеррных
         *
         * @param array $globalServer - данные из глобальной переменной $_SERVER
         * @param string|null $body - тело http запроса
         *
         * @return ServerRequest
         */
        public static function createFromGlobal(array $globalServer, string $body = null): ServerRequest
        {
            self::validateRequiredFields($globalServer);
            self::httpValidationMethod($globalServer['REQUEST_METHOD']);

            $method = $globalServer['REQUEST_METHOD'];
            $requestTarget = $globalServer['REQUEST_URI'];


            $protocolVersion = self::extractProtocolVersion($globalServer['SERVER_PROTOCOL']);


            $uri = Uri::createFromString(self::buildUri($globalServer));

            $headers = self::extractHeaders($globalServer);
            return new ServerRequest($method, $protocolVersion, $requestTarget, $uri, $headers, $body);
        }

        /**
         * Извлекает версию протокола
         *
         * @param string $protocolVersionRaw - версия протокола
         * @return string
         */
        private static function extractProtocolVersion(string $protocolVersionRaw): string
        {
            if ('HTTP/1.1' === $protocolVersionRaw) {
                $version = '1.1';
            } elseif ('HTTP/1.0' === $protocolVersionRaw) {
                $version = '1.0';
            } else {
                throw new ErrorHttpRequestException(
                    "Неподдерживаемая версия http протокола '$protocolVersionRaw'"
                );
            }

            return $version;
        }

        private static function extractHeaders(array $globalServer): array
        {
            $headers = [];

            foreach ($globalServer as $key => $value) {
                if (0 === strpos($key, 'HTTP_')) {
                    $name = str_replace('_', '-', strtolower(substr($key, 5)));
                    $headers[$name] = $value;
                }
            }

            return $headers;
        }

        /**
         * собираем Uri из $_SERVER
         *
         * @param array $globalServer - Копия глобальной пересенной $_SERVER
         * @return string
         */
        private static function buildUri(array $globalServer): string
        {
            $uri = $globalServer['REQUEST_URI'];
            if ('' !== $globalServer['SERVER_NAME']) {
                $uriServerInfo = self::extractUriScheme($globalServer) . '://' . $globalServer['SERVER_NAME'];
                self::validatePort($globalServer['SERVER_PORT']);
                $uriServerInfo .= ':' . $globalServer['SERVER_PORT'];

                if (0 === strpos($uri, '/')) {
                    $uri = $uriServerInfo . $uri;
                } else {
                    $uri = $uriServerInfo . '/' . $uri;
                }
            }
            return $uri;
        }

        /**
         * Не корректный номер порта
         *
         * @param string $portString - номер порта
         */
        private static function validatePort(string $portString): void
        {
            if ($portString !== (string)(int)$portString) {
                throw new Exception\ErrorHttpRequestException(
                    "Не корректный порт: '$portString'"
                );
            }
        }

        /**
         * Извлекает информацию о схеме
         *
         * @param array $globalServer - Копия глобальной пересенной $_SERVER
         * @return string
         */
        private static function extractUriScheme(array $globalServer): string
        {
            $schema = 'http';
            if (array_key_exists('HTTPS', $globalServer) && 'on' === $globalServer['HTTPS']) {
                $schema = 'https';
            }
            return $schema;
        }
    }