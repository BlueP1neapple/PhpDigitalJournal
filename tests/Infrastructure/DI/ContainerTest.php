<?php
    namespace JoJoBizzareCoders\DigitalJournalTest\Infrastructure\DI;
    use JoJoBizzareCoders\DigitalJournal\Controller\FoundAssessmentReport;
    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Autoloader;

    require_once __DIR__ . "/../../../src/Infrastructure/Autoloader.php";
    spl_autoload_register(
        new Autoloader([
            'JoJoBizzareCoders\\DigitalJournal\\' => __DIR__ . '/../src/',
            'JoJoBizzareCoders\\DigitalJournalTest\\' => __DIR__.'/',
        ])
    );

    /**
     * Тест проверяющий работу контейнера
     */
    class ContainerTest
    {
        /**
         * Тест возврата сервиса
         *
         * @return void
         */
        public static function testGetService():void
        {
            echo "----------Тестирование получения сервиса----------\n";
            //Arrange
            $diConfig=[
              'instances' =>[
                  'appConfig'=>require __DIR__ . '/../../../config/dev/config.php'
              ],
                'services'=>[
                    FoundAssessmentReport::class=>[
                        args=>[
                            'Logger' //TODO Доделать тест
                        ]
                    ]
                ]
            ];
        }
    }