<?php

    use JoJoBizzareCoders\DigitalJournal\Infrastructure\AppConfig;
    use JoJoBizzareCoders\DigitalJournal\Infrastructure\App;
    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Autoloader;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DI\Container;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerRequestFactory;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\LoggerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Router\RouterInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\View\RenderInterface;


// Функции
    require_once __DIR__ . "/../src/Infrastructure/Autoloader.php";

    spl_autoload_register(
        new Autoloader([
            'JoJoBizzareCoders\\DigitalJournal\\' => __DIR__ . '/../src/',
            'JoJoBizzareCoders\\DigitalJournalTest\\' => __DIR__ . '/../tests',
        ])
    );

$httpResponse = (new App(
    static function (Container $di): RouterInterface {
        return $di->get(RouterInterface::class);
    },
    static function (Container $di): LoggerInterface {
        return $di->get(LoggerInterface::class);
    },
    static function (Container $di): AppConfig {
        return $di->get(AppConfig::class);
    },
    static function (Container $di): RenderInterface {
        return $di->get(RenderInterface::class);
    },
    static function (): Container {
        return Container::createFromArray(require __DIR__ . '/../config/dev/di.php');}

))->dispatch(ServerRequestFactory::createFromGlobal($_SERVER));