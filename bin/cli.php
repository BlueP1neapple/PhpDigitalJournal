#!/usr/bin/env php
<?php
require_once __DIR__ . '/../vendor/autoload.php';

use JoJoBizzareCoders\DigitalJournal\Config\ContainerExtensions;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Console\AppConsole;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Console\Output\OutputInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DI\ContainerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DI\SymfonyDiContainerInit;


(new AppConsole(
    require __DIR__ . '/../config/console.handlers.php',
    static function (ContainerInterface $di): OutputInterface {
        return $di->get(OutputInterface::class);
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
            //'DEV' !== getenv('ENV_TYPE'),
                false,
            __DIR__ . '/../var/cache/di-symfony/DigitalJournalCachedContainer.php',
        )
    ),
))->dispatch();

