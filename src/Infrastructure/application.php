<?php

    require_once __DIR__ . '/../Infrastructure/AppConfig.php';


    /**
     * Логгирование текстовым сообщением
     * @param string $errorString - сообщение ошибки
     */
    function loggerInFile(string $errorString): void
    {
        file_put_contents(__DIR__ . "/../app.log", $errorString . "\n", FILE_APPEND);
    }

    /**
     * Функция рэдеренга
     * @param int $httpCode - код ответа
     * @param array $data - ответ
     */
    function render(int $httpCode, array $data): void
    {
        header('Content-Type: application/json');
        http_response_code($httpCode);
        echo json_encode($data);
        exit();
    }

    /**
     * Функция валидации
     * @param array $validateParameters - валидируемые параметры, ключ имя параметра, значение - текст ошибки
     * @param array $params - все параметры
     * @return array - сообщение о ошибках,
     */
    function paramTypeValidation(array $validateParameters, array $params): ?array
    {
        $result = null;
        foreach ($validateParameters as $paramName => $errorMsg) {
            if (array_key_exists($paramName, $params) && false === is_string($params[$paramName])) {
                $result = [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => $errorMsg,
                    ]
                ];
                break;
            }
        }
        return $result;
    }

    /**
     * Загузка данных
     * @param string $sourcePath
     * @return array
     */
    function loadData(string $sourcePath): array
    {
        $content = file_get_contents($sourcePath);
        return json_decode($content, true);
    }

    /**
     * Логика основного приложения
     *
     * @param array $handlers - массив сопоставляющий в url path с функциями реализующий логику обработки запроса
     * @param string $requestUri - Переменная содержащая полный путь запроса
     * @param callable $logger - название функции логирования
     * @param callable $appConfigFactory - конфиг приложения
     * @return array - массив результатов
     */
    function app(array $handlers, string $requestUri, callable $logger, callable $appConfigFactory): array
    {
        try{
            $requestParams=[];
            parse_str(parse_url($requestUri,PHP_URL_QUERY),$requestParams);
            $urlPath = parse_url(
                $requestUri,
                PHP_URL_PATH
            ); // Создаётся переменная, урлПаф для того, что запросы без PATH_INFO обрабатывались корректно
            $appConfig=$appConfigFactory();
            if(!($appConfig instanceof AppConfig)){
                throw new Exception('incorrect application config');
            }
            $logger('Url request received: ' . $requestUri . "\n");
            if (array_key_exists($urlPath, $handlers)) {
                $result = $handlers[$urlPath]($requestParams, $logger, $appConfig);
            } else {
                $result = [
                    'httpCode' => 404,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'unsupported request'
                    ]
                ];
            }
        } catch (InvalidDataStructureException $e) {
            $result = [
                'httpCode' => 503,
                'result' => [
                    'status' => 'fail',
                    'message' => $e->getMessage(),
                ]
            ];
        } catch (Throwable $e) {
            $result = [
                'httpCode' => 500,
                'result' => [
                    'status' => 'fail',
                    'message' => $e->getMessage(),
                ]
            ];
        }
        return $result;
    }
