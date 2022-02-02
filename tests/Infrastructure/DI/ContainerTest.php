<?php

namespace JoJoBizzareCoders\DigitalJournalTest\Infrastructure\DI;

use JoJoBizzareCoders\DigitalJournal\Config\AppConfig;
use JoJoBizzareCoders\DigitalJournal\Controller\GetAssessmentReportCollectionController;
use JoJoBizzareCoders\DigitalJournal\Controller\GetLessonCollectionController;
use JoJoBizzareCoders\DigitalJournal\Entity\AssessmentReportRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\LessonRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DataLoader\DataLoaderInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DataLoader\JsonDataLoader;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DI\Container;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DI\ContainerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\HttpApplication\AppConfigInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\Adapter\NullAdapter;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\AdapterInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\Logger;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\LoggerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Router\ChainRouters;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Router\ControllerFactory;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Router\DefaultRouter;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Router\RouterInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\View\DefaultRender;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\View\RenderInterface;
use JoJoBizzareCoders\DigitalJournal\Repository\AssessmentReportJsonRepository;
use JoJoBizzareCoders\DigitalJournal\Repository\LessonJsonRepository;
use JoJoBizzareCoders\DigitalJournal\Service\SearchAssessmentReportService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService;
use PHPUnit\Framework\TestCase;

/**
 * Тест проверяющий работу контейнера
 */
class ContainerTest extends TestCase
{
    /**
     * Тест возврата сервиса
     *
     * @return void
     */
    public function testGetService(): void
    {
        //Arrange
        $diConfig = [
            'instances' => [
                'appConfig' => require __DIR__ . '/../../../config/dev/config.php'
            ],
            'services' => [
                DataLoaderInterface::class => [
                    'class' => JsonDataLoader::class
                ],
                GetAssessmentReportCollectionController::class => [
                    'args' => [
                        'Logger' => LoggerInterface::class,
                        'SearchAssessmentReportService' => SearchAssessmentReportService::class,
                    ]
                ],
                SearchAssessmentReportService::class => [
                    'args' => [
                        'Logger' => LoggerInterface::class,
                        'assessmentReportJsonRepository' => AssessmentReportRepositoryInterface::class
                    ]
                ],
                AssessmentReportRepositoryInterface::class => [
                    'class' => AssessmentReportJsonRepository::class,
                    'args' => [
                        'pathToItems' => 'pathToItems',
                        'pathToTeachers' => 'pathToTeachers',
                        'pathToClasses' => 'pathToClasses',
                        'pathToStudents' => 'pathToStudents',
                        'pathToParents' => 'pathToParents',
                        'pathToLesson' => 'pathToLesson',
                        'pathToAssessmentReport' => 'pathToAssessmentReport',
                        'dataLoader' => DataLoaderInterface::class
                    ],
                ],
                GetLessonCollectionController::class => [
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
                RenderInterface::class => [
                    'class' => DefaultRender::class
                ],
                RouterInterface::class => [
                    'class' => ChainRouters::class,
                    'args' => [
                        DefaultRouter::class
                    ]
                ],
                DefaultRouter::class => [
                    'args' => [
                        'handlers' => 'handlers',
                        'controllerFactory' => ControllerFactory::class,
                    ]
                ],
                ControllerFactory::class => [
                    'args' => [
                        'diContainer' => ContainerInterface::class
                    ]
                ],
                SearchLessonService::class => [
                    'args' => [
                        'logger' => LoggerInterface::class,
                        'lessonJsonRepository' => LessonRepositoryInterface::class
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
                AdapterInterface::class => [
                    'class' => NullAdapter::class,
                    'args' => [
                        'pathToLogFile' => 'pathToLogFile'
                    ]
                ]
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
                AppConfig::class => static function (ContainerInterface $c): AppConfigInterface {
                    $appConfig = $c->get('appConfig');
                    return AppConfig::createFromArray($appConfig);
                }
            ]
        ];
        $di = Container::createFromArray($diConfig);

        //Act
        $controller = $di->get(GetLessonCollectionController::class);

        //Assert
        $this->assertInstanceOf(
            GetLessonCollectionController::class,
            $controller,
            'Контейнер отработал неккоректно'
        );
    }
}
