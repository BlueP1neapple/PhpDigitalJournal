<?php

    use JoJoBizzareCoders\DigitalJournal\Infrastructure\AppConfig;
    use JoJoBizzareCoders\DigitalJournal\Infrastructure\App;
    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Autoloader;
    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerRequestFactory;
    use JoJoBizzareCoders\DigitalJournal\Infrastructure\View\DefaultRender;


// Функции
    require_once __DIR__ . "/../src/Infrastructure/Autoloader.php";

    spl_autoload_register(
        new Autoloader([
            'JoJoBizzareCoders\\DigitalJournal\\' => __DIR__ . '/../src/',
            'JoJoBizzareCoders\\DigitalJournalTest\\' => __DIR__ . '/../tests',
        ])
    );

    $httpResponse = (new App(
        include __DIR__ . '/../config/request.handlers.php',
        '\JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\Factory::create',
        static function () {
            return AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');
        },
        static function(){
            return new DefaultRender();
        }
    ))->dispatch(ServerRequestFactory::createFromGlobals($_SERVER));