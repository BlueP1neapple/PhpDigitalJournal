<?php

require_once __DIR__ . '/../src/Infrastructure/AppConfig.php';
require_once __DIR__ . '/../src/Infrastructure/application.php';
require_once __DIR__ . '/../src/Infrastructure/Logger/LoggerInterface.php';
require_once __DIR__ . '/../src/Infrastructure/Logger/NullLogger/Logger.php';
require_once __DIR__ . '/../src/Infrastructure/Logger/Factory.php';

/**
 * Вычисляет расхождение массивов с дополнительной проверкой индекса. Поддержка многомерных массивов
 *
 * @param array $a1
 * @param array $a2
 *
 * @return array
 */
function array_diff_assoc_recursive(array $a1, array $a2): array
{
    $result = [];
    foreach ($a1 as $k1 => $v1) {
        if (false === array_key_exists($k1, $a2)) {
            $result[$k1] = $v1;
            continue;
        }
        if (is_iterable($v1) && is_iterable($a2[$k1])) {
            $resultCheck = array_diff_assoc_recursive($v1, $a2[$k1]);
            if (count($resultCheck) > 0) {
                $result[$k1] = $resultCheck;
            }
            continue;
        }
        if ($v1 !== $a2[$k1]) {
            $result[$k1] = $v1;
        }
    }
    return $result;
}

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
        $handlers = include __DIR__ . '/../config/request.handlers.php';

        $loggerFactory = static function (): LoggerInterface {
            return new Logger();
        };

        return [
            // Тесты первого сценария
            // Тесты поиска
            [
                'testName' => 'Тестирование возможности смотреть расписание по названию предмета',
                'in' => [
                    $handlers,
                    '/lesson?item_name=Математика',
                    $loggerFactory,
                    static function () {
                        return AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');
                    },
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
            ],
            [
                'testName' => 'Тестирование возможности смотреть расписание по рассшифровке предмета',
                'in' => [
                    $handlers,
                    '/lesson?item_description=Математика',
                    $loggerFactory,
                    static function () {
                        return AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');
                    },
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
            ],
            [
                'testName' => 'Тестирование возможности смотреть расписание по дате',
                'in' => [
                    $handlers,
                    '/lesson?lesson_date=2011.11.10 8:30',
                    $loggerFactory,
                    static function () {
                        return AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');
                    },
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
                        ]
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование возможности смотреть расписание по fio преподавателя',
                'in' => [
                    $handlers,
                    '/lesson?teacher_fio=Круглова Наталия Сергеевна',
                    $loggerFactory,
                    static function () {
                        return AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');
                    },
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
            ],
            [
                'testName' => 'Тестирование возможности смотреть расписание по кабинету преподавателя',
                'in' => [
                    $handlers,
                    '/lesson?teacher_cabinet=56',
                    $loggerFactory,
                    static function () {
                        return AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');
                    },
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
            ],
            [
                'testName' => 'Тестирование возможности смотреть расписание по номеру класса',
                'in' => [
                    $handlers,
                    '/lesson?class_number=6',
                    $loggerFactory,
                    static function () {
                        return AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');
                    },
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
                        ]
                    ]
                ]
            ],
            [
                'testName' => 'Тестирование возможности смотреть расписание по букве класса',
                'in' => [
                    $handlers,
                    '/lesson?class_letter=А',
                    $loggerFactory,
                    static function () {
                        return AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');
                    },
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
                                'fio' => 'Гусева Анна Владимировна',
                                'phone' => '+79133243412',
                                'dateOfBirth' => '1975.11.01',
                                'address' => 'ул. Зеленская, д. 22, кв. 11',
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
            [
                'testName' => 'Тестирование возможности смотреть расписание по номеру и букве класса',
                'in' => [
                    $handlers,
                    '/lesson?class_number=6&class_letter=А',
                    $loggerFactory,
                    static function () {
                        return AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');
                    },
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
                        ]
                    ]
                ]
            ],

            // Тесты с некорректными запросом поиска
            [
                'testName' => 'Тестирование неподдерживаемого запроса',
                'in' => [
                    $handlers,
                    '/hhh?param=ru',
                    $loggerFactory,
                    static function () {
                        return AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');
                    },
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
                    $handlers,
                    '/lesson?item_name[]=Математика',
                    $loggerFactory,
                    static function () {
                        return AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');
                    },
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
                    $handlers,
                    '/lesson?item_description[]=Математика',
                    $loggerFactory,
                    static function () {
                        return AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');
                    },
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
                    $handlers,
                    '/lesson?lesson_date[]=2013.11.10 8:30',
                    $loggerFactory,
                    static function () {
                        return AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');
                    },
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
                    $handlers,
                    '/lesson?teacher_fio[]=Круглова Наталия Сергеевна',
                    $loggerFactory,
                    static function () {
                        return AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');
                    },
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
                    $handlers,
                    '/lesson?teacher_cabinet[]=56',
                    $loggerFactory,
                    static function () {
                        return AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');
                    },
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
                    $handlers,
                    '/lesson?class_number[]=6',
                    $loggerFactory,
                    static function () {
                        return AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');
                    },
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
                    $handlers,
                    '/lesson?class_letter[]=А',
                    $loggerFactory,
                    static function () {
                        return AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');
                    },
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
                    $handlers,
                    '/?param=ru',
                    $loggerFactory,
                    static function () {
                        return AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');
                    },
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
                    $handlers,
                    '/assessmentReport?item_name=Математика',
                    $loggerFactory,
                    static function () {
                        return AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');
                    },
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
                                ],
                            ],
                            'student' => [
                                'id' => 1,
                                'fio' => 'Кузнецов Алексей Евгеньевич',
                                'phone' => '+79222444488',
                                'dateOfBirth' => '2011.01.11',
                                'address' => 'ул. Казанская, д. 35Б, кв. 23',
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                                'parent' => [
                                    'id' => 1,
                                    'fio' => 'Кузнецов Евгений Сергеевич',
                                    'phone' => '+79222444488',
                                    'dateOfBirth' => '1975.10.01',
                                    'address' => 'ул. Казанская, д. 35Б, кв. 23',
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
                                ],
                            ],
                            'student' => [
                                'id' => 7,
                                'fio' => 'Крабов Владимир Юрьевич',
                                'phone' => '+79888444488',
                                'dateOfBirth' => '2009.04.23',
                                'address' => 'ул. Новая, д. 54, кв. 22',
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => 'А'
                                ],
                                'parent' => [
                                    'id' => 2,
                                    'fio' => 'Крабов Юрий Владимирович',
                                    'phone' => '+79888444488',
                                    'dateOfBirth' => '1985.11.10',
                                    'address' => 'ул. Новая, д. 54, кв. 22',
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
                                ],
                            ],
                            'student' => [
                                'id' => 2,
                                'fio' => 'Соколова Алла Юрьевна',
                                'phone' => '+79222433488',
                                'dateOfBirth' => '2011.01.12',
                                'address' => 'ул. Зеленская, д. 47, кв. 34',
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                                'parent' => [
                                    'id' => 8,
                                    'fio' => 'Соколова Лидия Михайловна',
                                    'phone' => '+79222433488',
                                    'dateOfBirth' => '1985.01.11',
                                    'address' => 'ул. Зеленская, д. 47, кв. 34',
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
                                ],
                            ],
                            'student' => [
                                'id' => 5,
                                'fio' => 'Кузнецова Анастасия Сергеевна',
                                'phone' => '+79223333388',
                                'dateOfBirth' => '2012.11.12',
                                'address' => 'ул. Грузовая, д. 45, кв. 45',
                                'class' => [
                                    'id' => 2,
                                    'number' => 3,
                                    'letter' => 'А'
                                ],
                                'parent' => [
                                    'id' => 6,
                                    'fio' => 'Кузнецова Наталия Михайловна',
                                    'phone' => '+79223333388',
                                    'dateOfBirth' => '1978.02.05',
                                    'address' => 'ул. Грузовая, д. 45, кв. 45',
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
                    $handlers,
                    '/assessmentReport?item_description=Математика',
                    $loggerFactory,
                    static function () {
                        return AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');
                    },
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
                                ],
                            ],
                            'student' => [
                                'id' => 1,
                                'fio' => 'Кузнецов Алексей Евгеньевич',
                                'phone' => '+79222444488',
                                'dateOfBirth' => '2011.01.11',
                                'address' => 'ул. Казанская, д. 35Б, кв. 23',
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                                'parent' => [
                                    'id' => 1,
                                    'fio' => 'Кузнецов Евгений Сергеевич',
                                    'phone' => '+79222444488',
                                    'dateOfBirth' => '1975.10.01',
                                    'address' => 'ул. Казанская, д. 35Б, кв. 23',
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
                                ],
                            ],
                            'student' => [
                                'id' => 7,
                                'fio' => 'Крабов Владимир Юрьевич',
                                'phone' => '+79888444488',
                                'dateOfBirth' => '2009.04.23',
                                'address' => 'ул. Новая, д. 54, кв. 22',
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => 'А'
                                ],
                                'parent' => [
                                    'id' => 2,
                                    'fio' => 'Крабов Юрий Владимирович',
                                    'phone' => '+79888444488',
                                    'dateOfBirth' => '1985.11.10',
                                    'address' => 'ул. Новая, д. 54, кв. 22',
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
                                ],
                            ],
                            'student' => [
                                'id' => 2,
                                'fio' => 'Соколова Алла Юрьевна',
                                'phone' => '+79222433488',
                                'dateOfBirth' => '2011.01.12',
                                'address' => 'ул. Зеленская, д. 47, кв. 34',
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                                'parent' => [
                                    'id' => 8,
                                    'fio' => 'Соколова Лидия Михайловна',
                                    'phone' => '+79222433488',
                                    'dateOfBirth' => '1985.01.11',
                                    'address' => 'ул. Зеленская, д. 47, кв. 34',
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
                                ],
                            ],
                            'student' => [
                                'id' => 5,
                                'fio' => 'Кузнецова Анастасия Сергеевна',
                                'phone' => '+79223333388',
                                'dateOfBirth' => '2012.11.12',
                                'address' => 'ул. Грузовая, д. 45, кв. 45',
                                'class' => [
                                    'id' => 2,
                                    'number' => 3,
                                    'letter' => 'А'
                                ],
                                'parent' => [
                                    'id' => 6,
                                    'fio' => 'Кузнецова Наталия Михайловна',
                                    'phone' => '+79223333388',
                                    'dateOfBirth' => '1978.02.05',
                                    'address' => 'ул. Грузовая, д. 45, кв. 45',
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
                    $handlers,
                    '/assessmentReport?lesson_date=2011.11.10 8:30',
                    $loggerFactory,
                    static function () {
                        return AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');
                    },
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
                                ],
                            ],
                            'student' => [
                                'id' => 1,
                                'fio' => 'Кузнецов Алексей Евгеньевич',
                                'phone' => '+79222444488',
                                'dateOfBirth' => '2011.01.11',
                                'address' => 'ул. Казанская, д. 35Б, кв. 23',
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                                'parent' => [
                                    'id' => 1,
                                    'fio' => 'Кузнецов Евгений Сергеевич',
                                    'phone' => '+79222444488',
                                    'dateOfBirth' => '1975.10.01',
                                    'address' => 'ул. Казанская, д. 35Б, кв. 23',
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
                                ],
                            ],
                            'student' => [
                                'id' => 7,
                                'fio' => 'Крабов Владимир Юрьевич',
                                'phone' => '+79888444488',
                                'dateOfBirth' => '2009.04.23',
                                'address' => 'ул. Новая, д. 54, кв. 22',
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => 'А'
                                ],
                                'parent' => [
                                    'id' => 2,
                                    'fio' => 'Крабов Юрий Владимирович',
                                    'phone' => '+79888444488',
                                    'dateOfBirth' => '1985.11.10',
                                    'address' => 'ул. Новая, д. 54, кв. 22',
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
                                ],
                            ],
                            'student' => [
                                'id' => 5,
                                'fio' => 'Кузнецова Анастасия Сергеевна',
                                'phone' => '+79223333388',
                                'dateOfBirth' => '2012.11.12',
                                'address' => 'ул. Грузовая, д. 45, кв. 45',
                                'class' => [
                                    'id' => 2,
                                    'number' => 3,
                                    'letter' => 'А'
                                ],
                                'parent' => [
                                    'id' => 6,
                                    'fio' => 'Кузнецова Наталия Михайловна',
                                    'phone' => '+79223333388',
                                    'dateOfBirth' => '1978.02.05',
                                    'address' => 'ул. Грузовая, д. 45, кв. 45',
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
                'testName' => 'Тестирование поиска оценок в дневнике по ФИО cтудента',
                'in' => [
                    $handlers,
                    '/assessmentReport?student_fio=Кузнецов Алексей Евгеньевич',
                    $loggerFactory,
                    static function () {
                        return AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');
                    },
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
                                ],
                            ],
                            'student' => [
                                'id' => 1,
                                'fio' => 'Кузнецов Алексей Евгеньевич',
                                'phone' => '+79222444488',
                                'dateOfBirth' => '2011.01.11',
                                'address' => 'ул. Казанская, д. 35Б, кв. 23',
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                                'parent' => [
                                    'id' => 1,
                                    'fio' => 'Кузнецов Евгений Сергеевич',
                                    'phone' => '+79222444488',
                                    'dateOfBirth' => '1975.10.01',
                                    'address' => 'ул. Казанская, д. 35Б, кв. 23',
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
                                    'fio' => 'Дмитриев Дмитрий Алексеевна',
                                    'phone' => '+79655346343',
                                    'dateOfBirth' => '1970.02.01',
                                    'address' => 'ул. Круглова, д. 11, кв. 11',
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
                                'fio' => 'Кузнецов Алексей Евгеньевич',
                                'phone' => '+79222444488',
                                'dateOfBirth' => '2011.01.11',
                                'address' => 'ул. Казанская, д. 35Б, кв. 23',
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                                'parent' => [
                                    'id' => 1,
                                    'fio' => 'Кузнецов Евгений Сергеевич',
                                    'phone' => '+79222444488',
                                    'dateOfBirth' => '1975.10.01',
                                    'address' => 'ул. Казанская, д. 35Б, кв. 23',
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
                    $handlers,
                    '/assessmentReport?item_name[]=Математика',
                    $loggerFactory,
                    static function () {
                        return AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');
                    },
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
                    $handlers,
                    '/studentReport?item_description[]=Математика',
                    $loggerFactory,
                    static function () {
                        return AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');
                    },
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
                    $handlers,
                    '/studentReport?lesson_date[]=2011.11.10 8:30',
                    $loggerFactory,
                    static function () {
                        return AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');
                    },
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
                    $handlers,
                    '/studentReport?student_fio[]=Кузнецов Алексей Евгеньевич',
                    $loggerFactory,
                    static function () {
                        return AppConfig::createFromArray(include __DIR__ . '/../config/dev/config.php');
                    },
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
                    $handlers,
                    '/lesson?item_name=Математика',
                    $loggerFactory,
                    static function () {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToLesson'] = __DIR__ . '/data/broken.lesson.json';
                        return AppConfig::createFromArray($config);
                    }
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
                    $handlers,
                    '/assessmentReport?item_name=Математика',
                    $loggerFactory,
                    static function () {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToAssessmentReport'] = __DIR__ . '/data/broken.assessmentReport.json';
                        return AppConfig::createFromArray($config);
                    }
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
                    $handlers,
                    '/lesson?item_name=Математика',
                    $loggerFactory,
                    static function () {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToItems'] = __DIR__ . '/data/broken.item.json';
                        return AppConfig::createFromArray($config);
                    }
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
                'testName' => 'Тестирование ситуации когда данные об предметах не корректны. Нет поля description',
                'in' => [
                    $handlers,
                    '/lesson?item_name=Математика',
                    $loggerFactory,
                    static function () {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToItems'] = __DIR__ . '/data/broken.item.json';
                        return AppConfig::createFromArray($config);
                    }
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Отсутвуют обязательные элементы: description'
                    ]
                ]
            ],

            // Тесты с некорректными путями
            [
                'testName' => 'Тестирование ситуации c некрректным путём до файла с занятиями',
                'in' => [
                    $handlers,
                    '/lesson?item_name=Математика',
                    $loggerFactory,
                    static function () {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToLesson'] = __DIR__ . '/unknown.lesson.json';
                        return AppConfig::createFromArray($config);
                    }
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
                    $handlers,
                    '/lesson?item_name=Математика',
                    $loggerFactory,
                    static function () {
                        $config = include __DIR__ . '/../config/dev/config.php';
                        $config['pathToAssessmentReport'] = __DIR__ . '/unknown.assessmentReport.json';
                        return AppConfig::createFromArray($config);
                    }
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
                    $handlers,
                    '/lesson?item_name=Математика',
                    $loggerFactory,
                    static function () {
                        return 'Oops';
                    }
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'incorrect application config'
                    ]
                ]
            ],
        ];
    }

    /**
     *Запускает тест
     *
     * @return void
     */
    public static function runTest(): void
    {
        foreach (static::testDataProvider() as $testItem) {
            echo "__________{$testItem['testName']}__________\n";
            //Arrange и Act
            $appResult = app(...$testItem['in']);

            // Assert
            if ($appResult['httpCode'] === $testItem['out']['httpCode']) {
                echo "-----ok - код ответа-----\n";
            } else {
                echo "-----fail - код ответа. Ожидалось: {$testItem['out']['httpCode']}, Актуальное значение: {$appResult['httpCode']}-----\n";
            }

            $actualResult = json_decode(json_encode($appResult['result']), true);

            //Лишние Элементы
            $unnecessaryElements = array_diff_assoc_recursive($actualResult, $testItem['out']['result']);
            //Недостоющие Элементы
            $missingElements = array_diff_assoc_recursive($testItem['out']['result'], $actualResult,);

            $errMsg = '';
            if (count($unnecessaryElements) > 0) {
                $errMsg .= sprintf(
                    "     Есть лишние элементы %s\n",
                    json_encode($unnecessaryElements, JSON_UNESCAPED_UNICODE)
                );
            }
            if (count($missingElements) > 0) {
                $errMsg .= sprintf(
                    "     Есть недостоющие элементы %s\n",
                    json_encode($missingElements, JSON_UNESCAPED_UNICODE)
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
UnitTest::runTest();