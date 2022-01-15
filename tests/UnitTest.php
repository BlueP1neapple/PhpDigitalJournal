<?php

require_once __DIR__ . "/../src/Infrastructure/Autoloader.php";

use JoJoBizzareCoders\DigitalJournal\Infrastructure\App;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\AppConfig;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Autoloader;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DI\Container;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerRequest;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\LoggerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\NullLogger\Logger;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Router\RouterInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Uri\Uri;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\View\NullRender;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\View\RenderInterface;
use JoJoBizzareCoders\DigitalJournalTest\TestUtils;


spl_autoload_register(
    new Autoloader([
        'JoJoBizzareCoders\\DigitalJournal\\' => __DIR__ . '/../src/',
        'JoJoBizzareCoders\\DigitalJournalTest\\' => __DIR__ . 'UnitTest.php/',
    ])
);


/**
 * Тестирование приложений
 */
class UnitTest
{
    //Методы
    /**
     * Провайдер данных для тестов
     *
     * @return array
     */
    private static function testDataProvider(): array
    {
        $diConfig = require __DIR__ . '/../config/dev/di.php';

        $diConfig['services'][LoggerInterface::class] = [
            'class' => Logger::class
        ];
        $diConfig['services'][RenderInterface::class] = [
            'class' => NullRender::class
        ];

        return [
            // Тесты первого сценария
            // Тесты поиска
            [
                'testName' => 'Тестирование возможности смотреть расписание по названию предмета',
                'in' => [
                    'uri' => '/lesson?item_name=Математика',
                    'diConfig' => $diConfig
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 8:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 3,
                                'number' => 6,
                                'letter' => 'А'
                            ]
                        ],
                        [
                            'id' => 2,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 10:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 1,
                                'number' => 4,
                                'letter' => 'Б'
                            ]
                        ],
                        [
                            'id' => 3,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 11:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 2,
                                'number' => 3,
                                'letter' => 'А'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование возможности смотреть расписание по рассшифровке предмета',
                'in' => [
                    'uri' => '/lesson?item_description=Математика',
                    'diConfig' => $diConfig
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 8:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 3,
                                'number' => 6,
                                'letter' => 'А'
                            ]
                        ],
                        [
                            'id' => 2,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 10:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 1,
                                'number' => 4,
                                'letter' => 'Б'
                            ]
                        ],
                        [
                            'id' => 3,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 11:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 2,
                                'number' => 3,
                                'letter' => 'А'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование возможности смотреть расписание по дате',
                'in' => [
                    'uri' => '/lesson?lesson_date=2011.11.10 8:30',
                    'diConfig' => $diConfig
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 8:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 3,
                                'number' => 6,
                                'letter' => 'А'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование возможности смотреть расписание по Фамилии преподавателя',
                'in' => [
                    'uri' => '/lesson?teacher_fio_surname=Круглова',
                    'diConfig' => $diConfig
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 8:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 3,
                                'number' => 6,
                                'letter' => 'А'
                            ]
                        ],
                        [
                            'id' => 2,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 10:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 1,
                                'number' => 4,
                                'letter' => 'Б'
                            ]
                        ],
                        [
                            'id' => 3,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 11:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 2,
                                'number' => 3,
                                'letter' => 'А'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование возможности смотреть расписание по имени преподавателя',
                'in' => [
                    'uri' => '/lesson?teacher_fio_name=Наталия',
                    'diConfig' => $diConfig
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 8:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 3,
                                'number' => 6,
                                'letter' => 'А'
                            ]
                        ],
                        [
                            'id' => 2,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 10:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 1,
                                'number' => 4,
                                'letter' => 'Б'
                            ]
                        ],
                        [
                            'id' => 3,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 11:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 2,
                                'number' => 3,
                                'letter' => 'А'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование возможности смотреть расписание по отчеству преподавателя',
                'in' => [
                    'uri' => '/lesson?teacher_fio_patronymic=Сергеевна',
                    'diConfig' => $diConfig
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 8:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 3,
                                'number' => 6,
                                'letter' => 'А'
                            ]
                        ],
                        [
                            'id' => 2,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 10:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 1,
                                'number' => 4,
                                'letter' => 'Б'
                            ]
                        ],
                        [
                            'id' => 3,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 11:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 2,
                                'number' => 3,
                                'letter' => 'А'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование возможности смотреть расписание по кабинету преподавателя',
                'in' => [
                    'uri' => '/lesson?teacher_cabinet=56',
                    'diConfig' => $diConfig
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 8:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 3,
                                'number' => 6,
                                'letter' => 'А'
                            ]
                        ],
                        [
                            'id' => 2,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 10:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 1,
                                'number' => 4,
                                'letter' => 'Б'
                            ]
                        ],
                        [
                            'id' => 3,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 11:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 2,
                                'number' => 3,
                                'letter' => 'А'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование возможности смотреть расписание по номеру класса',
                'in' => [
                    'uri' => '/lesson?class_number=6',
                    'diConfig' => $diConfig
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 8:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 3,
                                'number' => 6,
                                'letter' => 'А'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование возможности смотреть расписание по букве класса',
                'in' => [
                    'uri' => '/lesson?class_letter=А',
                    'diConfig' => $diConfig
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 8:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 3,
                                'number' => 6,
                                'letter' => 'А'
                            ]
                        ],
                        [
                            'id' => 3,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 11:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 2,
                                'number' => 3,
                                'letter' => 'А'
                            ]
                        ],
                        [
                            'id' => 5,
                            'item' => [
                                'id' => 2,
                                'name' => 'ОБЖ',
                                'description' => 'Основы безопасности жизнедеятельности'
                            ],
                            'date' => '2011.11.11 8:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 2,
                                'fio' => [
                                    'surname' => 'Гусева',
                                    'name' => 'Анна',
                                    'patronymic' => 'Владимировна'
                                ],
                                'phone' => '+79133243412',
                                'dateOfBirth' => '1975.11.01',
                                'address' => [
                                    'street' => 'ул. Зеленская',
                                    'home' => 'д. 22',
                                    'apartment' => 'кв. 11'
                                ],
                                'item' => [
                                    'id' => 2,
                                    'name' => 'ОБЖ',
                                    'description' => 'Основы безопасности жизнедеятельности'
                                ],
                                'cabinet' => 77,
                                'email' => 'guseva@gmail.com'
                            ],
                            'class' => [
                                'id' => 2,
                                'number' => 3,
                                'letter' => 'А'
                            ]
                        ],
                    ]
                ]
            ],

            // Тесты с некорректными запросом поиска
            [
                'testName' => 'Тестирование неподдерживаемого запроса',
                'in' => [
                    'uri' => '/hhh?param=ru',
                    'diConfig' => $diConfig
                ],
                'out' => [
                    'httpCode' => 404,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'unsupported request'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование некорреткного ввода названия предмета',
                'in' => [
                    'uri' => '/lesson?item_name[]=Математика',
                    'diConfig' => $diConfig
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Incorrect item name'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование некорреткного ввода рассшифровки предмета',
                'in' => [
                    'uri' => '/lesson?item_description[]=Математика',
                    'diConfig' => $diConfig
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Incorrect item description'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование некорреткного ввода даты занятия',
                'in' => [
                    'uri' => '/lesson?lesson_date[]=2013.11.10 8:30',
                    'diConfig' => $diConfig
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Incorrect date'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование некорреткного ввода fio преподавателя',
                'in' => [
                    'uri' => '/lesson?teacher_fio[]=Круглова Наталия Сергеевна',
                    'diConfig' => $diConfig
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Incorrect teacher fio'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование некорреткного ввода кабинета преподавателя',
                'in' => [
                    'uri' => '/lesson?teacher_cabinet[]=56',
                    'diConfig' => $diConfig
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Incorrect teacher cabinet'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование некорреткного ввода номера класса',
                'in' => [
                    'uri' => '/lesson?class_number[]=6',
                    'diConfig' => $diConfig
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Incorrect class number'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование некорреткного ввода буквы класса',
                'in' => [
                    'uri' => '/lesson?class_letter[]=А',
                    'diConfig' => $diConfig
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Incorrect class letter'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование запроса без path',
                'in' => [
                    'uri' => '/?param=ru',
                    'diConfig' => $diConfig
                ],
                'out' => [
                    'httpCode' => 404,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'unsupported request'
                    ]
                ]
            ],


            // Тесты 2 и 4 сценария
            // Тесты поиска
            [
                'testName' => 'Тестирование возможности смотреть оценку по названию предмета',
                'in' => [
                    'uri' => '/assessmentReport?item_name=Математика',
                    'diConfig' => $diConfig
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
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Круглова',
                                        'name' => 'Наталия',
                                        'patronymic' => 'Сергеевна'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => 'ул. Ясная',
                                        'home' => 'д. 54',
                                        'apartment' => 'кв. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => 'Математика',
                                        'description' => 'Математика'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => 'А'
                                ],
                            ],
                            'student' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Кузнецов',
                                    'name' => 'Алексей',
                                    'patronymic' => 'Евгеньевич'
                                ],
                                'dateOfBirth' => '2011.01.11',
                                'phone' => '+79222444488',
                                'address' => [
                                    'street' => 'ул. Казанская',
                                    'home' => 'д. 35Б',
                                    'apartment' => 'кв. 23'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                                'parent' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Кузнецов',
                                        'name' => 'Евгений',
                                        'patronymic' => 'Сергеевич'
                                    ],
                                    'dateOfBirth' => '1975.10.01',
                                    'phone' => '+79222444488',
                                    'address' => [
                                        'street' => 'ул. Казанская',
                                        'home' => 'д. 35Б',
                                        'apartment' => 'кв. 23'
                                    ],
                                    'placeOfWork' => 'ООО Алмаз',
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
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Круглова',
                                        'name' => 'Наталия',
                                        'patronymic' => 'Сергеевна'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => 'ул. Ясная',
                                        'home' => 'д. 54',
                                        'apartment' => 'кв. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => 'Математика',
                                        'description' => 'Математика'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => 'А'
                                ],
                            ],
                            'student' => [
                                'id' => 7,
                                'fio' => [
                                    'surname' => 'Крабов',
                                    'name' => 'Владимир',
                                    'patronymic' => 'Юрьевич'
                                ],
                                'dateOfBirth' => '2009.04.23',
                                'phone' => '+79888444488',
                                'address' => [
                                    'street' => 'ул. Новая',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 22'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => 'А'
                                ],
                                'parent' => [
                                    'id' => 2,
                                    'fio' => [
                                        'surname' => 'Крабов',
                                        'name' => 'Юрий',
                                        'patronymic' => 'Владимирович'
                                    ],
                                    'phone' => '+79888444488',
                                    'dateOfBirth' => '1985.11.10',
                                    'address' => [
                                        'street' => 'ул. Новая',
                                        'home' => 'д. 54',
                                        'apartment' => 'кв. 22'
                                    ],
                                    'placeOfWork' => 'ООО Весна',
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
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'date' => '2011.11.10 10:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Круглова',
                                        'name' => 'Наталия',
                                        'patronymic' => 'Сергеевна'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => 'ул. Ясная',
                                        'home' => 'д. 54',
                                        'apartment' => 'кв. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => 'Математика',
                                        'description' => 'Математика'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                            ],
                            'student' => [
                                'id' => 2,
                                'fio' => [
                                    'surname' => 'Соколова',
                                    'name' => 'Алла',
                                    'patronymic' => 'Юрьевна'
                                ],
                                'dateOfBirth' => '2011.01.12',
                                'phone' => '+79222433488',
                                'address' => [
                                    'street' => 'ул. Зеленская',
                                    'home' => 'д. 47',
                                    'apartment' => 'кв. 34'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                                'parent' => [
                                    'id' => 8,
                                    'fio' => [
                                        'surname' => 'Соколова',
                                        'name' => 'Лидия',
                                        'patronymic' => 'Михайловна'
                                    ],
                                    'dateOfBirth' => '1985.01.11',
                                    'phone' => '+79222433488',
                                    'address' => [
                                        'street' => 'ул. Зеленская',
                                        'home' => 'д. 47',
                                        'apartment' => 'кв. 34'
                                    ],
                                    'placeOfWork' => 'ООО Тесты',
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
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Круглова',
                                        'name' => 'Наталия',
                                        'patronymic' => 'Сергеевна'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => 'ул. Ясная',
                                        'home' => 'д. 54',
                                        'apartment' => 'кв. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => 'Математика',
                                        'description' => 'Математика'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => 'А'
                                ],
                            ],
                            'student' => [
                                'id' => 5,
                                'fio' => [
                                    'surname' => 'Кузнецова',
                                    'name' => 'Анастасия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'dateOfBirth' => '2012.11.12',
                                'phone' => '+79223333388',
                                'address' => [
                                    'street' => 'ул. Грузовая',
                                    'home' => 'д. 45',
                                    'apartment' => 'кв. 45'
                                ],
                                'class' => [
                                    'id' => 2,
                                    'number' => 3,
                                    'letter' => 'А'
                                ],
                                'parent' => [
                                    'id' => 6,
                                    'fio' => [
                                        'surname' => 'Кузнецова',
                                        'name' => 'Наталия',
                                        'patronymic' => 'Михайловна'
                                    ],
                                    'phone' => '+79223333388',
                                    'dateOfBirth' => '1978.02.05',
                                    'address' => [
                                        'street' => 'ул. Грузовая',
                                        'home' => 'д. 45',
                                        'apartment' => 'кв. 45'
                                    ],
                                    'placeOfWork' => 'ИП Сергеев',
                                    'email' => 'kuznecova@gmail.com'
                                ],
                            ],
                            'mark' => 5
                        ]
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование поиска оценок в дневнике по расшифровке названия предмета',
                'in' => [
                    'uri' => '/assessmentReport?item_description=Математика',
                    'diConfig' => $diConfig
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
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Круглова',
                                        'name' => 'Наталия',
                                        'patronymic' => 'Сергеевна'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => 'ул. Ясная',
                                        'home' => 'д. 54',
                                        'apartment' => 'кв. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => 'Математика',
                                        'description' => 'Математика'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => 'А'
                                ],
                            ],
                            'student' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Кузнецов',
                                    'name' => 'Алексей',
                                    'patronymic' => 'Евгеньевич'
                                ],
                                'dateOfBirth' => '2011.01.11',
                                'phone' => '+79222444488',
                                'address' => [
                                    'street' => 'ул. Казанская',
                                    'home' => 'д. 35Б',
                                    'apartment' => 'кв. 23'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                                'parent' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Кузнецов',
                                        'name' => 'Евгений',
                                        'patronymic' => 'Сергеевич'
                                    ],
                                    'dateOfBirth' => '1975.10.01',
                                    'phone' => '+79222444488',
                                    'address' => [
                                        'street' => 'ул. Казанская',
                                        'home' => 'д. 35Б',
                                        'apartment' => 'кв. 23'
                                    ],
                                    'placeOfWork' => 'ООО Алмаз',
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
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Круглова',
                                        'name' => 'Наталия',
                                        'patronymic' => 'Сергеевна'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => 'ул. Ясная',
                                        'home' => 'д. 54',
                                        'apartment' => 'кв. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => 'Математика',
                                        'description' => 'Математика'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => 'А'
                                ],
                            ],
                            'student' => [
                                'id' => 7,
                                'fio' => [
                                    'surname' => 'Крабов',
                                    'name' => 'Владимир',
                                    'patronymic' => 'Юрьевич'
                                ],
                                'dateOfBirth' => '2009.04.23',
                                'phone' => '+79888444488',
                                'address' => [
                                    'street' => 'ул. Новая',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 22'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => 'А'
                                ],
                                'parent' => [
                                    'id' => 2,
                                    'fio' => [
                                        'surname' => 'Крабов',
                                        'name' => 'Юрий',
                                        'patronymic' => 'Владимирович'
                                    ],
                                    'phone' => '+79888444488',
                                    'dateOfBirth' => '1985.11.10',
                                    'address' => [
                                        'street' => 'ул. Новая',
                                        'home' => 'д. 54',
                                        'apartment' => 'кв. 22'
                                    ],
                                    'placeOfWork' => 'ООО Весна',
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
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'date' => '2011.11.10 10:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Круглова',
                                        'name' => 'Наталия',
                                        'patronymic' => 'Сергеевна'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => 'ул. Ясная',
                                        'home' => 'д. 54',
                                        'apartment' => 'кв. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => 'Математика',
                                        'description' => 'Математика'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                            ],
                            'student' => [
                                'id' => 2,
                                'fio' => [
                                    'surname' => 'Соколова',
                                    'name' => 'Алла',
                                    'patronymic' => 'Юрьевна'
                                ],
                                'dateOfBirth' => '2011.01.12',
                                'phone' => '+79222433488',
                                'address' => [
                                    'street' => 'ул. Зеленская',
                                    'home' => 'д. 47',
                                    'apartment' => 'кв. 34'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                                'parent' => [
                                    'id' => 8,
                                    'fio' => [
                                        'surname' => 'Соколова',
                                        'name' => 'Лидия',
                                        'patronymic' => 'Михайловна'
                                    ],
                                    'dateOfBirth' => '1985.01.11',
                                    'phone' => '+79222433488',
                                    'address' => [
                                        'street' => 'ул. Зеленская',
                                        'home' => 'д. 47',
                                        'apartment' => 'кв. 34'
                                    ],
                                    'placeOfWork' => 'ООО Тесты',
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
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Круглова',
                                        'name' => 'Наталия',
                                        'patronymic' => 'Сергеевна'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => 'ул. Ясная',
                                        'home' => 'д. 54',
                                        'apartment' => 'кв. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => 'Математика',
                                        'description' => 'Математика'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => 'А'
                                ],
                            ],
                            'student' => [
                                'id' => 5,
                                'fio' => [
                                    'surname' => 'Кузнецова',
                                    'name' => 'Анастасия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'dateOfBirth' => '2012.11.12',
                                'phone' => '+79223333388',
                                'address' => [
                                    'street' => 'ул. Грузовая',
                                    'home' => 'д. 45',
                                    'apartment' => 'кв. 45'
                                ],
                                'class' => [
                                    'id' => 2,
                                    'number' => 3,
                                    'letter' => 'А'
                                ],
                                'parent' => [
                                    'id' => 6,
                                    'fio' => [
                                        'surname' => 'Кузнецова',
                                        'name' => 'Наталия',
                                        'patronymic' => 'Михайловна'
                                    ],
                                    'phone' => '+79223333388',
                                    'dateOfBirth' => '1978.02.05',
                                    'address' => [
                                        'street' => 'ул. Грузовая',
                                        'home' => 'д. 45',
                                        'apartment' => 'кв. 45'
                                    ],
                                    'placeOfWork' => 'ИП Сергеев',
                                    'email' => 'kuznecova@gmail.com'
                                ],
                            ],
                            'mark' => 5
                        ]
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование поиска оценок в дневнике по дате проведения занятия',
                'in' => [
                    'uri' => '/assessmentReport?lesson_date=2011.11.10 8:30',
                    'diConfig' => $diConfig
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
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Круглова',
                                        'name' => 'Наталия',
                                        'patronymic' => 'Сергеевна'
                                    ],
                                    'phone' => '+79222444411',
                                    'dateOfBirth' => '1965.01.11',
                                    'address' => [
                                        'street' => 'ул. Ясная',
                                        'home' => 'д. 54',
                                        'apartment' => 'кв. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => 'Математика',
                                        'description' => 'Математика'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => 'А'
                                ],
                            ],
                            'student' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Кузнецов',
                                    'name' => 'Алексей',
                                    'patronymic' => 'Евгеньевич'
                                ],
                                'dateOfBirth' => '2011.01.11',
                                'phone' => '+79222444488',
                                'address' => [
                                    'street' => 'ул. Казанская',
                                    'home' => 'д. 35Б',
                                    'apartment' => 'кв. 23'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                                'parent' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Кузнецов',
                                        'name' => 'Евгений',
                                        'patronymic' => 'Сергеевич'
                                    ],
                                    'dateOfBirth' => '1975.10.01',
                                    'phone' => '+79222444488',
                                    'address' => [
                                        'street' => 'ул. Казанская',
                                        'home' => 'д. 35Б',
                                        'apartment' => 'кв. 23'
                                    ],
                                    'placeOfWork' => 'ООО Алмаз',
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
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Круглова',
                                        'name' => 'Наталия',
                                        'patronymic' => 'Сергеевна'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => 'ул. Ясная',
                                        'home' => 'д. 54',
                                        'apartment' => 'кв. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => 'Математика',
                                        'description' => 'Математика'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => 'А'
                                ],
                            ],
                            'student' => [
                                'id' => 7,
                                'fio' => [
                                    'surname' => 'Крабов',
                                    'name' => 'Владимир',
                                    'patronymic' => 'Юрьевич'
                                ],
                                'dateOfBirth' => '2009.04.23',
                                'phone' => '+79888444488',
                                'address' => [
                                    'street' => 'ул. Новая',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 22'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => 'А'
                                ],
                                'parent' => [
                                    'id' => 2,
                                    'fio' => [
                                        'surname' => 'Крабов',
                                        'name' => 'Юрий',
                                        'patronymic' => 'Владимирович'
                                    ],
                                    'dateOfBirth' => '1985.11.10',
                                    'phone' => '+79888444488',
                                    'address' => [
                                        'street' => 'ул. Новая',
                                        'home' => 'д. 54',
                                        'apartment' => 'кв. 22'
                                    ],
                                    'placeOfWork' => 'ООО Весна',
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
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Круглова',
                                        'name' => 'Наталия',
                                        'patronymic' => 'Сергеевна'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => 'ул. Ясная',
                                        'home' => 'д. 54',
                                        'apartment' => 'кв. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => 'Математика',
                                        'description' => 'Математика'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => 'А'
                                ],
                            ],
                            'student' => [
                                'id' => 5,
                                'fio' => [
                                    'surname' => 'Кузнецова',
                                    'name' => 'Анастасия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'dateOfBirth' => '2012.11.12',
                                'phone' => '+79223333388',
                                'address' => [
                                    'street' => 'ул. Грузовая',
                                    'home' => 'д. 45',
                                    'apartment' => 'кв. 45'
                                ],
                                'class' => [
                                    'id' => 2,
                                    'number' => 3,
                                    'letter' => 'А'
                                ],
                                'parent' => [
                                    'id' => 6,
                                    'fio' => [
                                        'surname' => 'Кузнецова',
                                        'name' => 'Наталия',
                                        'patronymic' => 'Михайловна'
                                    ],
                                    'dateOfBirth' => '1978.02.05',
                                    'phone' => '+79223333388',
                                    'address' => [
                                        'street' => 'ул. Грузовая',
                                        'home' => 'д. 45',
                                        'apartment' => 'кв. 45'
                                    ],
                                    'placeOfWork' => 'ИП Сергеев',
                                    'email' => 'kuznecova@gmail.com'
                                ],
                            ],
                            'mark' => 5
                        ]
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование поиска оценок в дневнике по Фамилия cтудента',
                'in' => [
                    'uri' => '/studentReport?student_fio_surname=Кузнецов',
                    'diConfig' => $diConfig
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
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Круглова',
                                        'name' => 'Наталия',
                                        'patronymic' => 'Сергеевна'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => 'ул. Ясная',
                                        'home' => 'д. 54',
                                        'apartment' => 'кв. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => 'Математика',
                                        'description' => 'Математика'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => 'А'
                                ],
                            ],
                            'student' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Кузнецов',
                                    'name' => 'Алексей',
                                    'patronymic' => 'Евгеньевич'
                                ],
                                'dateOfBirth' => '2011.01.11',
                                'phone' => '+79222444488',
                                'address' => [
                                    'street' => 'ул. Казанская',
                                    'home' => 'д. 35Б',
                                    'apartment' => 'кв. 23'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                                'parent' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Кузнецов',
                                        'name' => 'Евгений',
                                        'patronymic' => 'Сергеевич'
                                    ],
                                    'phone' => '+79222444488',
                                    'dateOfBirth' => '1975.10.01',
                                    'address' => [
                                        'street' => 'ул. Казанская',
                                        'home' => 'д. 35Б',
                                        'apartment' => 'кв. 23'
                                    ],
                                    'placeOfWork' => 'ООО Алмаз',
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
                                    'name' => 'Химия',
                                    'description' => 'Химия'
                                ],
                                'date' => '2011.11.11 10:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 3,
                                    'fio' => [
                                        'surname' => 'Дмитриев',
                                        'name' => 'Дмитрий',
                                        'patronymic' => 'Алексеевна'
                                    ],
                                    'phone' => '+79655346343',
                                    'dateOfBirth' => '1970.02.01',
                                    'address' => [
                                        'street' => 'ул. Круглова',
                                        'home' => 'д. 11',
                                        'apartment' => 'кв. 11'
                                    ],
                                    'item' => [
                                        'id' => 3,
                                        'name' => 'Химия',
                                        'description' => 'Химия'
                                    ],
                                    'cabinet' => 64,
                                    'email' => 'dmitriev@gmail.com'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                            ],
                            'student' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Кузнецов',
                                    'name' => 'Алексей',
                                    'patronymic' => 'Евгеньевич'
                                ],
                                'phone' => '+79222444488',
                                'dateOfBirth' => '2011.01.11',
                                'address' => [
                                    'street' => 'ул. Казанская',
                                    'home' => 'д. 35Б',
                                    'apartment' => 'кв. 23'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                                'parent' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Кузнецов',
                                        'name' => 'Евгений',
                                        'patronymic' => 'Сергеевич'
                                    ],
                                    'phone' => '+79222444488',
                                    'dateOfBirth' => '1975.10.01',
                                    'address' => [
                                        'street' => 'ул. Казанская',
                                        'home' => 'д. 35Б',
                                        'apartment' => 'кв. 23'
                                    ],
                                    'placeOfWork' => 'ООО Алмаз',
                                    'email' => 'kuznecov@gmail.com'
                                ],
                            ],
                            'mark' => 3
                        ]
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование поиска оценок в дневнике по Имени cтудента',
                'in' => [
                    'uri' => '/studentReport?student_fio_name=Алексей',
                    'diConfig' => $diConfig
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
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Круглова',
                                        'name' => 'Наталия',
                                        'patronymic' => 'Сергеевна'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => 'ул. Ясная',
                                        'home' => 'д. 54',
                                        'apartment' => 'кв. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => 'Математика',
                                        'description' => 'Математика'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => 'А'
                                ],
                            ],
                            'student' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Кузнецов',
                                    'name' => 'Алексей',
                                    'patronymic' => 'Евгеньевич'
                                ],
                                'dateOfBirth' => '2011.01.11',
                                'phone' => '+79222444488',
                                'address' => [
                                    'street' => 'ул. Казанская',
                                    'home' => 'д. 35Б',
                                    'apartment' => 'кв. 23'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                                'parent' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Кузнецов',
                                        'name' => 'Евгений',
                                        'patronymic' => 'Сергеевич'
                                    ],
                                    'phone' => '+79222444488',
                                    'dateOfBirth' => '1975.10.01',
                                    'address' => [
                                        'street' => 'ул. Казанская',
                                        'home' => 'д. 35Б',
                                        'apartment' => 'кв. 23'
                                    ],
                                    'placeOfWork' => 'ООО Алмаз',
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
                                    'name' => 'Химия',
                                    'description' => 'Химия'
                                ],
                                'date' => '2011.11.11 10:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 3,
                                    'fio' => [
                                        'surname' => 'Дмитриев',
                                        'name' => 'Дмитрий',
                                        'patronymic' => 'Алексеевна'
                                    ],
                                    'phone' => '+79655346343',
                                    'dateOfBirth' => '1970.02.01',
                                    'address' => [
                                        'street' => 'ул. Круглова',
                                        'home' => 'д. 11',
                                        'apartment' => 'кв. 11'
                                    ],
                                    'item' => [
                                        'id' => 3,
                                        'name' => 'Химия',
                                        'description' => 'Химия'
                                    ],
                                    'cabinet' => 64,
                                    'email' => 'dmitriev@gmail.com'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                            ],
                            'student' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Кузнецов',
                                    'name' => 'Алексей',
                                    'patronymic' => 'Евгеньевич'
                                ],
                                'phone' => '+79222444488',
                                'dateOfBirth' => '2011.01.11',
                                'address' => [
                                    'street' => 'ул. Казанская',
                                    'home' => 'д. 35Б',
                                    'apartment' => 'кв. 23'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                                'parent' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Кузнецов',
                                        'name' => 'Евгений',
                                        'patronymic' => 'Сергеевич'
                                    ],
                                    'phone' => '+79222444488',
                                    'dateOfBirth' => '1975.10.01',
                                    'address' => [
                                        'street' => 'ул. Казанская',
                                        'home' => 'д. 35Б',
                                        'apartment' => 'кв. 23'
                                    ],
                                    'placeOfWork' => 'ООО Алмаз',
                                    'email' => 'kuznecov@gmail.com'
                                ],
                            ],
                            'mark' => 3
                        ]
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование поиска оценок в дневнике по отчеству cтудента',
                'in' => [
                    'uri' => '/studentReport?student_fio_patronymic=Евгеньевич',
                    'diConfig' => $diConfig
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
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Круглова',
                                        'name' => 'Наталия',
                                        'patronymic' => 'Сергеевна'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => 'ул. Ясная',
                                        'home' => 'д. 54',
                                        'apartment' => 'кв. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => 'Математика',
                                        'description' => 'Математика'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => 'А'
                                ],
                            ],
                            'student' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Кузнецов',
                                    'name' => 'Алексей',
                                    'patronymic' => 'Евгеньевич'
                                ],
                                'dateOfBirth' => '2011.01.11',
                                'phone' => '+79222444488',
                                'address' => [
                                    'street' => 'ул. Казанская',
                                    'home' => 'д. 35Б',
                                    'apartment' => 'кв. 23'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                                'parent' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Кузнецов',
                                        'name' => 'Евгений',
                                        'patronymic' => 'Сергеевич'
                                    ],
                                    'phone' => '+79222444488',
                                    'dateOfBirth' => '1975.10.01',
                                    'address' => [
                                        'street' => 'ул. Казанская',
                                        'home' => 'д. 35Б',
                                        'apartment' => 'кв. 23'
                                    ],
                                    'placeOfWork' => 'ООО Алмаз',
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
                                    'name' => 'Химия',
                                    'description' => 'Химия'
                                ],
                                'date' => '2011.11.11 10:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 3,
                                    'fio' => [
                                        'surname' => 'Дмитриев',
                                        'name' => 'Дмитрий',
                                        'patronymic' => 'Алексеевна'
                                    ],
                                    'phone' => '+79655346343',
                                    'dateOfBirth' => '1970.02.01',
                                    'address' => [
                                        'street' => 'ул. Круглова',
                                        'home' => 'д. 11',
                                        'apartment' => 'кв. 11'
                                    ],
                                    'item' => [
                                        'id' => 3,
                                        'name' => 'Химия',
                                        'description' => 'Химия'
                                    ],
                                    'cabinet' => 64,
                                    'email' => 'dmitriev@gmail.com'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                            ],
                            'student' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Кузнецов',
                                    'name' => 'Алексей',
                                    'patronymic' => 'Евгеньевич'
                                ],
                                'phone' => '+79222444488',
                                'dateOfBirth' => '2011.01.11',
                                'address' => [
                                    'street' => 'ул. Казанская',
                                    'home' => 'д. 35Б',
                                    'apartment' => 'кв. 23'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                                'parent' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Кузнецов',
                                        'name' => 'Евгений',
                                        'patronymic' => 'Сергеевич'
                                    ],
                                    'phone' => '+79222444488',
                                    'dateOfBirth' => '1975.10.01',
                                    'address' => [
                                        'street' => 'ул. Казанская',
                                        'home' => 'д. 35Б',
                                        'apartment' => 'кв. 23'
                                    ],
                                    'placeOfWork' => 'ООО Алмаз',
                                    'email' => 'kuznecov@gmail.com'
                                ],
                            ],
                            'mark' => 3
                        ]
                    ]
                ]
            ],

            // Тесты с некорректными запросом поиска
            [
                'testName' => 'Тестирование некорреткного ввода названия предмета',
                'in' => [
                    'uri' => '/assessmentReport?item_name[]=Математика',
                    'diConfig' => $diConfig
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Incorrect item name'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование некорреткного ввода рассшифровки предмета',
                'in' => [
                    'uri' => '/studentReport?item_description[]=Математика',
                    'diConfig' => $diConfig
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Incorrect item description'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование некорреткного ввода даты занятия',
                'in' => [
                    'uri' => '/studentReport?lesson_date[]=2011.11.10 8:30',
                    'diConfig' => $diConfig
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Incorrect lesson date'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование некорреткного ввода ФИО cтудента',
                'in' => [
                    'uri' => '/studentReport?student_fio[]=Кузнецов Алексей Евгеньевич',
                    'diConfig' => $diConfig
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Incorrect student fio'
                    ]
                ]
            ],

            // Тесты данных и структур
            // Тесты с некорреткными данными
            [
                'testName' => 'Тестирование ситуации когда данные о занятии не корректны. Нет поля date',
                'in' => [
                    'uri' => '/lesson?item_name=Математика',
                    'diConfig' => (static function ($diConfig) {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToLesson'] = __DIR__ . '/data/broken.lesson.json';
                        $diConfig['instances']['appConfig'] = $config;
                        return $diConfig;
                    })(
                        $diConfig
                    )
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Отсутвуют обязательные элементы: date'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование ситуации когда данные о оценке не корректны. Нет поля mark',
                'in' => [
                    'uri' => '/assessmentReport?item_name=Математика',
                    'diConfig' => (static function ($diConfig) {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToAssessmentReport'] = __DIR__ . '/data/broken.assessmentReport.json';
                        $diConfig['instances']['appConfig'] = $config;
                        return $diConfig;
                    })(
                        $diConfig
                    )
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Отсутвуют обязательные элементы: mark'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование ситуации когда данные об предметах не корректны. Нет поля description',
                'in' => [
                    'uri' => '/lesson?item_name=Математика',
                    'diConfig' => (static function ($diConfig) {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToItems'] = __DIR__ . '/data/broken.item.json';
                        $diConfig['instances']['appConfig'] = $config;
                        return $diConfig;
                    })(
                        $diConfig
                    )
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Отсутвуют обязательные элементы: description'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование ситуации когда данные об классаха не корректны. Нет поля letter',
                'in' => [
                    'uri' => '/lesson?item_name=Математика',
                    'diConfig' => (static function ($diConfig) {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToClasses'] = __DIR__ . '/data/broken.class.json';
                        $diConfig['instances']['appConfig'] = $config;
                        return $diConfig;
                    })(
                        $diConfig
                    )
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Отсутвуют обязательные элементы: letter'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование ситуации когда данные об родителях не корректны. Нет поля email',
                'in' => [
                    'uri' => '/assessmentReport?item_name=Математика',
                    'diConfig' => (static function ($diConfig) {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToParents'] = __DIR__ . '/data/broken.parent.json';
                        $diConfig['instances']['appConfig'] = $config;
                        return $diConfig;
                    })(
                        $diConfig
                    )
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Отсутвуют обязательные элементы: email'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование ситуации когда данные об учениках не корректны. Нет поля address',
                'in' => [
                    'uri' => '/assessmentReport?item_name=Математика',
                    'diConfig' => (static function ($diConfig) {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToStudents'] = __DIR__ . '/data/broken.student.json';
                        $diConfig['instances']['appConfig'] = $config;
                        return $diConfig;
                    })(
                        $diConfig
                    )
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Нет данных о аддрессе'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование ситуации когда данные об учителях не корректны. Нет поля email',
                'in' => [
                    'uri' => '/assessmentReport?item_name=Математика',
                    'diConfig' => (static function ($diConfig) {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToTeachers'] = __DIR__ . '/data/broken.teacher.json';
                        $diConfig['instances']['appConfig'] = $config;
                        return $diConfig;
                    })(
                        $diConfig
                    )
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Отсутвуют обязательные элементы: email'
                    ]
                ]
            ],


            // Тесты с некорректными путями
            [
                'testName' => 'Тестирование ситуации c некрректным путём до файла с занятиями',
                'in' => [
                    'uri' => '/lesson?item_name=Математика',
                    'diConfig' => (static function ($diConfig) {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToLesson'] = __DIR__ . '/unknown.lesson.json';
                        $diConfig['instances']['appConfig'] = $config;
                        return $diConfig;
                    })(
                        $diConfig
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Неккоректный путь до файла с данными'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование ситуации c некрректным путём до файла с оценками',
                'in' => [
                    'uri' => '/assessmentReport?item_name=Математика',
                    'diConfig' => (static function ($diConfig) {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToAssessmentReport'] = __DIR__ . '/unknown.assessmentReport.json';
                        $diConfig['instances']['appConfig'] = $config;
                        return $diConfig;
                    })(
                        $diConfig
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Неккоректный путь до файла с данными'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование ситуации c некрректным путём до файла с классами',
                'in' => [
                    'uri' => '/assessmentReport?item_name=Математика',
                    'diConfig' => (static function ($diConfig) {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToClasses'] = __DIR__ . '/unknown.class.json';
                        $diConfig['instances']['appConfig'] = $config;
                        return $diConfig;
                    })(
                        $diConfig
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Неккоректный путь до файла с данными'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование ситуации c некрректным путём до файла с предметами',
                'in' => [
                    'uri' => '/assessmentReport?item_name=Математика',
                    'diConfig' => (static function ($diConfig) {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToItems'] = __DIR__ . '/unknown.Item.json';
                        $diConfig['instances']['appConfig'] = $config;
                        return $diConfig;
                    })(
                        $diConfig
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Неккоректный путь до файла с данными'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование ситуации c некрректным путём до файла с Родителями',
                'in' => [
                    'uri' => '/assessmentReport?item_name=Математика',
                    'diConfig' => (static function ($diConfig) {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToParents'] = __DIR__ . '/unknown.parent.json';
                        $diConfig['instances']['appConfig'] = $config;
                        return $diConfig;
                    })(
                        $diConfig
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Неккоректный путь до файла с данными'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование ситуации c некрректным путём до файла с Учениками',
                'in' => [
                    'uri' => '/assessmentReport?item_name=Математика',
                    'diConfig' => (static function ($diConfig) {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToStudents'] = __DIR__ . '/unknown.student.json';
                        $diConfig['instances']['appConfig'] = $config;
                        return $diConfig;
                    })(
                        $diConfig
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Неккоректный путь до файла с данными'
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование ситуации c некрректным путём до файла с Учителями',
                'in' => [
                    'uri' => '/assessmentReport?item_name=Математика',
                    'diConfig' => (static function ($diConfig) {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToTeachers'] = __DIR__ . '/unknown.teacher.json';
                        $diConfig['instances']['appConfig'] = $config;
                        return $diConfig;
                    })(
                        $diConfig
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Неккоректный путь до файла с данными'
                    ]
                ]
            ],

            // Тест с некорректным конфигом
            [
                'testName' => 'Тестирование ситуации когда нет Конфига',
                'in' => [
                    'uri' => '/lesson?item_name=Математика',
                    'diConfig' => (static function ($diConfig) {
                        $diConfig['instances']['appConfig'] = static function () {
                            return 'Oops';
                        };
                        return $diConfig;
                    })(
                        $diConfig
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'system error'
                    ]
                ]
            ],

            // Тесты с эхологгером
            /*[
                'testName' => 'Тестирование возможности смотреть расписание по названию предмета ЭхоЛоггер',
                'in' => [
                    'handlers'=>$handlers,
                    'uri'=>'/lesson?item_name=Математика',
                    'loggerFactory'=> '\JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\Factory::create',
                    'appConfigFactory'=>static function () {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['loggerType'] = 'echoLogger';
                        return AppConfig::createFromArray($config);
                    }
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 8:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => 'Круглова Наталия Сергеевна',
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => 'ул. Ясная, д. 54, кв. 19',
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 3,
                                'number' => 6,
                                'letter' => 'А'
                            ]
                        ],
                        [
                            'id' => 2,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 10:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => 'Круглова Наталия Сергеевна',
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => 'ул. Ясная, д. 54, кв. 19',
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 1,
                                'number' => 4,
                                'letter' => 'Б'
                            ]
                        ],
                        [
                            'id' => 3,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 11:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => 'Круглова Наталия Сергеевна',
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => 'ул. Ясная, д. 54, кв. 19',
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 2,
                                'number' => 3,
                                'letter' => 'А'
                            ]
                        ]
                    ]
                ]
            ],*/
        ];
    }

    /**
     *Запускает тест
     *
     * @return void
     * @throws JsonException
     */
    public static function runTest(): void
    {
        foreach (static::testDataProvider() as $testItem) {
            echo "__________{$testItem['testName']}__________\n";
            //Arrange
            $httpRequest = new ServerRequest(
                'GET',
                '1.1',
                $testItem['in']['uri'],
                Uri::createFromString($testItem['in']['uri']),
                ['Content-Type' => 'application/json'],
                null,
            );
            $diConfig = $testItem['in']['diConfig'];

            //Act
            $httpResponse = (new App(
                static function (Container $di): RouterInterface {
                    return $di->get(RouterInterface::class);
                },
                static function (Container $di): LoggerInterface {
                    return $di->get(LoggerInterface::class);
                },
                static function (Container $di): AppConfig {
                    return $di->get(AppConfig::class);
                },
                static function (Container $di): RenderInterface {
                    return $di->get(RenderInterface::class);
                },
                static function () use ($diConfig): Container {
                    return Container::createFromArray($diConfig);
                }

            ))->dispatch($httpRequest);

            // Assert
            if ($httpResponse->getStatusCode() === $testItem['out']['httpCode']) {
                echo "-----ok - код ответа-----\n";
            } else {
                echo "-----fail - код ответа. Ожидалось: {$testItem['out']['httpCode']}, Актуальное значение: {$httpResponse->getStatusCode()}-----\n";
            }

            $actualResult = json_decode($httpResponse->getBody(), true, 512, JSON_THROW_ON_ERROR);

            //Лишние Элементы
            $unnecessaryElements = TestUtils::arrayDiffAssocRecursive($actualResult, $testItem['out']['result']);
            //Недостоющие Элементы
            $missingElements = TestUtils::arrayDiffAssocRecursive($testItem['out']['result'], $actualResult);

            $errMsg = '';
            if (count($unnecessaryElements) > 0) {
                $errMsg .= sprintf(
                    "     Есть лишние элементы %s\n",
                    json_encode($unnecessaryElements, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE)
                );
            }
            if (count($missingElements) > 0) {
                $errMsg .= sprintf(
                    "     Есть недостоющие элементы %s\n",
                    json_encode($missingElements, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE)
                );
            }
            if ('' === $errMsg) {
                echo "     ok-данные ответа валидны\n";
            } else {
                echo "     Fail-данные ответа валидны\n" . $errMsg;
            }
        }
    }

}

//Вызов метода
try {
    UnitTest::runTest();
} catch (JsonException $e) {
}