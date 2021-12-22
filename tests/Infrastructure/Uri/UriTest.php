<?php

    namespace JoJoBizzareCoders\DigitalJournalTest\Infrastructure\Uri;

    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Autoloader;
    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Uri\Uri;

    require_once __DIR__ . '/../../../src/Infrastructure/Autoloader.php';
    spl_autoload_register(
        new Autoloader([
            'JoJoBizzareCoders\\DigitalJournal\\' => __DIR__ . '/../../../src/',
            'JoJoBizzareCoders\\DigitalJournalTest\\' => __DIR__ . '/../../../tests/',
        ])
    );

    /**
     * Тестирование uri
     */
    class UriTest
    {
        /**
         * Тестирование преобразования объекта Uri в строку
         *
         * @return void
         */
        public static function testUriToString(): void
        {
            echo "----------Тестирование преобразования объекта Uri в строку----------\n";

            //Arrange
            $expected = 'http://and:mypassword@htmlbook.ru:80/samhtml/ssylki/absolyutnye-i-otnositelnye-ssylki?query=value1#fragment-example';
            $uri = new Uri(
                'http',
                'and:mypassword',
                'htmlbook.ru',
                '80',
                '/samhtml/ssylki/absolyutnye-i-otnositelnye-ssylki',
                'query=value1',
                'fragment-example'
            );

            //Act
            $actualUriString = (string)$uri;

            //Assert
            if ($expected === $actualUriString) {
                echo "     OK - объект uri корректно преобразован в строку\n";
            } else {
                echo "     Fail - объект uri некорректно преобразован в строку. Ожидалось $expected. Актуальное значение: $actualUriString\n";
            }
        }

        /**
         * Тестирование создания объекта uri из строки
         *
         * @return void
         */
        public static function testCreateFromString(): void
        {
            echo "----------Тестирование создания объекта uri из строки----------\n";

            //Arrange
            $expected = 'http://and:mypassword@htmlbook.ru:80/samhtml/ssylki/absolyutnye-i-otnositelnye-ssylki?query=value1#fragment-example';

            //Act
            $uri = Uri::createFromString($expected);
            $actualUriString = (string)$uri;

            //Assert
            if ($expected === $actualUriString) {
                echo "     OK - объект uri корректно создан из строки\n";
            } else {
                echo "     Fail - объект uri некорректно создан из строки. Ожидалось $expected. Актуальное значение: $actualUriString\n";
            }
        }
    }


    UriTest::testUriToString();
    UriTest::testCreateFromString();