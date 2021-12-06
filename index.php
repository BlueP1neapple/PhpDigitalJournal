<?php

// Функции
    require_once "functions/application.php";


    $requestUri = $_SERVER['REQUEST_URI'];
    $urlPath = parse_url($requestUri, PHP_URL_PASS);

    $resultApplication = app
    (
        include __DIR__ . '/functions/request.handlers.php',
        $_SERVER['REQUEST_URI'], //Полный путь запроса
        $_GET, //Глобальная перменная с параметрами поиска
        'loggerInFile' //Название функции логирования
    );

    render($resultApplication['httpCode'], $resultApplication['result']);