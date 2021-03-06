<?php

namespace JoJoBizzareCoders\DigitalJournalTest;

use Exception;
use JoJoBizzareCoders\DigitalJournal\Config\AppConfig;
use JoJoBizzareCoders\DigitalJournal\Config\ContainerExtensions;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DI\ContainerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DI\SymfonyDiContainerInit;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DI\SymfonyDiContainerInit\ContainerParams;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Exception\ExceptionHandler\DefaultExceptionHandler;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Exception\ExceptionHandler\ExceptionHandlerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\HttpApplication\App;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\HttpApplication\AppConfiguration;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\View\NullRender;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\View\RenderInterface;
use JsonException;
use Nyholm\Psr7\ServerRequest;
use Nyholm\Psr7\Uri;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;


class AppTest extends TestCase
{
    private static function createDiContainer(): ContainerBuilder
    {
        $containerBuilder = SymfonyDiContainerInit::createContainerBuilder(
            new ContainerParams(
                __DIR__ . '/../config/dev/di.xml',
                [
                    'kernel.project_dir' => __DIR__ . '/../'
                ],
                ContainerExtensions::httpAppContainerExtensions()
            )
        );

        $containerBuilder->removeAlias(ExceptionHandlerInterface::class);
        $containerBuilder->setDefinition(DefaultExceptionHandler::class, (new Definition())->setAutowired(true));
        $containerBuilder->setAlias(ExceptionHandlerInterface::class, DefaultExceptionHandler::class)->setPublic(true);

        $containerBuilder->removeAlias(LoggerInterface::class);
        $containerBuilder->setDefinition(NullLogger::class, new Definition());
        $containerBuilder->setAlias(LoggerInterface::class, NullLogger::class)->setPublic(true);
        $containerBuilder->getDefinition(RenderInterface::class)
            ->setClass(NullRender::class)
            ->setArguments([]);
        return $containerBuilder;
    }

    /**
     * ?????????? ???????????????????????? ?? ???????????? ?????? ?????????????????????? ???????????????????????? ???????????? ??????????????
     *
     * @param array $config
     * @return string
     */
    public static function bugFactory(array $config): string
    {
        return 'Oops';
    }

    /**
     * ?????????????????? ???????????? ?????? ???????????????????????? ????????????????????
     *
     * @return \array[][]
     * @throws Exception
     */
    public static function dataProvider(): array
    {
        return [
            '???????????????????????? ?????????????????????? ???????????????? ???????????????????? ???? ???????????????? ????????????????' => [
                'in' => [
                    'uri' => '/lesson?item_name=????????????????????',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'item' => [
                                'id' => 1,
                                'name' => '????????????????????',
                                'description' => '????????????????????'
                            ],
                            'date' => '2011.11.10 8:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => '????????????????',
                                    'name' => '??????????????',
                                    'patronymic' => '??????????????????'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => '????. ??????????',
                                    'home' => '??. 54',
                                    'apartment' => '????. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 3,
                                'number' => 6,
                                'letter' => '??'
                            ]
                        ],
                        [
                            'id' => 2,
                            'item' => [
                                'id' => 1,
                                'name' => '????????????????????',
                                'description' => '????????????????????'
                            ],
                            'date' => '2011.11.10 10:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => '????????????????',
                                    'name' => '??????????????',
                                    'patronymic' => '??????????????????'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => '????. ??????????',
                                    'home' => '??. 54',
                                    'apartment' => '????. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 1,
                                'number' => 4,
                                'letter' => '??'
                            ]
                        ],
                        [
                            'id' => 3,
                            'item' => [
                                'id' => 1,
                                'name' => '????????????????????',
                                'description' => '????????????????????'
                            ],
                            'date' => '2011.11.10 11:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => '????????????????',
                                    'name' => '??????????????',
                                    'patronymic' => '??????????????????'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => '????. ??????????',
                                    'home' => '??. 54',
                                    'apartment' => '????. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 2,
                                'number' => 3,
                                'letter' => '??'
                            ]
                        ]
                    ]
                ]
            ],
            '???????????????????????? ?????????????????????? ???????????????? ???????????????????? ???? ?????????????????????? ????????????????' => [
                'in' => [
                    'uri' => '/lesson?item_description=????????????????????',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'item' => [
                                'id' => 1,
                                'name' => '????????????????????',
                                'description' => '????????????????????'
                            ],
                            'date' => '2011.11.10 8:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => '????????????????',
                                    'name' => '??????????????',
                                    'patronymic' => '??????????????????'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => '????. ??????????',
                                    'home' => '??. 54',
                                    'apartment' => '????. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 3,
                                'number' => 6,
                                'letter' => '??'
                            ]
                        ],
                        [
                            'id' => 2,
                            'item' => [
                                'id' => 1,
                                'name' => '????????????????????',
                                'description' => '????????????????????'
                            ],
                            'date' => '2011.11.10 10:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => '????????????????',
                                    'name' => '??????????????',
                                    'patronymic' => '??????????????????'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => '????. ??????????',
                                    'home' => '??. 54',
                                    'apartment' => '????. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 1,
                                'number' => 4,
                                'letter' => '??'
                            ]
                        ],
                        [
                            'id' => 3,
                            'item' => [
                                'id' => 1,
                                'name' => '????????????????????',
                                'description' => '????????????????????'
                            ],
                            'date' => '2011.11.10 11:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => '????????????????',
                                    'name' => '??????????????',
                                    'patronymic' => '??????????????????'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => '????. ??????????',
                                    'home' => '??. 54',
                                    'apartment' => '????. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 2,
                                'number' => 3,
                                'letter' => '??'
                            ]
                        ]
                    ]
                ]
            ],
            '???????????????????????? ?????????????????????? ???????????????? ???????????????????? ???? ????????' => [
                'in' => [
                    'uri' => '/lesson?lesson_date=2011.11.10 8:30',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'item' => [
                                'id' => 1,
                                'name' => '????????????????????',
                                'description' => '????????????????????'
                            ],
                            'date' => '2011.11.10 8:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => '????????????????',
                                    'name' => '??????????????',
                                    'patronymic' => '??????????????????'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => '????. ??????????',
                                    'home' => '??. 54',
                                    'apartment' => '????. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 3,
                                'number' => 6,
                                'letter' => '??'
                            ]
                        ]
                    ]
                ]
            ],
            '???????????????????????? ?????????????????????? ???????????????? ???????????????????? ???? ?????????????? ??????????????????????????' => [
                'in' => [
                    'uri' => '/lesson?teacher_fio_surname=????????????????',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'item' => [
                                'id' => 1,
                                'name' => '????????????????????',
                                'description' => '????????????????????'
                            ],
                            'date' => '2011.11.10 8:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => '????????????????',
                                    'name' => '??????????????',
                                    'patronymic' => '??????????????????'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => '????. ??????????',
                                    'home' => '??. 54',
                                    'apartment' => '????. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 3,
                                'number' => 6,
                                'letter' => '??'
                            ]
                        ],
                        [
                            'id' => 2,
                            'item' => [
                                'id' => 1,
                                'name' => '????????????????????',
                                'description' => '????????????????????'
                            ],
                            'date' => '2011.11.10 10:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => '????????????????',
                                    'name' => '??????????????',
                                    'patronymic' => '??????????????????'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => '????. ??????????',
                                    'home' => '??. 54',
                                    'apartment' => '????. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 1,
                                'number' => 4,
                                'letter' => '??'
                            ]
                        ],
                        [
                            'id' => 3,
                            'item' => [
                                'id' => 1,
                                'name' => '????????????????????',
                                'description' => '????????????????????'
                            ],
                            'date' => '2011.11.10 11:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => '????????????????',
                                    'name' => '??????????????',
                                    'patronymic' => '??????????????????'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => '????. ??????????',
                                    'home' => '??. 54',
                                    'apartment' => '????. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 2,
                                'number' => 3,
                                'letter' => '??'
                            ]
                        ]
                    ]
                ]
            ],
            '???????????????????????? ?????????????????????? ???????????????? ???????????????????? ???? ?????????? ??????????????????????????' => [
                'in' => [
                    'uri' => '/lesson?teacher_fio_name=??????????????',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'item' => [
                                'id' => 1,
                                'name' => '????????????????????',
                                'description' => '????????????????????'
                            ],
                            'date' => '2011.11.10 8:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => '????????????????',
                                    'name' => '??????????????',
                                    'patronymic' => '??????????????????'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => '????. ??????????',
                                    'home' => '??. 54',
                                    'apartment' => '????. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 3,
                                'number' => 6,
                                'letter' => '??'
                            ]
                        ],
                        [
                            'id' => 2,
                            'item' => [
                                'id' => 1,
                                'name' => '????????????????????',
                                'description' => '????????????????????'
                            ],
                            'date' => '2011.11.10 10:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => '????????????????',
                                    'name' => '??????????????',
                                    'patronymic' => '??????????????????'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => '????. ??????????',
                                    'home' => '??. 54',
                                    'apartment' => '????. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 1,
                                'number' => 4,
                                'letter' => '??'
                            ]
                        ],
                        [
                            'id' => 3,
                            'item' => [
                                'id' => 1,
                                'name' => '????????????????????',
                                'description' => '????????????????????'
                            ],
                            'date' => '2011.11.10 11:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => '????????????????',
                                    'name' => '??????????????',
                                    'patronymic' => '??????????????????'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => '????. ??????????',
                                    'home' => '??. 54',
                                    'apartment' => '????. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 2,
                                'number' => 3,
                                'letter' => '??'
                            ]
                        ]
                    ]
                ]
            ],
            '???????????????????????? ?????????????????????? ???????????????? ???????????????????? ???? ???????????????? ??????????????????????????' => [
                'in' => [
                    'uri' => '/lesson?teacher_fio_patronymic=??????????????????',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'item' => [
                                'id' => 1,
                                'name' => '????????????????????',
                                'description' => '????????????????????'
                            ],
                            'date' => '2011.11.10 8:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => '????????????????',
                                    'name' => '??????????????',
                                    'patronymic' => '??????????????????'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => '????. ??????????',
                                    'home' => '??. 54',
                                    'apartment' => '????. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 3,
                                'number' => 6,
                                'letter' => '??'
                            ]
                        ],
                        [
                            'id' => 2,
                            'item' => [
                                'id' => 1,
                                'name' => '????????????????????',
                                'description' => '????????????????????'
                            ],
                            'date' => '2011.11.10 10:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => '????????????????',
                                    'name' => '??????????????',
                                    'patronymic' => '??????????????????'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => '????. ??????????',
                                    'home' => '??. 54',
                                    'apartment' => '????. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 1,
                                'number' => 4,
                                'letter' => '??'
                            ]
                        ],
                        [
                            'id' => 3,
                            'item' => [
                                'id' => 1,
                                'name' => '????????????????????',
                                'description' => '????????????????????'
                            ],
                            'date' => '2011.11.10 11:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => '????????????????',
                                    'name' => '??????????????',
                                    'patronymic' => '??????????????????'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => '????. ??????????',
                                    'home' => '??. 54',
                                    'apartment' => '????. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 2,
                                'number' => 3,
                                'letter' => '??'
                            ]
                        ]
                    ]
                ]
            ],
            '???????????????????????? ?????????????????????? ???????????????? ???????????????????? ???? ???????????????? ??????????????????????????' => [
                'in' => [
                    'uri' => '/lesson?teacher_cabinet=56',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'item' => [
                                'id' => 1,
                                'name' => '????????????????????',
                                'description' => '????????????????????'
                            ],
                            'date' => '2011.11.10 8:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => '????????????????',
                                    'name' => '??????????????',
                                    'patronymic' => '??????????????????'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => '????. ??????????',
                                    'home' => '??. 54',
                                    'apartment' => '????. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 3,
                                'number' => 6,
                                'letter' => '??'
                            ]
                        ],
                        [
                            'id' => 2,
                            'item' => [
                                'id' => 1,
                                'name' => '????????????????????',
                                'description' => '????????????????????'
                            ],
                            'date' => '2011.11.10 10:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => '????????????????',
                                    'name' => '??????????????',
                                    'patronymic' => '??????????????????'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => '????. ??????????',
                                    'home' => '??. 54',
                                    'apartment' => '????. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 1,
                                'number' => 4,
                                'letter' => '??'
                            ]
                        ],
                        [
                            'id' => 3,
                            'item' => [
                                'id' => 1,
                                'name' => '????????????????????',
                                'description' => '????????????????????'
                            ],
                            'date' => '2011.11.10 11:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => '????????????????',
                                    'name' => '??????????????',
                                    'patronymic' => '??????????????????'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => '????. ??????????',
                                    'home' => '??. 54',
                                    'apartment' => '????. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 2,
                                'number' => 3,
                                'letter' => '??'
                            ]
                        ]
                    ]
                ]
            ],
            '???????????????????????? ?????????????????????? ???????????????? ???????????????????? ???? ???????????? ????????????' => [
                'in' => [
                    'uri' => '/lesson?class_number=6',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'item' => [
                                'id' => 1,
                                'name' => '????????????????????',
                                'description' => '????????????????????'
                            ],
                            'date' => '2011.11.10 8:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => '????????????????',
                                    'name' => '??????????????',
                                    'patronymic' => '??????????????????'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => '????. ??????????',
                                    'home' => '??. 54',
                                    'apartment' => '????. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 3,
                                'number' => 6,
                                'letter' => '??'
                            ]
                        ]
                    ]
                ]
            ],
            '???????????????????????? ?????????????????????? ???????????????? ???????????????????? ???? ?????????? ????????????' => [
                'in' => [
                    'uri' => '/lesson?class_letter=??',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'item' => [
                                'id' => 1,
                                'name' => '????????????????????',
                                'description' => '????????????????????'
                            ],
                            'date' => '2011.11.10 8:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => '????????????????',
                                    'name' => '??????????????',
                                    'patronymic' => '??????????????????'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => '????. ??????????',
                                    'home' => '??. 54',
                                    'apartment' => '????. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 3,
                                'number' => 6,
                                'letter' => '??'
                            ]
                        ],
                        [
                            'id' => 3,
                            'item' => [
                                'id' => 1,
                                'name' => '????????????????????',
                                'description' => '????????????????????'
                            ],
                            'date' => '2011.11.10 11:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => '????????????????',
                                    'name' => '??????????????',
                                    'patronymic' => '??????????????????'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => '????. ??????????',
                                    'home' => '??. 54',
                                    'apartment' => '????. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 2,
                                'number' => 3,
                                'letter' => '??'
                            ]
                        ],
                        [
                            'id' => 5,
                            'item' => [
                                'id' => 2,
                                'name' => '??????',
                                'description' => '???????????? ???????????????????????? ??????????????????????????????????'
                            ],
                            'date' => '2011.11.11 8:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 2,
                                'fio' => [
                                    'surname' => '????????????',
                                    'name' => '????????',
                                    'patronymic' => '????????????????????????'
                                ],
                                'phone' => '+79133243412',
                                'dateOfBirth' => '1975.11.01',
                                'address' => [
                                    'street' => '????. ??????????????????',
                                    'home' => '??. 22',
                                    'apartment' => '????. 11'
                                ],
                                'item' => [
                                    'id' => 2,
                                    'name' => '??????',
                                    'description' => '???????????? ???????????????????????? ??????????????????????????????????'
                                ],
                                'cabinet' => 77,
                                'email' => 'guseva@gmail.com'
                            ],
                            'class' => [
                                'id' => 2,
                                'number' => 3,
                                'letter' => '??'
                            ]
                        ],
                    ]
                ]
            ],
            '???????????????????????? ?????????????????????????????????? ??????????????' => [
                'in' => [
                    'uri' => '/hhh?param=ru',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 404,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'unsupported request'
                    ]
                ]
            ],
            '???????????????????????? ?????????????????????????? ?????????? ???????????????? ???????????????? ?????? ???????????? ??????????????' => [
                'in' => [
                    'uri' => '/lesson?item_name[]=????????????????????',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Incorrect item name'
                    ]
                ]
            ],
            '???????????????????????? ?????????????????????????? ?????????? ?????????????????????? ???????????????? ?????? ???????????? ??????????????' => [
                'in' => [
                    'uri' => '/lesson?item_description[]=????????????????????',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Incorrect item description'
                    ]
                ]
            ],
            '???????????????????????? ?????????????????????????? ?????????? ???????? ?????????????? ?????? ???????????? ??????????????' => [
                'in' => [
                    'uri' => '/lesson?lesson_date[]=2013.11.10 8:30',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Incorrect date'
                    ]
                ]
            ],
            '???????????????????????? ?????????????????????????? ?????????? fio ??????????????????????????' => [
                'in' => [
                    'uri' => '/lesson?teacher_fio[]=???????????????? ?????????????? ??????????????????',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Incorrect teacher fio'
                    ]
                ]
            ],
            '???????????????????????? ?????????????????????????? ?????????? ???????????????? ??????????????????????????' => [
                'in' => [
                    'uri' => '/lesson?teacher_cabinet[]=56',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Incorrect teacher cabinet'
                    ]
                ]
            ],
            '???????????????????????? ?????????????????????????? ?????????? ???????????? ????????????' => [
                'in' => [
                    'uri' => '/lesson?class_number[]=6',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Incorrect class number'
                    ]
                ]
            ],
            '???????????????????????? ?????????????????????????? ?????????? ?????????? ????????????' => [
                'in' => [
                    'uri' => '/lesson?class_letter[]=??',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Incorrect class letter'
                    ]
                ]
            ],
            '???????????????????????? ?????????????? ?????? path' => [
                'in' => [
                    'uri' => '/?param=ru',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 404,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'unsupported request'
                    ]
                ]
            ],
            '???????????????????????? ?????????????????????? ???????????????? ???????????? ???? ???????????????? ????????????????' => [
                'in' => [
                    'uri' => '/assessmentReport?item_name=????????????????????',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'lesson' => [
                                'id' => 1,
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => '????????????????',
                                        'name' => '??????????????',
                                        'patronymic' => '??????????????????'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => '????. ??????????',
                                        'home' => '??. 54',
                                        'apartment' => '????. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => '????????????????????',
                                        'description' => '????????????????????'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => '??'
                                ],
                            ],
                            'student' => [
                                'id' => 4,
                                'fio' => [
                                    'surname' => '????????????????',
                                    'name' => '??????????????',
                                    'patronymic' => '????????????????????'
                                ],
                                'dateOfBirth' => '2011.01.11',
                                'phone' => '+79222444488',
                                'address' => [
                                    'street' => '????. ??????????????????',
                                    'home' => '??. 35??',
                                    'apartment' => '????. 23'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => '??'
                                ],
                                'parent' => [
                                    'id' => 12,
                                    'fio' => [
                                        'surname' => '????????????????',
                                        'name' => '??????????????',
                                        'patronymic' => '??????????????????'
                                    ],
                                    'dateOfBirth' => '1975.10.01',
                                    'phone' => '+79222444488',
                                    'address' => [
                                        'street' => '????. ??????????????????',
                                        'home' => '??. 35??',
                                        'apartment' => '????. 23'
                                    ],
                                    'placeOfWork' => '?????? ??????????',
                                    'email' => 'kuznecov@gmail.com'
                                ],
                            ],
                            'mark' => 5
                        ],
                        [
                            'id' => 2,
                            'lesson' => [
                                'id' => 1,
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => '????????????????',
                                        'name' => '??????????????',
                                        'patronymic' => '??????????????????'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => '????. ??????????',
                                        'home' => '??. 54',
                                        'apartment' => '????. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => '????????????????????',
                                        'description' => '????????????????????'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => '??'
                                ],
                            ],
                            'student' => [
                                'id' => 10,
                                'fio' => [
                                    'surname' => '????????????',
                                    'name' => '????????????????',
                                    'patronymic' => '??????????????'
                                ],
                                'dateOfBirth' => '2009.04.23',
                                'phone' => '+79888444488',
                                'address' => [
                                    'street' => '????. ??????????',
                                    'home' => '??. 54',
                                    'apartment' => '????. 22'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => '??'
                                ],
                                'parent' => [
                                    'id' => 13,
                                    'fio' => [
                                        'surname' => '????????????',
                                        'name' => '????????',
                                        'patronymic' => '????????????????????????'
                                    ],
                                    'phone' => '+79888444488',
                                    'dateOfBirth' => '1985.11.10',
                                    'address' => [
                                        'street' => '????. ??????????',
                                        'home' => '??. 54',
                                        'apartment' => '????. 22'
                                    ],
                                    'placeOfWork' => '?????? ??????????',
                                    'email' => 'krabov@gmail.com'
                                ],
                            ],
                            'mark' => 4
                        ],
                        [
                            'id' => 4,
                            'lesson' => [
                                'id' => 2,
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'date' => '2011.11.10 10:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => '????????????????',
                                        'name' => '??????????????',
                                        'patronymic' => '??????????????????'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => '????. ??????????',
                                        'home' => '??. 54',
                                        'apartment' => '????. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => '????????????????????',
                                        'description' => '????????????????????'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => '??'
                                ],
                            ],
                            'student' => [
                                'id' => 5,
                                'fio' => [
                                    'surname' => '????????????????',
                                    'name' => '????????',
                                    'patronymic' => '??????????????'
                                ],
                                'dateOfBirth' => '2011.01.12',
                                'phone' => '+79222433488',
                                'address' => [
                                    'street' => '????. ??????????????????',
                                    'home' => '??. 47',
                                    'apartment' => '????. 34'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => '??'
                                ],
                                'parent' => [
                                    'id' => 19,
                                    'fio' => [
                                        'surname' => '????????????????',
                                        'name' => '??????????',
                                        'patronymic' => '????????????????????'
                                    ],
                                    'dateOfBirth' => '1985.01.11',
                                    'phone' => '+79222433488',
                                    'address' => [
                                        'street' => '????. ??????????????????',
                                        'home' => '??. 47',
                                        'apartment' => '????. 34'
                                    ],
                                    'placeOfWork' => '?????? ??????????',
                                    'email' => 'sokolova@gmail.com'
                                ],
                            ],
                            'mark' => 4
                        ],
                        [
                            'id' => 8,
                            'lesson' => [
                                'id' => 1,
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => '????????????????',
                                        'name' => '??????????????',
                                        'patronymic' => '??????????????????'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => '????. ??????????',
                                        'home' => '??. 54',
                                        'apartment' => '????. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => '????????????????????',
                                        'description' => '????????????????????'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => '??'
                                ],
                            ],
                            'student' => [
                                'id' => 8,
                                'fio' => [
                                    'surname' => '??????????????????',
                                    'name' => '??????????????????',
                                    'patronymic' => '??????????????????'
                                ],
                                'dateOfBirth' => '2012.11.12',
                                'phone' => '+79223333388',
                                'address' => [
                                    'street' => '????. ????????????????',
                                    'home' => '??. 45',
                                    'apartment' => '????. 45'
                                ],
                                'class' => [
                                    'id' => 2,
                                    'number' => 3,
                                    'letter' => '??'
                                ],
                                'parent' => [
                                    'id' => 17,
                                    'fio' => [
                                        'surname' => '??????????????????',
                                        'name' => '??????????????',
                                        'patronymic' => '????????????????????'
                                    ],
                                    'phone' => '+79223333388',
                                    'dateOfBirth' => '1978.02.05',
                                    'address' => [
                                        'street' => '????. ????????????????',
                                        'home' => '??. 45',
                                        'apartment' => '????. 45'
                                    ],
                                    'placeOfWork' => '???? ??????????????',
                                    'email' => 'kuznecova@gmail.com'
                                ],
                            ],
                            'mark' => 5
                        ]
                    ]
                ]
            ],
            '???????????????????????? ???????????? ???????????? ?? ???????????????? ???? ?????????????????????? ???????????????? ????????????????' => [
                'in' => [
                    'uri' => '/assessmentReport?item_description=????????????????????',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'lesson' => [
                                'id' => 1,
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => '????????????????',
                                        'name' => '??????????????',
                                        'patronymic' => '??????????????????'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => '????. ??????????',
                                        'home' => '??. 54',
                                        'apartment' => '????. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => '????????????????????',
                                        'description' => '????????????????????'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => '??'
                                ],
                            ],
                            'student' => [
                                'id' => 4,
                                'fio' => [
                                    'surname' => '????????????????',
                                    'name' => '??????????????',
                                    'patronymic' => '????????????????????'
                                ],
                                'dateOfBirth' => '2011.01.11',
                                'phone' => '+79222444488',
                                'address' => [
                                    'street' => '????. ??????????????????',
                                    'home' => '??. 35??',
                                    'apartment' => '????. 23'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => '??'
                                ],
                                'parent' => [
                                    'id' => 12,
                                    'fio' => [
                                        'surname' => '????????????????',
                                        'name' => '??????????????',
                                        'patronymic' => '??????????????????'
                                    ],
                                    'dateOfBirth' => '1975.10.01',
                                    'phone' => '+79222444488',
                                    'address' => [
                                        'street' => '????. ??????????????????',
                                        'home' => '??. 35??',
                                        'apartment' => '????. 23'
                                    ],
                                    'placeOfWork' => '?????? ??????????',
                                    'email' => 'kuznecov@gmail.com'
                                ],
                            ],
                            'mark' => 5
                        ],
                        [
                            'id' => 2,
                            'lesson' => [
                                'id' => 1,
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => '????????????????',
                                        'name' => '??????????????',
                                        'patronymic' => '??????????????????'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => '????. ??????????',
                                        'home' => '??. 54',
                                        'apartment' => '????. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => '????????????????????',
                                        'description' => '????????????????????'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => '??'
                                ],
                            ],
                            'student' => [
                                'id' => 10,
                                'fio' => [
                                    'surname' => '????????????',
                                    'name' => '????????????????',
                                    'patronymic' => '??????????????'
                                ],
                                'dateOfBirth' => '2009.04.23',
                                'phone' => '+79888444488',
                                'address' => [
                                    'street' => '????. ??????????',
                                    'home' => '??. 54',
                                    'apartment' => '????. 22'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => '??'
                                ],
                                'parent' => [
                                    'id' => 13,
                                    'fio' => [
                                        'surname' => '????????????',
                                        'name' => '????????',
                                        'patronymic' => '????????????????????????'
                                    ],
                                    'phone' => '+79888444488',
                                    'dateOfBirth' => '1985.11.10',
                                    'address' => [
                                        'street' => '????. ??????????',
                                        'home' => '??. 54',
                                        'apartment' => '????. 22'
                                    ],
                                    'placeOfWork' => '?????? ??????????',
                                    'email' => 'krabov@gmail.com'
                                ],
                            ],
                            'mark' => 4
                        ],
                        [
                            'id' => 4,
                            'lesson' => [
                                'id' => 2,
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'date' => '2011.11.10 10:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => '????????????????',
                                        'name' => '??????????????',
                                        'patronymic' => '??????????????????'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => '????. ??????????',
                                        'home' => '??. 54',
                                        'apartment' => '????. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => '????????????????????',
                                        'description' => '????????????????????'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => '??'
                                ],
                            ],
                            'student' => [
                                'id' => 5,
                                'fio' => [
                                    'surname' => '????????????????',
                                    'name' => '????????',
                                    'patronymic' => '??????????????'
                                ],
                                'dateOfBirth' => '2011.01.12',
                                'phone' => '+79222433488',
                                'address' => [
                                    'street' => '????. ??????????????????',
                                    'home' => '??. 47',
                                    'apartment' => '????. 34'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => '??'
                                ],
                                'parent' => [
                                    'id' => 19,
                                    'fio' => [
                                        'surname' => '????????????????',
                                        'name' => '??????????',
                                        'patronymic' => '????????????????????'
                                    ],
                                    'dateOfBirth' => '1985.01.11',
                                    'phone' => '+79222433488',
                                    'address' => [
                                        'street' => '????. ??????????????????',
                                        'home' => '??. 47',
                                        'apartment' => '????. 34'
                                    ],
                                    'placeOfWork' => '?????? ??????????',
                                    'email' => 'sokolova@gmail.com'
                                ],
                            ],
                            'mark' => 4
                        ],
                        [
                            'id' => 8,
                            'lesson' => [
                                'id' => 1,
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => '????????????????',
                                        'name' => '??????????????',
                                        'patronymic' => '??????????????????'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => '????. ??????????',
                                        'home' => '??. 54',
                                        'apartment' => '????. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => '????????????????????',
                                        'description' => '????????????????????'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => '??'
                                ],
                            ],
                            'student' => [
                                'id' => 8,
                                'fio' => [
                                    'surname' => '??????????????????',
                                    'name' => '??????????????????',
                                    'patronymic' => '??????????????????'
                                ],
                                'dateOfBirth' => '2012.11.12',
                                'phone' => '+79223333388',
                                'address' => [
                                    'street' => '????. ????????????????',
                                    'home' => '??. 45',
                                    'apartment' => '????. 45'
                                ],
                                'class' => [
                                    'id' => 2,
                                    'number' => 3,
                                    'letter' => '??'
                                ],
                                'parent' => [
                                    'id' => 17,
                                    'fio' => [
                                        'surname' => '??????????????????',
                                        'name' => '??????????????',
                                        'patronymic' => '????????????????????'
                                    ],
                                    'phone' => '+79223333388',
                                    'dateOfBirth' => '1978.02.05',
                                    'address' => [
                                        'street' => '????. ????????????????',
                                        'home' => '??. 45',
                                        'apartment' => '????. 45'
                                    ],
                                    'placeOfWork' => '???? ??????????????',
                                    'email' => 'kuznecova@gmail.com'
                                ],
                            ],
                            'mark' => 5
                        ]
                    ]
                ]
            ],
            '???????????????????????? ???????????? ???????????? ?? ???????????????? ???? ???????? ???????????????????? ??????????????' => [
                'in' => [
                    'uri' => '/assessmentReport?lesson_date=2011.11.10 8:30',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'lesson' => [
                                'id' => 1,
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => '????????????????',
                                        'name' => '??????????????',
                                        'patronymic' => '??????????????????'
                                    ],
                                    'phone' => '+79222444411',
                                    'dateOfBirth' => '1965.01.11',
                                    'address' => [
                                        'street' => '????. ??????????',
                                        'home' => '??. 54',
                                        'apartment' => '????. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => '????????????????????',
                                        'description' => '????????????????????'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => '??'
                                ],
                            ],
                            'student' => [
                                'id' => 4,
                                'fio' => [
                                    'surname' => '????????????????',
                                    'name' => '??????????????',
                                    'patronymic' => '????????????????????'
                                ],
                                'dateOfBirth' => '2011.01.11',
                                'phone' => '+79222444488',
                                'address' => [
                                    'street' => '????. ??????????????????',
                                    'home' => '??. 35??',
                                    'apartment' => '????. 23'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => '??'
                                ],
                                'parent' => [
                                    'id' => 12,
                                    'fio' => [
                                        'surname' => '????????????????',
                                        'name' => '??????????????',
                                        'patronymic' => '??????????????????'
                                    ],
                                    'dateOfBirth' => '1975.10.01',
                                    'phone' => '+79222444488',
                                    'address' => [
                                        'street' => '????. ??????????????????',
                                        'home' => '??. 35??',
                                        'apartment' => '????. 23'
                                    ],
                                    'placeOfWork' => '?????? ??????????',
                                    'email' => 'kuznecov@gmail.com'
                                ],
                            ],
                            'mark' => 5
                        ],
                        [
                            'id' => 2,
                            'lesson' => [
                                'id' => 1,
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => '????????????????',
                                        'name' => '??????????????',
                                        'patronymic' => '??????????????????'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => '????. ??????????',
                                        'home' => '??. 54',
                                        'apartment' => '????. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => '????????????????????',
                                        'description' => '????????????????????'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => '??'
                                ],
                            ],
                            'student' => [
                                'id' => 10,
                                'fio' => [
                                    'surname' => '????????????',
                                    'name' => '????????????????',
                                    'patronymic' => '??????????????'
                                ],
                                'dateOfBirth' => '2009.04.23',
                                'phone' => '+79888444488',
                                'address' => [
                                    'street' => '????. ??????????',
                                    'home' => '??. 54',
                                    'apartment' => '????. 22'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => '??'
                                ],
                                'parent' => [
                                    'id' => 13,
                                    'fio' => [
                                        'surname' => '????????????',
                                        'name' => '????????',
                                        'patronymic' => '????????????????????????'
                                    ],
                                    'dateOfBirth' => '1985.11.10',
                                    'phone' => '+79888444488',
                                    'address' => [
                                        'street' => '????. ??????????',
                                        'home' => '??. 54',
                                        'apartment' => '????. 22'
                                    ],
                                    'placeOfWork' => '?????? ??????????',
                                    'email' => 'krabov@gmail.com'
                                ],
                            ],
                            'mark' => 4
                        ],
                        [
                            'id' => 8,
                            'lesson' => [
                                'id' => 1,
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => '????????????????',
                                        'name' => '??????????????',
                                        'patronymic' => '??????????????????'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => '????. ??????????',
                                        'home' => '??. 54',
                                        'apartment' => '????. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => '????????????????????',
                                        'description' => '????????????????????'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => '??'
                                ],
                            ],
                            'student' => [
                                'id' => 8,
                                'fio' => [
                                    'surname' => '??????????????????',
                                    'name' => '??????????????????',
                                    'patronymic' => '??????????????????'
                                ],
                                'dateOfBirth' => '2012.11.12',
                                'phone' => '+79223333388',
                                'address' => [
                                    'street' => '????. ????????????????',
                                    'home' => '??. 45',
                                    'apartment' => '????. 45'
                                ],
                                'class' => [
                                    'id' => 2,
                                    'number' => 3,
                                    'letter' => '??'
                                ],
                                'parent' => [
                                    'id' => 17,
                                    'fio' => [
                                        'surname' => '??????????????????',
                                        'name' => '??????????????',
                                        'patronymic' => '????????????????????'
                                    ],
                                    'dateOfBirth' => '1978.02.05',
                                    'phone' => '+79223333388',
                                    'address' => [
                                        'street' => '????. ????????????????',
                                        'home' => '??. 45',
                                        'apartment' => '????. 45'
                                    ],
                                    'placeOfWork' => '???? ??????????????',
                                    'email' => 'kuznecova@gmail.com'
                                ],
                            ],
                            'mark' => 5
                        ]
                    ]
                ]
            ],
            '???????????????????????? ???????????? ???????????? ?? ???????????????? ???? ?????????????? c??????????????' => [
                'in' => [
                    'uri' => '/assessmentReport?student_fio_surname=????????????????',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'lesson' => [
                                'id' => 1,
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => '????????????????',
                                        'name' => '??????????????',
                                        'patronymic' => '??????????????????'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => '????. ??????????',
                                        'home' => '??. 54',
                                        'apartment' => '????. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => '????????????????????',
                                        'description' => '????????????????????'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => '??'
                                ],
                            ],
                            'student' => [
                                'id' => 4,
                                'fio' => [
                                    'surname' => '????????????????',
                                    'name' => '??????????????',
                                    'patronymic' => '????????????????????'
                                ],
                                'dateOfBirth' => '2011.01.11',
                                'phone' => '+79222444488',
                                'address' => [
                                    'street' => '????. ??????????????????',
                                    'home' => '??. 35??',
                                    'apartment' => '????. 23'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => '??'
                                ],
                                'parent' => [
                                    'id' => 12,
                                    'fio' => [
                                        'surname' => '????????????????',
                                        'name' => '??????????????',
                                        'patronymic' => '??????????????????'
                                    ],
                                    'phone' => '+79222444488',
                                    'dateOfBirth' => '1975.10.01',
                                    'address' => [
                                        'street' => '????. ??????????????????',
                                        'home' => '??. 35??',
                                        'apartment' => '????. 23'
                                    ],
                                    'placeOfWork' => '?????? ??????????',
                                    'email' => 'kuznecov@gmail.com'
                                ],
                            ],
                            'mark' => 5
                        ],
                        [
                            'id' => 3,
                            'lesson' => [
                                'id' => 6,
                                'item' => [
                                    'id' => 3,
                                    'name' => '??????????',
                                    'description' => '??????????'
                                ],
                                'date' => '2011.11.11 10:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 3,
                                    'fio' => [
                                        'surname' => '????????????????',
                                        'name' => '??????????????',
                                        'patronymic' => '????????????????????'
                                    ],
                                    'phone' => '+79655346343',
                                    'dateOfBirth' => '1970.02.01',
                                    'address' => [
                                        'street' => '????. ????????????????',
                                        'home' => '??. 11',
                                        'apartment' => '????. 11'
                                    ],
                                    'item' => [
                                        'id' => 3,
                                        'name' => '??????????',
                                        'description' => '??????????'
                                    ],
                                    'cabinet' => 64,
                                    'email' => 'dmitriev@gmail.com'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => '??'
                                ],
                            ],
                            'student' => [
                                'id' => 4,
                                'fio' => [
                                    'surname' => '????????????????',
                                    'name' => '??????????????',
                                    'patronymic' => '????????????????????'
                                ],
                                'phone' => '+79222444488',
                                'dateOfBirth' => '2011.01.11',
                                'address' => [
                                    'street' => '????. ??????????????????',
                                    'home' => '??. 35??',
                                    'apartment' => '????. 23'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => '??'
                                ],
                                'parent' => [
                                    'id' => 12,
                                    'fio' => [
                                        'surname' => '????????????????',
                                        'name' => '??????????????',
                                        'patronymic' => '??????????????????'
                                    ],
                                    'phone' => '+79222444488',
                                    'dateOfBirth' => '1975.10.01',
                                    'address' => [
                                        'street' => '????. ??????????????????',
                                        'home' => '??. 35??',
                                        'apartment' => '????. 23'
                                    ],
                                    'placeOfWork' => '?????? ??????????',
                                    'email' => 'kuznecov@gmail.com'
                                ],
                            ],
                            'mark' => 3
                        ]
                    ]
                ]
            ],
            '???????????????????????? ???????????? ???????????? ?? ???????????????? ???? ?????????? c??????????????' => [
                'in' => [
                    'uri' => '/assessmentReport?student_fio_name=??????????????',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'lesson' => [
                                'id' => 1,
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => '????????????????',
                                        'name' => '??????????????',
                                        'patronymic' => '??????????????????'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => '????. ??????????',
                                        'home' => '??. 54',
                                        'apartment' => '????. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => '????????????????????',
                                        'description' => '????????????????????'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => '??'
                                ],
                            ],
                            'student' => [
                                'id' => 4,
                                'fio' => [
                                    'surname' => '????????????????',
                                    'name' => '??????????????',
                                    'patronymic' => '????????????????????'
                                ],
                                'dateOfBirth' => '2011.01.11',
                                'phone' => '+79222444488',
                                'address' => [
                                    'street' => '????. ??????????????????',
                                    'home' => '??. 35??',
                                    'apartment' => '????. 23'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => '??'
                                ],
                                'parent' => [
                                    'id' => 12,
                                    'fio' => [
                                        'surname' => '????????????????',
                                        'name' => '??????????????',
                                        'patronymic' => '??????????????????'
                                    ],
                                    'phone' => '+79222444488',
                                    'dateOfBirth' => '1975.10.01',
                                    'address' => [
                                        'street' => '????. ??????????????????',
                                        'home' => '??. 35??',
                                        'apartment' => '????. 23'
                                    ],
                                    'placeOfWork' => '?????? ??????????',
                                    'email' => 'kuznecov@gmail.com'
                                ],
                            ],
                            'mark' => 5
                        ],
                        [
                            'id' => 3,
                            'lesson' => [
                                'id' => 6,
                                'item' => [
                                    'id' => 3,
                                    'name' => '??????????',
                                    'description' => '??????????'
                                ],
                                'date' => '2011.11.11 10:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 3,
                                    'fio' => [
                                        'surname' => '????????????????',
                                        'name' => '??????????????',
                                        'patronymic' => '????????????????????'
                                    ],
                                    'phone' => '+79655346343',
                                    'dateOfBirth' => '1970.02.01',
                                    'address' => [
                                        'street' => '????. ????????????????',
                                        'home' => '??. 11',
                                        'apartment' => '????. 11'
                                    ],
                                    'item' => [
                                        'id' => 3,
                                        'name' => '??????????',
                                        'description' => '??????????'
                                    ],
                                    'cabinet' => 64,
                                    'email' => 'dmitriev@gmail.com'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => '??'
                                ],
                            ],
                            'student' => [
                                'id' => 4,
                                'fio' => [
                                    'surname' => '????????????????',
                                    'name' => '??????????????',
                                    'patronymic' => '????????????????????'
                                ],
                                'phone' => '+79222444488',
                                'dateOfBirth' => '2011.01.11',
                                'address' => [
                                    'street' => '????. ??????????????????',
                                    'home' => '??. 35??',
                                    'apartment' => '????. 23'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => '??'
                                ],
                                'parent' => [
                                    'id' => 12,
                                    'fio' => [
                                        'surname' => '????????????????',
                                        'name' => '??????????????',
                                        'patronymic' => '??????????????????'
                                    ],
                                    'phone' => '+79222444488',
                                    'dateOfBirth' => '1975.10.01',
                                    'address' => [
                                        'street' => '????. ??????????????????',
                                        'home' => '??. 35??',
                                        'apartment' => '????. 23'
                                    ],
                                    'placeOfWork' => '?????? ??????????',
                                    'email' => 'kuznecov@gmail.com'
                                ],
                            ],
                            'mark' => 3
                        ]
                    ]
                ]
            ],
            '???????????????????????? ???????????? ???????????? ?? ???????????????? ???? ???????????????? c??????????????' => [
                'in' => [
                    'uri' => '/assessmentReport?student_fio_patronymic=????????????????????',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'lesson' => [
                                'id' => 1,
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => '????????????????',
                                        'name' => '??????????????',
                                        'patronymic' => '??????????????????'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => '????. ??????????',
                                        'home' => '??. 54',
                                        'apartment' => '????. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => '????????????????????',
                                        'description' => '????????????????????'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => '??'
                                ],
                            ],
                            'student' => [
                                'id' => 4,
                                'fio' => [
                                    'surname' => '????????????????',
                                    'name' => '??????????????',
                                    'patronymic' => '????????????????????'
                                ],
                                'dateOfBirth' => '2011.01.11',
                                'phone' => '+79222444488',
                                'address' => [
                                    'street' => '????. ??????????????????',
                                    'home' => '??. 35??',
                                    'apartment' => '????. 23'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => '??'
                                ],
                                'parent' => [
                                    'id' => 12,
                                    'fio' => [
                                        'surname' => '????????????????',
                                        'name' => '??????????????',
                                        'patronymic' => '??????????????????'
                                    ],
                                    'phone' => '+79222444488',
                                    'dateOfBirth' => '1975.10.01',
                                    'address' => [
                                        'street' => '????. ??????????????????',
                                        'home' => '??. 35??',
                                        'apartment' => '????. 23'
                                    ],
                                    'placeOfWork' => '?????? ??????????',
                                    'email' => 'kuznecov@gmail.com'
                                ],
                            ],
                            'mark' => 5
                        ],
                        [
                            'id' => 3,
                            'lesson' => [
                                'id' => 6,
                                'item' => [
                                    'id' => 3,
                                    'name' => '??????????',
                                    'description' => '??????????'
                                ],
                                'date' => '2011.11.11 10:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 3,
                                    'fio' => [
                                        'surname' => '????????????????',
                                        'name' => '??????????????',
                                        'patronymic' => '????????????????????'
                                    ],
                                    'phone' => '+79655346343',
                                    'dateOfBirth' => '1970.02.01',
                                    'address' => [
                                        'street' => '????. ????????????????',
                                        'home' => '??. 11',
                                        'apartment' => '????. 11'
                                    ],
                                    'item' => [
                                        'id' => 3,
                                        'name' => '??????????',
                                        'description' => '??????????'
                                    ],
                                    'cabinet' => 64,
                                    'email' => 'dmitriev@gmail.com'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => '??'
                                ],
                            ],
                            'student' => [
                                'id' => 4,
                                'fio' => [
                                    'surname' => '????????????????',
                                    'name' => '??????????????',
                                    'patronymic' => '????????????????????'
                                ],
                                'phone' => '+79222444488',
                                'dateOfBirth' => '2011.01.11',
                                'address' => [
                                    'street' => '????. ??????????????????',
                                    'home' => '??. 35??',
                                    'apartment' => '????. 23'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => '??'
                                ],
                                'parent' => [
                                    'id' => 12,
                                    'fio' => [
                                        'surname' => '????????????????',
                                        'name' => '??????????????',
                                        'patronymic' => '??????????????????'
                                    ],
                                    'phone' => '+79222444488',
                                    'dateOfBirth' => '1975.10.01',
                                    'address' => [
                                        'street' => '????. ??????????????????',
                                        'home' => '??. 35??',
                                        'apartment' => '????. 23'
                                    ],
                                    'placeOfWork' => '?????? ??????????',
                                    'email' => 'kuznecov@gmail.com'
                                ],
                            ],
                            'mark' => 3
                        ]
                    ]
                ]
            ],
            '???????????????????????? ???????????? ???????????? ?? ???????????????? ???? id ????????????' => [
                'in' => [
                    'uri' => '/assessmentReport/1',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        'id' => 1,
                        'lesson' => [
                            'id' => 1,
                            'item' => [
                                'id' => 1,
                                'name' => '????????????????????',
                                'description' => '????????????????????'
                            ],
                            'date' => '2011.11.10 8:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => '????????????????',
                                    'name' => '??????????????',
                                    'patronymic' => '??????????????????'
                                ],
                                'dateOfBirth' => '1965.01.11',
                                'phone' => '+79222444411',
                                'address' => [
                                    'street' => '????. ??????????',
                                    'home' => '??. 54',
                                    'apartment' => '????. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => '????????????????????',
                                    'description' => '????????????????????'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 3,
                                'number' => 6,
                                'letter' => '??'
                            ],
                        ],
                        'student' => [
                            'id' => 4,
                            'fio' => [
                                'surname' => '????????????????',
                                'name' => '??????????????',
                                'patronymic' => '????????????????????'
                            ],
                            'dateOfBirth' => '2011.01.11',
                            'phone' => '+79222444488',
                            'address' => [
                                'street' => '????. ??????????????????',
                                'home' => '??. 35??',
                                'apartment' => '????. 23'
                            ],
                            'class' => [
                                'id' => 1,
                                'number' => 4,
                                'letter' => '??'
                            ],
                            'parent' => [
                                'id' => 12,
                                'fio' => [
                                    'surname' => '????????????????',
                                    'name' => '??????????????',
                                    'patronymic' => '??????????????????'
                                ],
                                'phone' => '+79222444488',
                                'dateOfBirth' => '1975.10.01',
                                'address' => [
                                    'street' => '????. ??????????????????',
                                    'home' => '??. 35??',
                                    'apartment' => '????. 23'
                                ],
                                'placeOfWork' => '?????? ??????????',
                                'email' => 'kuznecov@gmail.com'
                            ],
                        ],
                        'mark' => 5
                    ]
                ]
            ],
            '???????????????????????? ?????????????????????????? ?????????? ???????????????? ???????????????? ?????? ???????????? ????????????' => [
                'in' => [
                    'uri' => '/assessmentReport?item_name[]=????????????????????',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Incorrect item name'
                    ]
                ]
            ],
            '???????????????????????? ?????????????????????????? ?????????? ?????????????????????? ???????????????? ?????? ???????????? ????????????' => [
                'in' => [
                    'uri' => '/assessmentReport?item_description[]=????????????????????',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Incorrect item description'
                    ]
                ]
            ],
            '???????????????????????? ?????????????????????????? ?????????? ???????? ?????????????? ?????? ???????????? ????????????' => [
                'in' => [
                    'uri' => '/assessmentReport?lesson_date[]=2011.11.10 8:30',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Incorrect lesson date'
                    ]
                ]
            ],
            '???????????????????????? ?????????????????????????? ?????????? ?????? c??????????????' => [
                'in' => [
                    'uri' => '/assessmentReport?student_fio[]=???????????????? ?????????????? ????????????????????',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Incorrect student fio'
                    ]
                ]
            ],
            '???????????????????????? ???????????????? ?????????? ???????????? ?? ?????????????? ???? ??????????????????. ?????? ???????? date' => [
                'in' => [
                    'uri' => '/lesson?item_name=????????????????????',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $appConfigParams = $c->getParameter('app.configs');
                        $appConfigParams['pathToLesson'] = __DIR__ . '/data/broken.lesson.json';
                        $c->setParameter('app.configs', $appConfigParams);
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => '?????????????????? ???????????????????????? ????????????????: date'
                    ]
                ]
            ],
            '???????????????????????? ???????????????? ?????????? ???????????? ?? ???????????? ???? ??????????????????. ?????? ???????? mark' => [
                'in' => [
                    'uri' => '/assessmentReport?item_name=????????????????????',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $appConfigParams = $c->getParameter('app.configs');
                        $appConfigParams['pathToAssessmentReport'] = __DIR__ . '/data/broken.assessmentReport.json';
                        $c->setParameter('app.configs', $appConfigParams);
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => '?????????????????? ???????????????????????? ????????????????: mark'
                    ]
                ]
            ],
            '???????????????????????? ???????????????? ?????????? ???????????? ???? ?????????????????? ???? ??????????????????. ?????? ???????? description' => [
                'in' => [
                    'uri' => '/lesson?item_name=????????????????????',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $appConfigParams = $c->getParameter('app.configs');
                        $appConfigParams['pathToItems'] = __DIR__ . '/data/broken.item.json';
                        $c->setParameter('app.configs', $appConfigParams);
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => '?????????????????? ???????????????????????? ????????????????: description'
                    ]
                ]
            ],
            '???????????????????????? ???????????????? ?????????? ???????????? ???? ?????????????? ???? ??????????????????. ?????? ???????? letter' => [
                'in' => [
                    'uri' => '/lesson?item_name=????????????????????',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $appConfigParams = $c->getParameter('app.configs');
                        $appConfigParams['pathToClasses'] = __DIR__ . '/data/broken.class.json';
                        $c->setParameter('app.configs', $appConfigParams);
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => '?????????????????? ???????????????????????? ????????????????: letter'
                    ]
                ]
            ],
            '???????????????????????? ???????????????? ?????????? ???????????? ???? ?????????????????? ???? ??????????????????. ?????? ???????? email,login,password' => [
                'in' => [
                    'uri' => '/assessmentReport?item_name=????????????????????',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $appConfigParams = $c->getParameter('app.configs');
                        $appConfigParams['pathToParents'] = __DIR__ . '/data/broken.parent.json';
                        $c->setParameter('app.configs', $appConfigParams);
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => '???????????? ?? ?????? ?????????? ???????????????? ????????????'
                    ]
                ]
            ],
            '???????????????????????? ???????????????? ?????????? ???????????? ???? ???????????????? ???? ??????????????????. ?????? ???????? address' => [
                'in' => [
                    'uri' => '/assessmentReport?item_name=????????????????????',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $appConfigParams = $c->getParameter('app.configs');
                        $appConfigParams['pathToStudents'] = __DIR__ . '/data/broken.student.json';
                        $c->setParameter('app.configs', $appConfigParams);
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => '?????? ???????????? ?? ????????????????'
                    ]
                ]
            ],
            '???????????????????????? ???????????????? ?????????? ???????????? ???? ???????????????? ???? ??????????????????. ?????? ???????? email,login,password' => [
                'in' => [
                    'uri' => '/assessmentReport?item_name=????????????????????',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $appConfigParams = $c->getParameter('app.configs');
                        $appConfigParams['pathToTeachers'] = __DIR__ . '/data/broken.teacher.json';
                        $c->setParameter('app.configs', $appConfigParams);
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => '???????????? ?? ?????? ?????????? ???????????????? ????????????'
                    ]
                ]
            ],
            '???????????????????????? ???????????????? c ???????????????????????? ?????????? ???? ?????????? ?? ??????????????????' => [
                'in' => [
                    'uri' => '/lesson?item_name=????????????????????',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $appConfigParams = $c->getParameter('app.configs');
                        $appConfigParams['pathToLesson'] = __DIR__ . '/unknown.lesson.json';
                        $c->setParameter('app.configs', $appConfigParams);
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => '???????????????????????? ???????? ???? ?????????? ?? ??????????????'
                    ]
                ]
            ],
            '???????????????????????? ???????????????? c ???????????????????????? ?????????? ???? ?????????? ?? ????????????????' => [
                'in' => [
                    'uri' => '/assessmentReport?item_name=????????????????????',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $appConfigParams = $c->getParameter('app.configs');
                        $appConfigParams['pathToAssessmentReport'] = __DIR__ . '/unknown.assessmentReport.json';
                        $c->setParameter('app.configs', $appConfigParams);
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => '???????????????????????? ???????? ???? ?????????? ?? ??????????????'
                    ]
                ]
            ],
            '???????????????????????? ???????????????? c ???????????????????????? ?????????? ???? ?????????? ?? ????????????????' => [
                'in' => [
                    'uri' => '/assessmentReport?item_name=????????????????????',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $appConfigParams = $c->getParameter('app.configs');
                        $appConfigParams['pathToClasses'] = __DIR__ . '/unknown.class.json';
                        $c->setParameter('app.configs', $appConfigParams);
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => '???????????????????????? ???????? ???? ?????????? ?? ??????????????'
                    ]
                ]
            ],
            '???????????????????????? ???????????????? c ???????????????????????? ?????????? ???? ?????????? ?? ????????????????????' => [
                'in' => [
                    'uri' => '/assessmentReport?item_name=????????????????????',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $appConfigParams = $c->getParameter('app.configs');
                        $appConfigParams['pathToItems'] = __DIR__ . '/unknown.Item.json';
                        $c->setParameter('app.configs', $appConfigParams);
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => '???????????????????????? ???????? ???? ?????????? ?? ??????????????'
                    ]
                ]
            ],
            '???????????????????????? ???????????????? c ???????????????????????? ?????????? ???? ?????????? ?? ????????????????????' => [
                'in' => [
                    'uri' => '/assessmentReport?item_name=????????????????????',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $appConfigParams = $c->getParameter('app.configs');
                        $appConfigParams['pathToParents'] = __DIR__ . '/unknown.parent.json';
                        $c->setParameter('app.configs', $appConfigParams);
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => '???????????????????????? ???????? ???? ?????????? ?? ??????????????'
                    ]
                ]
            ],
            '???????????????????????? ???????????????? c ???????????????????????? ?????????? ???? ?????????? ?? ??????????????????' => [
                'in' => [
                    'uri' => '/assessmentReport?item_name=????????????????????',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $appConfigParams = $c->getParameter('app.configs');
                        $appConfigParams['pathToStudents'] = __DIR__ . '/unknown.student.json';
                        $c->setParameter('app.configs', $appConfigParams);
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => '???????????????????????? ???????? ???? ?????????? ?? ??????????????'
                    ]
                ]
            ],
            '???????????????????????? ???????????????? c ???????????????????????? ?????????? ???? ?????????? ?? ??????????????????' => [
                'in' => [
                    'uri' => '/assessmentReport?item_name=????????????????????',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $appConfigParams = $c->getParameter('app.configs');
                        $appConfigParams['pathToTeachers'] = __DIR__ . '/unknown.teacher.json';
                        $c->setParameter('app.configs', $appConfigParams);
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => '???????????????????????? ???????? ???? ?????????? ?? ??????????????'
                    ]
                ]
            ],
            '???????????????????????? ???????????????? ?????????? ?????? ??????????????' => [
                'in' => [
                    'uri' => '/lesson?item_name=????????????????????',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->getDefinition(AppConfig::class)->setFactory([AppTest::class, 'bugFactory']);
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'system error'
                    ]
                ]
            ]
        ];
    }


    /**
     * ???????????? ??????????
     *
     * @dataProvider dataProvider
     * @param array $in - ?????????????? ???????????? ?????? ??????????
     * @param array $out - ?????????????? ???????????? ?????? ????????????????
     * @return void
     * @throws JsonException
     */
    public function testApp(array $in, array $out): void
    {
        //Arrange
        $httpRequest = new ServerRequest(
            'GET',
            new Uri($in['uri']),
            ['Content-Type' => 'application/json'],
        );
        $queryParams = [];
        parse_str($httpRequest->getUri()->getQuery(), $queryParams);
        $httpRequest = $httpRequest->withQueryParams($queryParams);
        $diContainer = $in['diContainer'];
        $app = new App(
            (new AppConfiguration())->setContainerFactory(
                static function () use ($diContainer): ContainerInterface {
                    return $diContainer;
                }
            )

        );
        //Action
        $httpResponse = $app->dispatch($httpRequest);
        // Assert
        $this->assertEquals($out['httpCode'], $httpResponse->getStatusCode(), '?????? ????????????');
        $this->assertEquals(
            $out['result'],
            json_decode($httpResponse->getBody(), true, 512, JSON_THROW_ON_ERROR),
            '?????????????????? ????????????'
        );
    }

}
