<?php
use JoJoBizzareCoders\DigitalJournal\Infrastructure\AppConfig;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Autoloader;

use function JoJoBizzareCoders\DigitalJournal\Infrastructure\app;
use function JoJoBizzareCoders\DigitalJournal\Infrastructure\render;

// Функции
    require_once __DIR__."/../src/Infrastructure/application.php";
    require_once __DIR__."/../src/Infrastructure/Autoloader.php";

    spl_autoload_register(
        new Autoloader([
            'JoJoBizzareCoders\\DigitalJournal\\'  => __DIR__ . '/../src/',
            'JoJoBizzareCoders\\DigitalJournalTest\\' => __DIR__ . '/../tests',
        ]));

    $resultApplication = app
    (
        include __DIR__ . '/../config/request.handlers.php',
        //Массив путей запросов ведущие к функциям реализующие этот запрос
        $_SERVER['REQUEST_URI'],
        //Полный путь запроса
        '\JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\Factory::create', //Название функции логирования
        static function () {
            return AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');
        } // Конфиг приложения
    );

    render($resultApplication['httpCode'], $resultApplication['result']); // Рэндер конечного результата