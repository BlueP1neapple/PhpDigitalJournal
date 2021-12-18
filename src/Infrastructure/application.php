<?php
namespace JoJoBizzareCoders\DigitalJournal\Infrastructure;




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

