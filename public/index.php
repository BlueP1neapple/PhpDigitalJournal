<?php

require_once __DIR__ . '/../vendor/autoload.php';

use JoJoBizzareCoders\DigitalJournal\Config\ContainerExtensions;
use JoJoBizzareCoders\DigitalJournal\Config\AppConfig;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DI\ContainerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DI\SymfonyDiContainerInit;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\HttpApplication\App;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\LoggerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Router\RouterInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\View\RenderInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Http\Message\ServerRequestInterface;


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
        new SymfonyDiContainerInit\ContainerParams(
            __DIR__ . '/../config/dev/di.xml',
            [
                'kernel.project_dir' => __DIR__ . '/../'
            ],
            ContainerExtensions::httpAppContainerExtensions()
        ),
        new SymfonyDiContainerInit\CacheParams(
            'DEV' !== getenv('ENV_TYPE'),
            //    false,
            __DIR__ . '/../var/cache/di-symfony/DigitalJournalCachedContainer.php',
        )
    ),
))->dispatch(
    (static function (): ServerRequestInterface {
        $psr17Factory = new Psr17Factory();
        $create = new ServerRequestCreator($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
        return $create->fromGlobals();
    })()
);
