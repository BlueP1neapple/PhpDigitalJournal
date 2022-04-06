<?php

use Doctrine\ORM\EntityManagerInterface ;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use JoJoBizzareCoders\DigitalJournal\Config\ContainerExtensions;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DI\SymfonyDiContainerInit;

require_once __DIR__ . '/../vendor/autoload.php';

$container = (new SymfonyDiContainerInit(
    new SymfonyDiContainerInit\ContainerParams(
        __DIR__ . '/../config/dev/di.xml',
        [
            'kernel.project_dir' => __DIR__ . '/../'
        ],
        ContainerExtensions::httpAppContainerExtensions()
    ),
    new SymfonyDiContainerInit\CacheParams(
     // 'DEV' !== getenv('ENV_TYPE'),
        false,
        __DIR__ . '/../var/cache/di-symfony/EfTechBookLibraryCachedContainer.php',
    )
))();

$entityManager = $container->get(EntityManagerInterface::class);

return ConsoleRunner::createHelperSet($entityManager);
