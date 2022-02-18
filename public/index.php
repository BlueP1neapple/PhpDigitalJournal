<?php

require_once __DIR__ . '/../vendor/autoload.php';

use JoJoBizzareCoders\DigitalJournal\Config\ContainerExtensions;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DI\SymfonyDiContainerInit;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\HttpApplication\App;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\HttpApplication\AppConfiguration;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Http\Message\ServerRequestInterface;


(new App(
    (new AppConfiguration())->setContainerFactory(
        SymfonyDiContainerInit::create(
            __DIR__ . '/../config/dev/di.xml',
            ['kernel.project_dir' => __DIR__ . '/../'],
            ContainerExtensions::createHttpAppContainerExtensions(),
            getenv('ENV_TYPE') !== 'DEV',
            __DIR__ . '/../var/cache/di-symfony/DigitalJournalCachedContainer.php'
        )
    )
))->dispatch(
    (static function (): ServerRequestInterface {
        $psr17Factory = new Psr17Factory();
        $creator = new ServerRequestCreator(
            $psr17Factory,
            $psr17Factory,
            $psr17Factory,
            $psr17Factory
        );
        return $creator->fromGlobals();
    })()
);

