<?php

use JoJoBizzareCoders\DigitalJournal\AppService\UserDataStorage\UserDataProvider;
use JoJoBizzareCoders\DigitalJournal\AppService\UserDataStorage\UserDataStorage;
use JoJoBizzareCoders\DigitalJournal\Config\AppConfig;
use JoJoBizzareCoders\DigitalJournal\ConsoleCommand\FindAssessmentReport;
use JoJoBizzareCoders\DigitalJournal\ConsoleCommand\FindLesson;
use JoJoBizzareCoders\DigitalJournal\ConsoleCommand\HashStr;
use JoJoBizzareCoders\DigitalJournal\Controller\CreateRegisterAssessmentReportController;
use JoJoBizzareCoders\DigitalJournal\Controller\CreateRegisterItemController;
use JoJoBizzareCoders\DigitalJournal\Controller\CreateRegisterLessonController;
use JoJoBizzareCoders\DigitalJournal\Controller\GetAssessmentReportCollectionController;
use JoJoBizzareCoders\DigitalJournal\Controller\GetAssessmentReportController;
use JoJoBizzareCoders\DigitalJournal\Controller\GetLessonCollectionController;
use JoJoBizzareCoders\DigitalJournal\Controller\GetLessonController;
use JoJoBizzareCoders\DigitalJournal\Controller\JournalAdministrationController;
use JoJoBizzareCoders\DigitalJournal\Controller\LoginController;
use JoJoBizzareCoders\DigitalJournal\Entity\AssessmentReportRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\ClassRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\ItemRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\LessonRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\ParentRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\StudentRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\TeacherRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Auth\HttpAuthProvider;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Auth\UserDataProviderInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Auth\UserDataStorageInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Console\Output\EchoOutput;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Console\Output\OutputInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DataLoader\DataLoaderInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DataLoader\JsonDataLoader;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DI\ContainerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\Adapter\FileAdapter;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\AdapterInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\Logger;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\LoggerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Router\ChainRouters;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Router\ControllerFactory;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Router\DefaultRouter;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Router\RegExpRouter;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Router\RouterInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Router\UniversalRouter;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Session\SessionInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Session\SessionNative;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Uri\Uri;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\View\DefaultRender;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\View\RenderInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\ViewTemplate\PhtmlTemplate;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\ViewTemplate\ViewTemplateInterface;
use JoJoBizzareCoders\DigitalJournal\Repository\AssessmentReportJsonRepository;
use JoJoBizzareCoders\DigitalJournal\Repository\ClassJsonFileRepository;
use JoJoBizzareCoders\DigitalJournal\Repository\ItemJsonFileRepository;
use JoJoBizzareCoders\DigitalJournal\Repository\LessonJsonRepository;
use JoJoBizzareCoders\DigitalJournal\Repository\ParentJsonRepository;
use JoJoBizzareCoders\DigitalJournal\Repository\StudentJsonRepository;
use JoJoBizzareCoders\DigitalJournal\Repository\TeacherJsonFileRepository;
use JoJoBizzareCoders\DigitalJournal\Service\NewItemService;
use JoJoBizzareCoders\DigitalJournal\Service\NewLessonService;
use JoJoBizzareCoders\DigitalJournal\Service\NewReportService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchAssessmentReportService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchClassService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchItemService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchStudentService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchTeacherService;

return [
    'instances' => [
        'handlers' => require __DIR__ . '/../request.handlers.php',
        'appConfig' => include __DIR__ . '/config.php',
        'controllerNs' => 'JoJoBizzareCoders\\DigitalJournal\\Controller',
        'regExpHandlers' => require __DIR__ . '/../regExp.handlers.php'
    ],
    'services' => [
        HttpAuthProvider::class => [
            'args' =>[
                'userDataStorage'=>UserDataStorageInterface::class,
                'session' => SessionInterface::class,
                'loginUri' => 'loginUri'

            ]
        ],
        UserDataStorageInterface::class => [
            'class' => UserDataStorage::class,
            'args' => [
                ParentRepositoryInterface::class,
                TeacherRepositoryInterface::class,
                StudentRepositoryInterface::class
            ]
        ],

        LoginController::class =>[
            'args' => [
                'template' => ViewTemplateInterface::class,
                'httpAuthProvider' => HttpAuthProvider::class
            ]
        ],
        ViewTemplateInterface::class =>[
            'class' => PhtmlTemplate::class
        ],
        JournalAdministrationController::class => [
            'args' => [
                'logger' => LoggerInterface::class,
                'searchAssessmentReportService' => SearchAssessmentReportService::class,
                'viewTemplate' => ViewTemplateInterface::class,
                'searchLessonService' => SearchLessonService::class,
                'newLessonService' => NewLessonService::class,
                'searchItemService' => SearchItemService::class,
                'searchTeacherService' => SearchTeacherService::class,
                'searchClassService' => SearchClassService::class,
                'newReportService' => NewReportService::class,
                'searchStudentService' => SearchStudentService::class,
                'httpAuthProvider' => HttpAuthProvider::class,
                'newItemService' => NewItemService::class,
            ]
        ],
        CreateRegisterLessonController::class =>[
            'args' =>[
                'newLessonService' => NewLessonService::class
            ]
        ],
        CreateRegisterAssessmentReportController::class =>[
            'args' => [
                'newReportService' => NewReportService::class
            ]
        ],
        CreateRegisterItemController::class => [
            'args' => [
                'newItemService' => NewItemService::class
            ]
        ],
        NewItemService::class =>[
            'args' => [
                'itemRepositoryInterface' => ItemRepositoryInterface::class
            ]

        ],
        NewReportService::class => [
            'args'=>[
                'assessmentReportRepository'=>AssessmentReportRepositoryInterface::class,
                'lessonRepository'=>LessonRepositoryInterface::class,
                'studentRepository'=>StudentRepositoryInterface::class
            ]
        ],
        NewLessonService::class => [
            'args' => [
                'lessonJsonRepository' => LessonRepositoryInterface::class,
                'teacherJsonFileRepository' => TeacherJsonFileRepository::class,
                'itemJsonFileRepository' => ItemJsonFileRepository::class,
                'classJsonFileRepository' => ClassJsonFileRepository::class
            ]
        ],
        TeacherJsonFileRepository::class =>[
            'args' => [
                'pathToTeachers' => 'pathToTeachers',
                'pathToItems' => 'pathToItems',
                'dataLoader' => DataLoaderInterface::class
            ]
        ],
        ItemJsonFileRepository::class =>[
            'args' => [
                'pathToItems' => 'pathToItems',
                'dataLoader' => DataLoaderInterface::class
            ]
        ],
        ClassJsonFileRepository::class =>[
            'args' => [
                'pathToClasses' => 'pathToClasses',
                'dataLoader' => DataLoaderInterface::class
            ]
        ],
        StudentRepositoryInterface::class=>[
            'class'=>StudentJsonRepository::class,
            'args'=>[
                'pathToStudents'=>'pathToStudents',
                'pathToClasses'=>'pathToClasses',
                'pathToParents'=>'pathToParents',
                'dataLoader'=>DataLoaderInterface::class
            ]
        ],
        ParentRepositoryInterface::class =>[
            'class'=> ParentJsonRepository::class,
            'args' =>[
                'pathToParents'=>'pathToParents',
                'dataLoader'=>DataLoaderInterface::class
            ]
        ],
        GetAssessmentReportCollectionController::class => [
            'args' => [
                'logger' => LoggerInterface::class,
                'searchAssessmentReportService' => SearchAssessmentReportService::class
            ]
        ],
        GetAssessmentReportController::class => [
            'args' => [
                'logger' => LoggerInterface::class,
                'searchAssessmentReportService' => SearchAssessmentReportService::class
            ]
        ],
        GetLessonCollectionController::class => [
            'args' => [
                'logger' => LoggerInterface::class,
                'searchLessonService' => SearchLessonService::class
            ]
        ],
        GetLessonController::class => [
            'args' => [
                'logger' => LoggerInterface::class,
                'searchLessonService' => SearchLessonService::class
            ]
        ],
        LoggerInterface::class => [
            'class' => Logger::class,
            'args' => [
                'adapter' => AdapterInterface::class
            ]
        ],
        AdapterInterface::class => [
            'class' => FileAdapter::class,
            'args' => [
                'pathToLogFile' => 'pathToLogFile'
            ]
        ],
        RenderInterface::class => [
            'class' => DefaultRender::class
        ],
        RouterInterface::class => [
            'class' => ChainRouters::class,
            'args' => [
                RegExpRouter::class,
                DefaultRouter::class,
                UniversalRouter::class
            ]
        ],
        RegExpRouter::class => [
            'args' => [
                'handlers' => 'regExpHandlers',
                'controllerFactory' => ControllerFactory::class,
            ]
        ],
        DefaultRouter::class => [
            'args' => [
                'handlers' => 'handlers',
                'controllerFactory' => ControllerFactory::class,
            ]
        ],
        UniversalRouter::class => [
            'args' => [
                'controllerNs' => 'controllerNs',
                'controllerFactory' => ControllerFactory::class,
            ]
        ],
        ControllerFactory::class => [
            'args' => [
                'diContainer' => ContainerInterface::class
            ]
        ],
        OutputInterface::class => [
            'class' => EchoOutput::class,
        ],
        FindLesson::class => [
            'args' => [
                'output' => OutputInterface::class,
                'searchLessonService' => SearchLessonService::class
            ]
        ],
        FindAssessmentReport::class => [
            'args' => [
                'output' => OutputInterface::class,
                'searchAssessmentReportService' => SearchAssessmentReportService::class
            ]
        ],
        DataLoaderInterface::class => [
            'class' => JsonDataLoader::class
        ],
        SearchStudentService::class =>[
            'args' => [
                'studentRepository' => StudentRepositoryInterface::class
            ]
        ],
        SearchLessonService::class => [
            'args' => [
                'logger' => LoggerInterface::class,
                'lessonRepository' => LessonRepositoryInterface::class
            ]
        ],
        SearchAssessmentReportService::class => [
            'args' => [
                'logger' => LoggerInterface::class,
                'assessmentReportRepository'=>AssessmentReportRepositoryInterface::class
            ]
        ],
        SearchItemService::class =>[
            'args' => [
                'logger' => LoggerInterface::class,
                'itemRepository' => ItemRepositoryInterface::class
            ]
        ],
        SearchTeacherService::class => [
            'args' => [
                'logger' => LoggerInterface::class,
                'teacherRepository' => TeacherRepositoryInterface::class
            ]
        ],
        SearchClassService::class =>[
            'args' =>[
                'logger' => LoggerInterface::class,
                'classRepository' => ClassRepositoryInterface::class
            ]
        ],
        HashStr::class =>[
            'args' => [
                OutputInterface::class
            ]
        ],
        ClassRepositoryInterface::class =>[
            'class' => ClassJsonFileRepository::class,
            'args' =>[
                'pathToClasses' => 'pathToClasses',
                'dataLoader' => DataLoaderInterface::class,
            ]
        ],
        TeacherRepositoryInterface::class => [
            'class' => TeacherJsonFileRepository::class,
            'args' => [
                'pathToTeachers' => 'pathToTeachers',
                'pathToItems' => 'pathToItems',
                'dataLoader' => DataLoaderInterface::class,

            ]
        ],
        ItemRepositoryInterface::class =>[
            'class' => ItemJsonFileRepository::class,
            'args' => [
                'pathToItems' => 'pathToItems',
                'dataLoader' => DataLoaderInterface::class
            ]
        ],
        LessonRepositoryInterface::class => [
            'class' => LessonJsonRepository::class,
            'args' => [
                'pathToItems' => 'pathToItems',
                'pathToTeachers' => 'pathToTeachers',
                'pathToClasses' => 'pathToClasses',
                'pathToLesson' => 'pathToLesson',
                'dataLoader' => DataLoaderInterface::class
            ]
        ],
        AssessmentReportRepositoryInterface::class => [
            'class' => AssessmentReportJsonRepository::class,
            'args'=>[
                'pathToItems' => 'pathToItems',
                'pathToTeachers' => 'pathToTeachers',
                'pathToClasses' => 'pathToClasses',
                'pathToStudents' => 'pathToStudents',
                'pathToParents' => 'pathToParents',
                'pathToLesson' => 'pathToLesson',
                'pathToAssessmentReport' => 'pathToAssessmentReport',
                'dataLoader' => DataLoaderInterface::class
            ]
        ],
        UserDataProviderInterface::class => [
            'class'=> UserDataProvider::class,
        ],

    ],
    'factories' => [
        ContainerInterface::class => static function (ContainerInterface $c): ContainerInterface {
            return $c;
        },
        'pathToLogFile' => static function (ContainerInterface $c): string {
            /** @var AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return $appConfig->getPathToLogFile();
        },
        'pathToLesson' => static function (ContainerInterface $c): string {
            /** @var AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return $appConfig->getPathToLesson();
        },
        'pathToAssessmentReport' => static function (ContainerInterface $c): string {
            /** @var AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return $appConfig->getPathToAssessmentReport();
        },
        'pathToItems' => static function (ContainerInterface $c): string {
            /** @var AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return $appConfig->getPathToItems();
        },
        'pathToTeachers' => static function (ContainerInterface $c): string {
            /** @var AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return $appConfig->getPathToTeachers();
        },
        'pathToClasses' => static function (ContainerInterface $c): string {
            /** @var AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return $appConfig->getPathToClasses();
        },
        'pathToStudents' => static function (ContainerInterface $c): string {
            /** @var AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return $appConfig->getPathToStudents();
        },
        'pathToParents' => static function (ContainerInterface $c): string {
            /** @var AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return $appConfig->getPathToParents();
        },
        SessionInterface::class => static function (ContainerInterface $c) {
            return SessionNative::create();
        },

        'loginUri' => static function (ContainerInterface $c):Uri {
            /** @var AppConfig $appConfig */
            $appConfig = $c->get(AppConfig::class);
            return Uri::createFromString($appConfig->getLoginUri());
        },
        AppConfig::class => static function (ContainerInterface $c): AppConfig {
            $appConfig = $c->get('appConfig');
            return AppConfig::createFromArray($appConfig);
        },

    ]
];