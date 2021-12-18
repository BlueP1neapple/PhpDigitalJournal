<?php

    namespace JoJoBizzareCoders\DigitalJournalTest\Infrastructure\Http;

    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Autoloader;
    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerRequestFactory;
    use JoJoBizzareCoders\DigitalJournalTest\TestUtils;

    require_once __DIR__ . '/../../../src/Infrastructure/Autoloader.php';
    spl_autoload_register(
        new Autoloader([
            'JoJoBizzareCoders\\DigitalJournal\\' => __DIR__ . '/../../../src/',
            'JoJoBizzareCoders\\DigitalJournalTest\\' => __DIR__ . '/../../../tests/',
        ])
    );

    /**
     * Тестирует логику работы фабрики, создающий серверный http запрос
     */
    final class ServerRequestFactoryTest
    {
        /**
         * Тестирование создание серверного запроса
         *
         * @return void
         */
        public static function testCreateFromGlobals(): void
        {
            echo "----------Тестирование создание серверного запроса----------\n";

            // Arrange
            $servers = [
                'SERVER_PROTOCOL' => 'HTTP/1.1',
                'SERVER_PORT' => '80',
                'REQUEST_URI' => '/samhtml/ssylki/absolyutnye-i-otnositelnye-ssylki?query=value1#fragment-example',
                'REQUEST_METHOD' => 'GET',
                'SERVER_NAME' => 'localhost',

                'HTTP_HOST' => 'localhost:80',
                'HTTP_CONNECTION' => 'Keep-Alive',
                'HTTP_USER_AGENT' => 'Apache-HttpClient\/4.5.13 (Java\/11.0.11)',
                'HTTP_COOKIE' => 'XDEBUG_SESSION=16151',
            ];
            $expectedUri = 'http://localhost:80/samhtml/ssylki/absolyutnye-i-otnositelnye-ssylki?query=value1#fragment-example';


            //Act
            $expectedBody = 'test';
            $httpServerRequest = ServerRequestFactory::createFromGlobals($servers, $expectedBody);
            $actualUriString = (string)$httpServerRequest->getUri();

            //Assert
            if ($expectedUri === $actualUriString) {
                echo "     OK - объект сервернного http запроса корректно создан\n";
            } else {
                echo "     Fail - объект сервернного http запроса некорректно создан. Ожидалось $expectedUri. Актуальное значение: $actualUriString\n";
            }

            if ($expectedBody === $httpServerRequest->getBody()) {
                echo "     OK - корректное тело запроса\n";
            } else {
                echo "     OK - некорректное тело запроса. Ожидалось $expectedBody. Актуальное значение: {$httpServerRequest->getBody()}\n";
            }

            $expectedProtocolVersion = '1.1';
            if ($expectedProtocolVersion === $httpServerRequest->getProtocolVersion()) {
                echo "     OK - корректное протокол версии запроса\n";
            } else {
                echo "     OK - некорректное протокол версии запроса. Ожидалось $expectedProtocolVersion. Актуальное значение: {$httpServerRequest->getProtocolVersion()}\n";
            }

            $expectedMethod = 'GET';
            if ($expectedMethod === $httpServerRequest->getMethod()) {
                echo "     OK - корректный метод протокола\n";
            } else {
                echo "     OK - некорректный метод протокола. Ожидалось $expectedMethod. Актуальное значение: {$httpServerRequest->getMethod()}\n";
            }

            $expectedRequestTarget = '/samhtml/ssylki/absolyutnye-i-otnositelnye-ssylki?query=value1#fragment-example';
            if ($expectedRequestTarget === $httpServerRequest->getRequestTarget()) {
                echo "     OK - корректное пояснение запроса\n";
            } else {
                echo "     OK - некорректное пояснение запроса. Ожидалось $expectedRequestTarget. Актуальное значение: {$httpServerRequest->getRequestTarget()}\n";
            }

            $actualQueryParams = $httpServerRequest->getQueryParams();
            $expectedQueryParams = [
                'query' => 'value1'
            ];

            //Лишние Элементы
            $unnecessaryQueryParams = TestUtils::arrayDiffAssocRecursive($actualQueryParams, $expectedQueryParams);
            //Недостоющие Элементы
            $missingQueryParams = TestUtils::arrayDiffAssocRecursive($expectedQueryParams, $actualQueryParams,);

            $errMsg = '';
            if (count($unnecessaryQueryParams) > 0) {
                $errMsg .= sprintf(
                    "     Есть лишние элементы %s\n",
                    json_encode($unnecessaryQueryParams, JSON_UNESCAPED_UNICODE)
                );
            }
            if (count($missingQueryParams) > 0) {
                $errMsg .= sprintf(
                    "     Есть недостоющие элементы %s\n",
                    json_encode($missingQueryParams, JSON_UNESCAPED_UNICODE)
                );
            }
            if ('' === $errMsg) {
                echo "     ok-данные параметров запроса валидны";
            } else {
                echo "      ok-данные параметров запроса валидны\n" . $errMsg;
            }
        }
    }

    ServerRequestFactoryTest::testCreateFromGlobals();