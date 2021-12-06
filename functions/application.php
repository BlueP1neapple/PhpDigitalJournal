<?php

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
        $pathToFile = $sourcePath;
        $content = file_get_contents($pathToFile);
        return json_decode($content, true);
    }

    /**
     * Логика основного приложения
     *
     * @param array $handlers - массив сопоставляющий в url path с функциями реализующий логику обработки запроса
     * @param string $requestUri - Переменная содержащая полный путь запроса
     * @param array $request - массив содержащий параметры поиска
     * @param callable $logger - название функции логирования
     * @return array - массив результатов
     */
    function app(array $handlers, string $requestUri, array $request, callable $logger): array
    {
        $urlPath = parse_url(
            $requestUri,
            PHP_URL_PATH
        ); // Создаётся переменная, урлПаф для того, что запросы без PATH_INFO обрабатывались корректно
        $logger('Url request received: ' . $requestUri . "\n");
        if (array_key_exists($urlPath, $handlers)) {
            $result = $handlers[$urlPath]($request, $logger);
        } else {
            $result = [
                'httpCode' => 404,
                'result' => [
                    'status' => 'fail',
                    'message' => 'unsupported request'
                ]
            ];
        }
        return $result;
    }
