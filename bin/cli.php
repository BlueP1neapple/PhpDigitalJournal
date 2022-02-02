#!/usr/bin/env php
<?php
require_once __DIR__ . '/../vendor/autoload.php';

use JoJoBizzareCoders\DigitalJournal\Infrastructure\Console\AppConsole;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Console\Output\OutputInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DI\Container;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DI\ContainerInterface;



(new AppConsole(
    require __DIR__ . '/../config/console.handlers.php',
    static function (ContainerInterface $di): OutputInterface {
        return $di->get(OutputInterface::class);
    },
    static function (): ContainerInterface {
        return Container::createFromArray(require __DIR__ . '/../config/dev/di.php');
    }
))->dispatch();

