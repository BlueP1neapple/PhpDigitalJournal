<?php

    require_once __DIR__ . '/../src/Infrastructure/AppConfig.php';
    require_once __DIR__ . '/../src/Infrastructure/application.php';

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
            return [
                [
                    'testName' => 'Тестирование возможности смотреть расписание по названию предмета',
                    'in' => [
                        $handlers,
                        '/lesson?item_name=Математика',
                        [
                            'item_name' => 'Математика'
                        ],
                        function () {
                        },
                        new AppConfig()
                    ],
                    'out' => [
                        'httpCode' => 200,
                        'result' =>  [
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
                    'testName' => 'Тестирование ситуации когда данные о занятии не корректны. Нет поля date',
                    'in' => [
                        $handlers,
                        '/lesson?item_name=Математика',
                        [
                            'item_name' => 'Математика'
                        ],
                        function () {
                        },
                        (function(){
                            $appConfig=new AppConfig();
                            $appConfig->setPathToLesson(__DIR__ . '/data/broken.lesson.json');
                            return $appConfig;
                        })()
                    ],
                    'out'=>[
                        'httpCode' => 500,
                        'result'=>[
                            'status'=>'fail',
                            'message'=>'Отсутвуют обязательные элементы: date'
                        ]
                    ]
                ]
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