<?php
use JoJoBizzareCoders\DigitalJournal\Infrastructure\AppConfig;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\App;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Autoloader;

use function JoJoBizzareCoders\DigitalJournal\Infrastructure\render;

// Функции
    require_once __DIR__."/../src/Infrastructure/application.php";
    require_once __DIR__."/../src/Infrastructure/Autoloader.php";

    spl_autoload_register(
        new Autoloader([
            'JoJoBizzareCoders\\DigitalJournal\\'  => __DIR__ . '/../src/',
            'JoJoBizzareCoders\\DigitalJournalTest\\' => __DIR__ . '/../tests',
        ]));

$resultApplication = (new App(
    include __DIR__ . '/../config/request.handlers.php',
    '\JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\Factory::create',
    static function () {
        return AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');
    }
))->dispatch($_SERVER['REQUEST_URI']);


    render($resultApplication['httpCode'], $resultApplication['result']); // Рэндер конечного результата