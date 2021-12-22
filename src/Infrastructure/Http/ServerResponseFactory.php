<?php

    namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Http;

    use JoJoBizzareCoders\DigitalJournal\Exception\RuntimeException;
    use Throwable;

    /**
     * Фабрика реализующая создание http ответ
     */
    class ServerResponseFactory
    {
        // Свойства
        /**
         * Расщифровка http кодов
         */
        private const PHRASES = [
            200 => 'OK',
            404 => 'Not found',
            500 => 'Internal Server Error',
            503 => 'Service Unavailable',
        ];

        // Методы

        /**
         * Создаёт ответ с данными
         *
         * @param int $code - код ответа
         * @param array $data - Данные ответа
         *
         * @return HttpResponse
         */
        public static function createJsonResponse(int $code, array $data): HttpResponse
        {
            try {
                $body = json_encode($data, JSON_THROW_ON_ERROR);
                if (false === array_key_exists($code, self::PHRASES)) {
                    throw new RuntimeException('Некорректный код ответа');
                }
                $phrases = self::PHRASES[$code];
            } catch (Throwable $e) {
                $body = '{"status": "fail", "message": "response coding error"}';
                $code = 502;
                $phrases = "Unknown error";
            }
            return new HttpResponse('1.1', $code, $phrases, ['Content-Type' => 'application/json'], $body);
        }

    }