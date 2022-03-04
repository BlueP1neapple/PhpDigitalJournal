<?php

namespace JoJoBizzareCoders\DigitalJournal\Repository;

use DateTimeImmutable;
use JoJoBizzareCoders\DigitalJournal\Entity\ItemClass;
use JoJoBizzareCoders\DigitalJournal\Entity\TeacherRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\TeacherUserClass;
use JoJoBizzareCoders\DigitalJournal\Exception\RuntimeException;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Db\ConnectionInterface;
use JoJoBizzareCoders\DigitalJournal\ValueObject\Address;
use JoJoBizzareCoders\DigitalJournal\ValueObject\Fio;

/**
 * Репозиторий учителей для работы с бд
 */
final class TeacherDbRepository implements TeacherRepositoryInterface
{
    /**
     *  Критерии поиска
     */
    private const ALLOWED_CRITERIA = [
        'id',
        'login'
    ];

    /**
     * Соединение с бд
     *
     * @var ConnectionInterface
     */
    private ConnectionInterface $connection;

    /**
     * @param ConnectionInterface $connection - соединение с бд
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @inheritDoc
     */
    public function findBy(array $criteria): array
    {
        $this->validate($criteria);
        $teacherData = $this->loadTeacherData($criteria);
        $itemEntity = $this->loadItemEntity($teacherData);
        return $this->buildTeacherEntities($teacherData, $itemEntity);
    }

    /**
     * Валидация критериев поиска
     *
     * @param array $criteria - массив критериев поиска
     * @return void
     */
    private function validate(array $criteria): void
    {
        $invalidCriteria = array_diff(array_keys($criteria), self::ALLOWED_CRITERIA);
        if (count($invalidCriteria) > 0) {
            $errMsg = 'Неподдерживаемые критерии поиска учителей: ' . implode(',', $invalidCriteria);
            throw new RuntimeException($errMsg);
        }
    }

    /**
     * Реализация логики загрузки коллекции данных о пользователях учителях
     *
     * @param array $criteria - коллекция критериев поиска
     * @return array - коллекция данных о пользователях учителях
     */
    private function loadTeacherData(array $criteria): array
    {
        $whereParts = [];
        $whereParams = [];
        $sql = <<<EOF
SELECT
       id,
       surname,
       name,
       patronymic,
       date_of_birth,
       phone,
       street,
       home,
       apartment,
       item_id,
       cabinet,
       email,
       login,
       password
FROM users_teachers
EOF;

        foreach ($criteria as $criteriaName => $criteriaValue) {
            $whereParts[] = "$criteriaName = :$criteriaName";
            $whereParams[$criteriaName] = $criteriaValue;
        }
        if (count($whereParts) > 0) {
            $sql .= ' WHERE ' . implode(' AND ', $whereParts);
        }
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($whereParams);
        return $stmt->fetchAll();
    }

    /**
     * Реализация логики загрузки сущностей предметов
     *
     * @param array $teacherData - коллекция данных о пользователях учителях
     * @return ItemClass[] - коллекция сущностей предметов
     */
    private function loadItemEntity(array $teacherData): array
    {
        $whereParts = [];
        $whereParams = [];
        $itemIdList = array_unique(
            array_map(
                static function (array $a) {
                    return $a['item_id'];
                },
                array_filter(
                    $teacherData,
                    static function (array $a) {
                        return isset($a['item_id']);
                    }
                )
            )
        );
        if (count($itemIdList) > 0) {
            $whereParams = array_combine(
                array_map(
                    static function (int $idx) {
                        return ':id_' . $idx;
                    },
                    range(
                        1,
                        count($itemIdList)
                    )
                ),
                $itemIdList
            );
            $whereParts[] = ' id IN (' . implode(', ', array_keys($whereParams)) . ')';
        }
        if (0 === count($whereParts)) {
            return [];
        }
        $sql = <<<EOF
SELECT
       id,
       name,
       description
FROM item
EOF;
        $sql .= ' WHERE ' . implode(' AND ', $whereParts);
        $smtp = $this->connection->prepare($sql);
        $smtp->execute($whereParams);
        $itemData = $smtp->fetchAll();
        $foundItems = [];
        foreach ($itemData as $itemItems) {
            $itemObj = ItemClass::createFromArray($itemItems);
            $foundItems[$itemObj->getId()] = $itemObj;
        }
        return $foundItems;
    }

    /**
     * Формитование массива сущьностей учителей
     *
     * @param array $teacherData - массив данных о пользователе учителя
     * @param array $itemEntity - массив сущностей предметов
     * @return TeacherUserClass[] - массив сущностей учителей
     */
    private function buildTeacherEntities(array $teacherData, array $itemEntity): array
    {
        $teacherEntities = [];
        foreach ($teacherData as $teacherItem) {
            $teacherItem['idItem'] = null === $teacherItem['item_id']
                ? null :
                $itemEntity[$teacherItem['item_id']];
            $teacherItem['fio'] = $this->createFioArray($teacherItem);
            $teacherItem['address'] = $this->createAddress($teacherItem);
            $dateOfBirthOfStudent = DateTimeImmutable::createFromFormat('Y-m-d', $teacherItem['date_of_birth']);
            $teacherItem['dateOfBirth'] = $dateOfBirthOfStudent->format('Y:m:d');
            $teacherEntities[] = TeacherUserClass::createFromArray($teacherItem);
        }
        return $teacherEntities;
    }

    /**
     * формирование fio
     *
     * @param array $userItem - массив данных фио
     * @return Fio[] - массив объектов значений с ФИО пользователя
     */
    private function createFioArray(array $userItem): array
    {
        $fio = [];
        $fio[] = new Fio(
            $userItem['surname'],
            $userItem['name'],
            $userItem['patronymic'],
        );
        return $fio;
    }

    /**
     * формированиеадресса
     *
     * @param array $userItem - массив данных с фамилией, именем и отчеством пользователя
     * @return Address[] - массив объектов значений с адресом проживания пользователя
     */
    private function createAddress(array $userItem): array
    {
        $address = [];
        $address[] = new Address(
            $userItem['street'],
            $userItem['home'],
            $userItem['apartment'],
        );
        return $address;
    }
}
