<?php

// Функции
    require_once "functions/application.php";
    require_once "Classes/AppConfig.php";

    $resultApplication = app
    (
        include __DIR__ . '/functions/request.handlers.php',
        //Массив путей запросов ведущие к функциям реализующие этот запрос
        $_SERVER['REQUEST_URI'],
        //Полный путь запроса
        'loggerInFile', //Название функции логирования
        static function () {
            return AppConfig::createFromArray(include __DIR__.'/dev.env.config.php');
        } // Конфиг приложения
    );

    render($resultApplication['httpCode'], $resultApplication['result']); // Рэндер конечного результата