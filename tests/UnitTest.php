<?php

use JoJoBizzareCoders\DigitalJournal\Infrastructure\AppConfig;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\LoggerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\NullLogger\Logger;
use function JoJoBizzareCoders\DigitalJournal\Infrastructure\app;

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
                //TODO доделать Unittest
                [
                    'testName' => 'Тестирование возможности смотреть расписание по рассшифровке предмета',
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
                                        'id'=>1,
                                        'name'=>'Математика',
                                        'description'=>'Математика'
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
                                'student'=>[
                                    'id'=>1,
                                    'fio'=>'Кузнецов Алексей Евгеньевич',
                                    'phone'=>'+79222444488',
                                    'dateOfBirth'=>'2011.01.11',
                                    'address'=>'ул. Казанская, д. 35Б, кв. 23',
                                    'class'=>[
                                        'id'=>1,
                                        'number'=>4,
                                        'letter'=>'Б'
                                    ],
                                    'parent'=>[
                                        'id'=>1,
                                        'fio'=>'Кузнецов Евгений Сергеевич',
                                        'phone'=>'+79222444488',
                                        'dateOfBirth'=>'1975.10.01',
                                        'address'=>'ул. Казанская, д. 35Б, кв. 23',
                                        'placeOfWork'=>'ООО Алмаз',
                                        'email'=>'kuznecov@gmail.com'
                                    ],
                                ],
                                'mark'=>5
                            ],
                            [
                                'id' => 2,
                                'lesson' => [
                                    'id' => 1,
                                    'item' => [
                                        'id'=>1,
                                        'name'=>'Математика',
                                        'description'=>'Математика'
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
                                'student'=>[
                                    'id'=>7,
                                    'fio'=>'Крабов Владимир Юрьевич',
                                    'phone'=>'+79888444488',
                                    'dateOfBirth'=>'2009.04.23',
                                    'address'=>'ул. Новая, д. 54, кв. 22',
                                    'class'=>[
                                        'id'=>3,
                                        'number'=>6,
                                        'letter'=>'А'
                                    ],
                                    'parent'=>[
                                        'id'=>2,
                                        'fio'=>'Крабов Юрий Владимирович',
                                        'phone'=>'+79888444488',
                                        'dateOfBirth'=>'1985.11.10',
                                        'address'=>'ул. Новая, д. 54, кв. 22',
                                        'placeOfWork'=>'ООО Весна',
                                        'email'=>'krabov@gmail.com'
                                    ],
                                ],
                                'mark'=>4
                            ],
                            [
                                'id' => 4,
                                'lesson' => [
                                    'id' => 2,
                                    'item' => [
                                        'id'=>1,
                                        'name'=>'Математика',
                                        'description'=>'Математика'
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
                                'student'=>[
                                    'id'=>2,
                                    'fio'=>'Соколова Алла Юрьевна',
                                    'phone'=>'+79222433488',
                                    'dateOfBirth'=>'2011.01.12',
                                    'address'=>'ул. Зеленская, д. 47, кв. 34',
                                    'class'=>[
                                        'id'=>1,
                                        'number'=>4,
                                        'letter'=>'Б'
                                    ],
                                    'parent'=>[
                                        'id'=>8,
                                        'fio'=>'Соколова Лидия Михайловна',
                                        'phone'=>'+79222433488',
                                        'dateOfBirth'=>'1985.01.11',
                                        'address'=>'ул. Зеленская, д. 47, кв. 34',
                                        'placeOfWork'=>'ООО Тесты',
                                        'email'=>'sokolova@gmail.com'
                                    ],
                                ],
                                'mark'=>4
                            ],
                            [
                                'id' => 8,
                                'lesson' => [
                                    'id' => 1,
                                    'item' => [
                                        'id'=>1,
                                        'name'=>'Математика',
                                        'description'=>'Математика'
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
                                'student'=>[
                                    'id'=>5,
                                    'fio'=>'Кузнецова Анастасия Сергеевна',
                                    'phone'=>'+79223333388',
                                    'dateOfBirth'=>'2012.11.12',
                                    'address'=>'ул. Грузовая, д. 45, кв. 45',
                                    'class'=>[
                                        'id'=>2,
                                        'number'=>3,
                                        'letter'=>'А'
                                    ],
                                    'parent'=>[
                                        'id'=>6,
                                        'fio'=>'Кузнецова Наталия Михайловна',
                                        'phone'=>'+79223333388',
                                        'dateOfBirth'=>'1978.02.05',
                                        'address'=>'ул. Грузовая, д. 45, кв. 45',
                                        'placeOfWork'=>'ИП Сергеев',
                                        'email'=>'kuznecova@gmail.com'
                                    ],
                                ],
                                'mark'=>5
                            ]
                        ]
                    ]
                ],

                // Тесты с некорреткными данными
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

                // Тесты с некорректными путями
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