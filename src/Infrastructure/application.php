<?php
namespace JoJoBizzareCoders\DigitalJournal\Infrastructure;


use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\HttpResponse;

/**
 *Отображает результат клиенту
 *
 * @param HttpResponse $response
 *
 * @return void
 */
function render(HttpResponse $response): void
{
    foreach ($response->getHeaders() as $headerName=>$headerValue){
        header("$headerName: $headerValue");
    }
    http_response_code($response->getStatusCode());
    echo $response->getBody();
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

