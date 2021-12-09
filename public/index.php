<?php

// Функции
    require_once __DIR__."/../src/Infrastructure/application.php";
    require_once __DIR__."/../src/Infrastructure/AppConfig.php";

    $resultApplication = app
    (
        include __DIR__ . '/../config/request.handlers.php',
        //Массив путей запросов ведущие к функциям реализующие этот запрос
        $_SERVER['REQUEST_URI'],
        //Полный путь запроса
        'loggerInFile', //Название функции логирования
        static function () {
            return AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');
        } // Конфиг приложения
    );

    render($resultApplication['httpCode'], $resultApplication['result']); // Рэндер конечного результата