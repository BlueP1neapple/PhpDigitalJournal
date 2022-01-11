<?php

use JoJoBizzareCoders\DigitalJournal\ConsoleCommand\GetLesson;
use JoJoBizzareCoders\DigitalJournal\ConsoleCommand\GetReport;
use JoJoBizzareCoders\DigitalJournal\Controller\GetLessonController;
use JoJoBizzareCoders\DigitalJournal\Controller\GetReportCollectionController;
use JoJoBizzareCoders\DigitalJournal\Controller\GetLessonCollectionController;
use JoJoBizzareCoders\DigitalJournal\Controller\GetReportController;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\AppConfig;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Console\Output\EchoOutPut;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Console\Output\OutputInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DI\ContainerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\FileLogger\Logger;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\LoggerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Router\ChainRouters;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Router\ControllerFactory;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Router\DefaultRouter;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Router\RegExpRouter;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Router\RouterInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Router\UniversalRouter;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\View\DefaultRender;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\View\RenderInterface;

return [
    'instances' => [
        'handlers' => require __DIR__ . '/../request.handlers.php',
        'appConfig' => include __DIR__ . '/config.php',
        'controllerNs' => 'JoJoBizzareCoders\\DigitalJournal\\Controller',
        'regExpHandlers'       => require __DIR__ . '/../regExp.handlers.php'
    ],
    'services' => [
        OutputInterface::class =>[
            'class' => EchoOutPut::class,
            'args' => []
        ],
        GetReport::class =>[
            'args' =>[
                'output' =>OutputInterface::class
            ]
        ],
        GetLesson::class => [
            'args' => [
                'output' => OutputInterface::class
            ]
        ],

        GetReportCollectionController::class => [
            'args' => [
                'logger' => LoggerInterface::class,
                'pathToItems' => 'pathToItems',
                'pathToTeachers' => 'pathToTeachers',
                'pathToClasses' => 'pathToClasses',
                'pathToStudents' => 'pathToStudents',
                'pathToParents' => 'pathToParents',
                'pathToLesson' => 'pathToLesson',
                'pathToAssessmentReport' => 'pathToAssessmentReport'
            ]
        ],
        GetReportController::class => [
            'args' => [
                'logger' => LoggerInterface::class,
                'pathToItems' => 'pathToItems',
                'pathToTeachers' => 'pathToTeachers',
                'pathToClasses' => 'pathToClasses',
                'pathToStudents' => 'pathToStudents',
                'pathToParents' => 'pathToParents',
                'pathToLesson' => 'pathToLesson',
                'pathToAssessmentReport' => 'pathToAssessmentReport'
            ]

        ],
        GetLessonCollectionController::class => [
            'args' => [
                'logger' => LoggerInterface::class,
                'pathToItems' => 'pathToItems',
                'pathToTeachers' => 'pathToTeachers',
                'pathToClasses' => 'pathToClasses',
                'pathToLesson' => 'pathToLesson'
            ]
        ],
        GetLessonController::class =>[
            'args' => [
                'logger' => LoggerInterface::class,
                'pathToItems' => 'pathToItems',
                'pathToTeachers' => 'pathToTeachers',
                'pathToClasses' => 'pathToClasses',
                'pathToLesson' => 'pathToLesson'
            ]
        ],
        LoggerInterface::class => [
            'class' => Logger::class,
            'args' => [
                'pathToFile' => 'pathToLogFile'
            ]
        ],
        RenderInterface::class => [
            'class' => DefaultRender::class
        ],
        RouterInterface::class =>[
            'class' => ChainRouters::class,
            'args' =>[
                RegExpRouter::class,
                DefaultRouter::class,
                UniversalRouter::class
            ]
        ],
        UniversalRouter::class =>[
            'args' =>[
                'controllerFactory' => ControllerFactory::class,
                'controllerNs' => 'controllerNs'
            ]
        ],
        DefaultRouter::class =>[
            'args' =>[
                'handlers' => 'handlers',
                'controllerFactory' => ControllerFactory::class,
            ]
        ],
        ControllerFactory::class => [
            'args' =>[
                'diContainer' => ContainerInterface::class
            ]
        ],
        RegExpRouter::class =>[
            'args' =>[
                'handlers' => 'regExpHandlers',
                'controllerFactory' => ControllerFactory::class,
            ]
        ]



    ],
    'factories' =>[
        ContainerInterface::class => static function(ContainerInterface $c):ContainerInterface{
            return $c;
        },
        'pathToLogFile' => static function(ContainerInterface $c): string {
            /** @var AppConfig $appConfig */
            $appConfig =$c->get(AppConfig::class);
            return $appConfig->getPathToLogFile();
        },
        'pathToLesson' => static function(ContainerInterface $c): string {
            /** @var AppConfig $appConfig */
            $appConfig =$c->get(AppConfig::class);
            return $appConfig->getPathToLesson();
        },
        'pathToAssessmentReport' => static function(ContainerInterface $c): string {
            /** @var AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return $appConfig->getPathToAssessmentReport();
        },
        'pathToItems' => static function(ContainerInterface $c): string {
            /** @var AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return $appConfig->getPathToItems();
        },
        'pathToTeachers' => static function(ContainerInterface $c): string {
            /** @var AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return $appConfig->getPathToTeachers();
        },
        'pathToClasses' => static function(ContainerInterface $c): string {
            /** @var AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return $appConfig->getPathToClasses();
        },
        'pathToStudents' => static function(ContainerInterface $c): string {
            /** @var AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return $appConfig->getPathToStudents();
        },
        'pathToParents' => static function(ContainerInterface $c): string {
            /** @var AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return $appConfig->getPathToParents();
        },
        AppConfig::class => static function(ContainerInterface $c): AppConfig {
            $appConfig = $c->get('appConfig');
            return AppConfig::createFromArray($appConfig);
        }
    ]
];