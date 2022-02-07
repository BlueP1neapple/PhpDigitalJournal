<?php
require_once __DIR__ . '/../vendor/autoload.php';

use JoJoBizzareCoders\DigitalJournal\Config\AppConfig;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DI\ContainerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DI\SymfonyDiContainerInit;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerRequestFactory;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\HttpApplication\App;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\LoggerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Router\RouterInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\View\RenderInterface;


$httpResponse = (new App(
    static function (ContainerInterface $di): RouterInterface {
        return $di->get(RouterInterface::class);
    },
    static function (ContainerInterface $di): LoggerInterface {
        return $di->get(LoggerInterface::class);
    },
    static function (ContainerInterface $di): AppConfig {
        return $di->get(AppConfig::class);
    },
    static function (ContainerInterface $di): RenderInterface {
        return $di->get(RenderInterface::class);
    },
    new SymfonyDiContainerInit(
        __DIR__ . '/../config/dev/di.xml',
        [
            'kernel.project_dir' => __DIR__ . '/../'
        ]
    )
))->dispatch(ServerRequestFactory::createFromGlobal($_SERVER, file_get_contents('php://input')));
