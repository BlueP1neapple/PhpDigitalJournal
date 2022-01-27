<?php

    namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Http;

    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Exception\RuntimeException;
    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Exception\UnexpectedValueException;
    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Uri\Uri;
    use Throwable;

    /**
     * Фабрика реализующая создание http ответ
     */
    class ServerResponseFactory
    {
        /**
         * Расщифровка http кодов
         */
        private const PHRASES = [
            200 => 'OK',
            201 => 'Create',
            301 => 'Moved Permanent',
            302 => 'Redirect',
            404 => 'Not found',
            500 => 'Internal Server Error',
            503 => 'Service Unavailable',
        ];


        /**
         * Создаёт ответ с данными
         *
         * @param int $code - код ответа
         * @param array $data - Данные ответа
         *
         * @return HttpResponse
         */
        public static function createJsonResponse(int $code,  $data): HttpResponse
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

        /**
         * Редирект
         *
         * @param Uri $uri
         * @param int $httpCode
         * @return HttpResponse
         */
        public static function redirect(Uri $uri, int $httpCode = 302):HttpResponse
        {
            try{
                if (!($httpCode >= 300 && $httpCode < 400)){
                    throw new RuntimeException('Не корректный код ответа');
                }

                if (false === array_key_exists($httpCode, self::PHRASES)){
                    throw new RuntimeException('Не корректный код ответа');
                }
                $phrases = self::PHRASES[$httpCode];
                $body =  '';
                $headers = ['Location' => (string)$uri];
            }catch (Throwable $e){
                $body = '<h1>Unknown error</h1>';
                $httpCode = 520;
                $phrases = 'Unknown error';
                $headers = ['Content-Type' => 'text/html'];
            }

            return new HttpResponse('1.1', $httpCode, $phrases, $headers, $body);
        }


        /**
         * Созадёт html ответ
         *
         * @param int $code
         * @param string $html
         * @return HttpResponse
         */
        public static function createHtmlResponse(int $code, string $html):HttpResponse
        {
            try {
                if(false === array_key_exists($code,self::PHRASES)){
                    throw new UnexpectedValueException('Некорректный код ответа');
                }
                $phrases = self::PHRASES[$code];
            }catch (Throwable $e){
                $html = '<h1>Unknown error</h1>';
                $code = 520;
                $phrases = 'Unknown error';
            }

            return new HttpResponse('1.1', $code, $phrases,['Content-Type' => 'text/html'], $html);
        }

    }