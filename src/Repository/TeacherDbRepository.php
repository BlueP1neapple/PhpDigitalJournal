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
        'id' => 't.id',
        'login' => 't.login'
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

    private const BASE_SEARCH_SQL = <<<EOF
SELECT t.id            AS teacher_id,
       t.surname       AS teacher_surname,
       t.name          AS teacher_name,
       t.patronymic    AS teacher_patronymic,
       t.date_of_birth AS teacher_date_of_birth,
       t.phone         AS teacher_phone,
       t.street        AS teacher_street,
       t.home          AS teacher_home,
       t.apartment     AS teacher_apartment,
       i.id            AS item_id,
       i.name          AS item_name,
       i.description   AS item_description,
       t.cabinet       AS teacher_cabinet,
       t.email         AS teacher_email,
       t.login         AS teacher_login,
       t.password      AS teacher_password
FROM users_teachers AS t
         LEFT JOIN item AS i ON t.item_id = i.id
EOF;


    /**
     * @inheritDoc
     */
    public function findBy(array $criteria): array
    {
        $teacherData = $this->loadData($criteria);
        return $this->buildTeacherEntities($teacherData);

    }

    /**
     * Формитование массива сущьностей учителей
     *
     * @param array $data
     * @return TeacherUserClass[] - массив сущностей учителей
     */
    private function buildTeacherEntities(array $data): array
    {
        $teacherData = [];
        foreach ($data as $row) {
            $teacherId = $row['teacher_id'];
            $itemId = $row['item_id'];
            if (false === array_key_exists($teacherId, $teacherData)) {
                $dateOfbirth = DateTimeImmutable::createFromFormat('Y-m-d', $row['teacher_date_of_birth']);
                $teacherData[$teacherId] = [
                    'id' => $teacherId,
                    'fio' => [],
                    'dateOfBirth' => $dateOfbirth,
                    'phone' => $row['teacher_phone'],
                    'address' => [],
                    'idItem' => [],
                    'cabinet' => $row['teacher_cabinet'],
                    'email' => $row['teacher_email'],
                    'login' => $row['teacher_login'],
                    'password' => $row['teacher_password']
                ];
            }
            $fioArray = [
                'surname' => $row['teacher_surname'],
                'name' => $row['teacher_name'],
                'patronymic' => $row['teacher_patronymic']
            ];
            $addressArray = [
                'street' => $row['teacher_street'],
                'home' => $row['teacher_home'],
                'apartment' => $row['teacher_apartment']
            ];
            $teacherData[$teacherId]['fio'] = $this->createFioArray($fioArray);
            $teacherData[$teacherId]['address'] = $this->createAddress($addressArray);
            $itemData = [
                'id' => $row['item_id'],
                'name' => $row['item_name'],
                'description' => $row['item_description']
            ];
            $item = ItemClass::createFromArray($itemData);
            $teacherData[$teacherId]['idItem'] = $item;
        }
        $teacherEntities = [];
        foreach ($teacherData as $item) {
            $teacherEntities[] = TeacherUserClass::createFromArray($item);
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

    private function loadData(array $criteria)
    {
        $sql = self::BASE_SEARCH_SQL;
        $whereParts = [];
        $params = [];
        $notSupportedSearchCriteria = [];
        foreach ($criteria as $criteriaName => $criteriaValue) {
            if (array_key_exists($criteriaName, self::ALLOWED_CRITERIA)) {
                $sqlParts = self::ALLOWED_CRITERIA[$criteriaName];
                $whereParts[] = "$sqlParts=:$criteriaName";
                $params[$criteriaName] = $criteriaValue;
            } else {
                $notSupportedSearchCriteria[] = $criteriaName;
            }
        }
        if (count($notSupportedSearchCriteria) > 0) {
            $errMsg = 'Неподдерживаемые критерии поиска учителей'
                . implode(', ', $notSupportedSearchCriteria);
            throw new RuntimeException($errMsg);
        }
        if (count($whereParts) > 0) {
            $sql .= ' WHERE ' . implode(' AND ', $whereParts);
        }
        $statement = $this->connection->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll();

    }
}
