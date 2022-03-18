<?php

namespace JoJoBizzareCoders\DigitalJournal\Repository;

use DateTimeImmutable;
use JoJoBizzareCoders\DigitalJournal\Entity\ClassClass;
use JoJoBizzareCoders\DigitalJournal\Entity\ItemClass;
use JoJoBizzareCoders\DigitalJournal\Entity\LessonClass;
use JoJoBizzareCoders\DigitalJournal\Entity\LessonRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\TeacherUserClass;
use JoJoBizzareCoders\DigitalJournal\Exception\RuntimeException;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Db\ConnectionInterface;
use JoJoBizzareCoders\DigitalJournal\ValueObject\Address;
use JoJoBizzareCoders\DigitalJournal\ValueObject\Fio;

/**
 * Репризиторий для поиска занятий. В качестве хранилища используеться json файлы
 */
class LessonDbRepository implements LessonRepositoryInterface
{

    /**
     * Поддерживаемые критерии поиска
     */
    private const ALLOWED_CRITERIA = [
        'item_name' => 'i.name',
        'item_description' => 'i.description',
        'date' => 'l.date',
        'teacher_fio_surname' => 'u.surname',
        'teacher_fio_name' => 'u.name',
        'teacher_fio_patronymic' => 'u.patronymic',
        'teacher_cabinet' => 't.cabinet',
        'class_number' => 'c.number',
        'class_letter' => 'c.letter',
        'id' => 'l.id'
    ];

    /**
     * Базовый sql запрос для поиска занятий
     */
    private const BASE_SEARCH_SQL = <<<EOF
SELECT l.id              AS lesson_id,
       i.id              AS item_id,
       i.name            AS item_name,
       i.description     AS item_description,
       l.date            AS lesson_date,
       l.lesson_duration AS lesson_duration,
       u.id              AS teacher_id,
       u.surname         AS teacher_surname,
       u.name            AS teacher_name,
       u.patronymic      AS teacher_patronymic,
       u.date_of_birth   AS teacher_date_of_birth,
       u.phone           AS teacher_phone,
       u.street          AS teacher_street,
       u.home            AS teacher_home,
       u.apartment       AS teacher_apartment,
       i2.id             AS teacher_item_id,
       i2.name           AS teacher_item_name,
       i2.description    AS teacher_item_description,
       t.cabinet         AS teacher_cabinet,
       t.email           AS teacher_email,
       u.login           AS teacher_login,
       u.password        AS teacher_password,
       c.id              AS class_id,
       c.number          AS class_number,
       c.letter          AS class_letter
FROM lesson AS l
         
         LEFT JOIN item AS i ON l.item_id = i.id
         LEFT JOIN users AS u ON l.teacher_id = u.id
         join teachers as t on u.id = t.id
         LEFT JOIN item AS i2 ON t.item_id = i2.id
         LEFT JOIN class AS c ON l.class_id = c.id
EOF;

    /**
     * Компонент отвечающий за соединение с базой данных
     *
     * @var ConnectionInterface
     */
    private ConnectionInterface $connection;

    /**
     * @param ConnectionInterface $connection - компонент отвечающий за соединение с базой данных
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
        $lessonData = $this->loadData($criteria);
        return $this->buildLessonEntities($lessonData);
    }

    /**
     * @inheritDoc
     */
    public function add(LessonClass $entity): LessonClass
    {
        $sql = <<<EOF
INSERT INTO lesson (id, item_id, date, lesson_duration, teacher_id, class_id)
VALUES (
        :id, :item_id, :date, :lesson_duration, :teacher_id, :class_id
)
EOF;
        $values = [
            'id' => $entity->getId(),
            'item_id' => $entity->getItem()->getId(),
            'date' => $entity->getDate(),
            'lesson_duration' => $entity->getLessonDuration(),
            'teacher_id' => $entity->getTeacher()->getId(),
            'class_id' => $entity->getClass()->getId()
        ];
        $this->connection->prepare($sql)->execute($values);
        return $entity;
    }

    /**
     * Реализация логики загрузки данных о найденных по критериям поиска занятий из базы данных
     *
     * @param array $criteria - коллекция критериев поиска
     * @return array - коллекция данных о занятиях
     */
    private function loadData(array $criteria): array
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
            $errMsg = 'Неподдерживаемые критерии поиска текстовых документов'
                . implode(', ', $notSupportedSearchCriteria);
            throw new RuntimeException($errMsg);
        }
        if (count($whereParts) > 0) {
            $sql .= ' WHERE ' . implode(' AND ', $whereParts);
        }
        $sql .= "\n ORDER BY l.id ";
        $statement = $this->connection->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll();
    }

    /**
     * Реализация логики формирования коллекции сущностей занятий на основе данных полученных из базы данных
     *
     * @param array $data - коллекция данных о найденных по критериям поиска занятий
     * @return LessonClass[] - коллекция сущностей найденных занятий
     */
    private function buildLessonEntities(array $data): array
    {
        $lessonData = [];
        foreach ($data as $row) {
            $lessonId = $row['lesson_id'];
            $itemId = $row['item_id'];
            $teacherId = $row['teacher_id'];
            $teacherItemId = $row['teacher_item_id'];
            $classId = $row['class_id'];
            if (false === array_key_exists($lessonId, $lessonData)) {
                $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $row['lesson_date']);
                $lessonData[$lessonId] = [
                    'id' => $lessonId,
                    'date' => $date->format('Y.m.d G:i'),
                    'lessonDuration' => $row['lesson_duration'],
                    'teacher_id' => [],
                    'class_id' => []
                ];
            }
            $item = [
                'id' => $itemId,
                'name' => $row['item_name'],
                'description' => $row['item_description']
            ];
            $lessonData[$lessonId]['item_id'] = ItemClass::createFromArray($item);
            $dateOfBirth = DateTimeImmutable::createFromFormat('Y-m-d', $row['teacher_date_of_birth']);
            $teacher = [
                'id' => $teacherId,
                'dateOfBirth' => $dateOfBirth,
                'phone' => $row['teacher_phone'],
                'cabinet' => $row['teacher_cabinet'],
                'email' => $row['teacher_email'],
                'login' => $row['teacher_login'],
                'password' => $row['teacher_password']
            ];
            $teacherItem = [
                'id' => $teacherItemId,
                'name' => $row['teacher_item_name'],
                'description' => $row['teacher_item_description']
            ];
            $teacher['idItem'] = ItemClass::createFromArray($teacherItem);
            $teacherFio = [
                'surname' => $row['teacher_surname'],
                'name' => $row['teacher_name'],
                'patronymic' => $row['teacher_patronymic']
            ];
            $teacher['fio'] = $this->createFio($teacherFio);
            $teacherAddress = [
                'street' => $row['teacher_street'],
                'home' => $row['teacher_home'],
                'apartment' => $row['teacher_apartment']
            ];
            $teacher['address'] = $this->createAddress($teacherAddress);
            $lessonData[$lessonId]['teacher_id'] = TeacherUserClass::createFromArray($teacher);
            $class = [
                'id' => $classId,
                'number' => $row['class_number'],
                'letter' => $row['class_letter']
            ];
            $lessonData[$lessonId]['class_id'] = ClassClass::createFromArray($class);
        }
        $lessonEntities = [];
        foreach ($lessonData as $lesson) {
            $lessonEntities[] = LessonClass::createFromArray($lesson);
        }
        return $lessonEntities;
    }

    /**
     * Реализация логики формирования объекта значения ФИО
     *
     * @param array $fio - коллекция с фамилией, именем и отчеством пользователя
     * @return Fio[] - коллекция с полным именем пользователя
     */
    private function createFio(array $fio): array
    {
        return [
            new Fio(
                $fio['surname'],
                $fio['name'],
                $fio['patronymic']
            )
        ];
    }

    /**
     * Реализация логики формирования объектов значения адреса проживания пользователя
     *
     * @param array $address - коллекция данных об адресе проживания пользователя
     * @return Address[] - коллекция объектов значения адреса проживания пользователя
     */
    private function createAddress(array $address): array
    {
        return [
            new Address(
                $address['street'],
                $address['home'],
                $address['apartment']
            )
        ];
    }

    public function nextId(): int
    {
        $sql = <<<EOF
SELECT nextval('lesson_id_seq') AS next_id
EOF;
        return ((int)current($this->connection->query($sql)->fetchAll())['next_id']);

    }
}