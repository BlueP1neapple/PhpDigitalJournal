<?php

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
 * Обработка ошибок
 * @param string $status - статус ответа
 * @param string $message - сообщение об ошибке
 * @param int $httpCode - код ошибки
 */
function errorHandLing(string $status, string $message, int $httpCode): void
{
    //logger($message);
    $result = [
        'status' => $status,
        'message' => $message
    ];
    render($httpCode, $result);
    exit();
}

/**
 * Функция валидации
 * @param string $paramName - Имя параметра
 * @param array $params - все параметры
 * @param string $errorMsg - сообщение об ошибке
 */
function paramTypeValidation(string $paramName, array $params, string $errorMsg): void
{
    if (array_key_exists($paramName, $params) && false === is_string($params[$paramName])) {
        errorHandLing('fail', $errorMsg, 500);
    }
}

/**
 * Логгирование текстовым сообщением
 * @param string $errMsg
 */
function logger(string $errorString): void
{
    file_put_contents(__DIR__ . "/../app.log", $errorString . "\n", FILE_APPEND);
}
