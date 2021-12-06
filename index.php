<?php

// Функции
    require_once "functions/application.php";

    $resultApplication = app
    (
        include __DIR__ . '/functions/request.handlers.php',
        //Массив путей запросов ведущие к функциям реализующие этот запрос
        $_SERVER['REQUEST_URI'],
        //Полный путь запроса
        $_GET,
        //Глобальная перменная с параметрами поиска
        'loggerInFile' //Название функции логирования
    );

    render($resultApplication['httpCode'], $resultApplication['result']); // Рэндер конечного результата