<?php

namespace JoJoBizzareCoders\DigitalJournalTest\Infrastructure\Repository;

use JoJoBizzareCoders\DigitalJournal\Entity\ParentUserClass;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DataLoader\JsonDataLoader;
use JoJoBizzareCoders\DigitalJournal\Repository\ParentJsonRepository;
use JoJoBizzareCoders\DigitalJournal\ValueObject\Address;
use JoJoBizzareCoders\DigitalJournal\ValueObject\Fio;
use JsonException;
use PHPUnit\Framework\TestCase;

/**
 * Тестирование репризиториев
 */
class RepositoryTest extends TestCase
{
    /**
     * Поставщик данных для тестирования приложения
     *
     * @return array[]
     */
    public static function dataProvider(): array
    {
        return [
            'Тестирование возможности поиска в репризитории по критерию Login' => [
                'in' => [
                    'login' => 'p0'
                ],
                'out' => [
                    ParentUserClass::createFromArray(
                        [
                            'id' => 12,
                            'fio' => [
                                [
                                    'surname' => 'Кузнецов',
                                    'name' => 'Евгений',
                                    'patronymic' => 'Сергеевич'
                                ]
                            ],
                            'dateOfBirth' => '1975.10.01',
                            'phone' => '+79222444488',
                            'address' => [
                                [
                                    'street' => 'ул. Казанская',
                                    'home' => 'д. 35Б',
                                    'apartment' => 'кв. 23'
                                ]
                            ],
                            'placeOfWork' => 'ООО Алмаз',
                            'email' => 'kuznecov@gmail.com',
                            'login' => 'p0',
                            'password' => '$2y$10$r8roUvRU3isynrDpqkeOb.FazrHESXg.twAt1k1TCu2WzxKiLhQp.'
                        ]
                    )
                ]
            ]
        ];
    }

    /**
     * Запускает тест поиска в репризиторие родителей по Логину
     *
     * @dataProvider dataProvider
     * @param array $in - входные данные для теста
     * @param array $out - входные данные для проверки
     * @return void
     * @throws JsonException
     */
    public function testParentJsonRepositoryFindBy(array $in, array $out): void
    {
        //Arrange
        $ParentJsonRepository = new ParentJsonRepository(
            __DIR__ . '/../../../data/parent.json',
            new  JsonDataLoader()
        );
        //Action
        $actualParent = $ParentJsonRepository->findBy($in);
        //Assert
        $this->assertEquals(
            $out,
            $actualParent,
            'Некооретный результат поиска в Репризитории Родителей'
        );
    }
}
