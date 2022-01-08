#!/usr/bin/env php
<?php


require_once __DIR__ . '/../src/Infrastructure/Autoloader.php';


use JoJoBizzareCoders\DigitalJournal\Infrastructure\Autoloader;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Console;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Console\Output\OutputInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DI\Container;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DI\ContainerInterface;

spl_autoload_register(
    new Autoloader([
        'EfTech\\BookLibrary\\' => __DIR__ . '/../src/',
        'EfTech\\BookLibraryTest\\' => __DIR__ . '/../tests/',
    ])
);

(new AppConsole(
    require __DIR__ . '/../config/console.handlers.php',
    static function(ContainerInterface $di): OutputInterface{ return $di->get(OutputInterface::class);},
    static function (): ContainerInterface {
        return Container::createFromArray(require __DIR__ . '/../config/dev/di.php');
    }
))->dispatch();
