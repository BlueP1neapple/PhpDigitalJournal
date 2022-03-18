<?php

namespace JoJoBizzareCoders\DigitalJournal\Repository;

use JoJoBizzareCoders\DigitalJournal\Entity\ClassClass;
use JoJoBizzareCoders\DigitalJournal\Entity\ParentUserClass;
use JoJoBizzareCoders\DigitalJournal\Entity\StudentRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\StudentUserClass;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Db\ConnectionInterface;
use JoJoBizzareCoders\DigitalJournal\ValueObject\Address;
use JoJoBizzareCoders\DigitalJournal\ValueObject\Fio;

final class StudentDbRepository implements StudentRepositoryInterface
{

    /**
     * Разрешённые критери поиска
     */
    private const ALLOWED_CRITERIA = [
        'login' => 'student.login',
        'id' => 'student.id'
    ];

    /**
     * Соединение с бд
     *
     * @var ConnectionInterface
     */
    private ConnectionInterface $connection;

    private const BASE_SEARCH_SQL = <<<EOF
select
       student.id,
       student.date_of_birth, 
       student.phone, 
       student.class_id, 
       student.login, 
       student.password, 
       student.surname, 
       student.name, 
       student.patronymic, 
       student.street, 
       student.home, 
       student.apartment,
       
       p.id,
       p.name,
       p.login,
       p.password,
       p.apartment,
       p.home,
       p.street,
       p.place_of_work,
       p.id, 
       p.date_of_birth,
       p.phone, 
       p.place_of_work,
       p.email 

from users_students as student
    left join class as c on student.class_id = c.id
    left join students_to_parents as stp on student.id = stp.student_id
    left join users_parents as p on p.id = stp.parent_id
EOF;


    /**
     * @param ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     *
     *
     * @param array $criteria
     * @return array
     */
    public function findBy(array $criteria): array
    {
        $studentData = $this->loadData($criteria);
        return $this->buildStudentEntities($studentData);
    }

    /**
     * Загрузка данный по критерию
     *
     * @param array $criteria
     * @return void
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
            $errMsg = 'Неподдерживаемые критерии поиска студентов'
                . implode(', ', $notSupportedSearchCriteria);
            throw new RuntimeException($errMsg);
        }
        if (count($whereParts) > 0) {
            $sql .= ' WHERE ' . implode(' AND ', $whereParts);
        }
        $sql .= "\n ORDER BY student.id";
        $statement = $this->connection->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll();

    }

    /**
     * Создание массива с сущьностями студентов
     *
     * @param array $data
     * @return array
     */
    private function buildStudentEntities(array $data) :array
    {
        $studentData = [];
        $parents = [];
        foreach ($data as $item) {
            $studentId = $item['student_id'];
            $classId = $item['class_id'];
            $parentsId = $item['parent_id'];
            if (false === array_key_exists($studentId, $studentData)) {
                $studentData[$studentId] = [
                    'id' => $studentId,
                    'dateOfBirth' => DateTimeImmutable::createFromFormat(
                        'Y-m-d',
                        $item['student_date_of_birth']
                    ),
                    'phone' => $item['student_phone'],
                    'login' => $item['student_login'],
                    'password' => $item['student_password']
                ];
                $studentFio = [
                    'surname' => $item['student_surname'],
                    'name' => $item['student_name'],
                    'patronymic' => $item['student_patronymic']
                ];
                $studentData[$studentId]['fio'] = $this->createFioArray($studentFio);
                $studentAddress = [
                    'street' => $item['student_street'],
                    'home' => $item['student_home'],
                    'apartment' => $item['student_apartment']
                ];
                $studentData[$studentId]['address'] = $this->createAddress($studentAddress);
                $studentClass = [
                    'id' => $classId,
                    'number' => $item['class_number'],
                    'letter' => $item['class_letter']
                ];
                $studentData[$studentId]['class_id'] = ClassClass::createFromArray($studentClass);
                if (null !== $parentsId) {
                    if (false === array_key_exists($parentsId, $parents)) {
                        $studentParents = [
                            'id' => $parentsId,
                            'dateOfBirth' => DateTimeImmutable::createFromFormat(
                                'Y-m-d',
                                $item['parent_date_of_birth']
                            ),
                            'phone' => $item['parent_phone'],
                            'placeOfWork' => $item['parent_place_of_work'],
                            'email' => $item['parent_email'],
                            'login' => $item['parent_login'],
                            'password' => $item['parent_password']
                        ];
                        $parentFio = [
                            'surname' => $item['parent_surname'],
                            'name' => $item['parent_name'],
                            'patronymic' => $item['parent_patronymic']
                        ];
                        $studentParents['fio'] = $this->createFioArray($parentFio);
                        $parentAddress = [
                            'street' => $item['parent_street'],
                            'home' => $item['parent_home'],
                            'apartment' => $item['parent_apartment']
                        ];
                        $studentParents['address'] = $this->createAddress($parentAddress);
                        $studentParents[$parentsId] = ParentUserClass::createFromArray($studentParents);
                    }
                    $studentData[$studentId]['parent_id'][$parentsId] = $studentParents[$parentsId];
                }
            }
        }
        $studentEntities = [];
        foreach ($studentData as $item) {
            $studentEntities[] = StudentUserClass::createFromArray($item);
        }
        return $studentEntities;
    }

    /**
     * Создание объекта значения фио
     *
     * @param array $arrayItem - массив с фамилией, именем и отчеством
     * @return Fio[] - массив с фио
     */
    private function createFioArray(array $arrayItem): array
    {
        $fio = [];
        $fio[] = new Fio(
            $arrayItem['surname'],
            $arrayItem['name'],
            $arrayItem['patronymic'],
        );
        return $fio;
    }

    /**
     * Создание объекта значений адрес
     *
     * @param array $userItem - данные для создания адреса в массиве
     * @return Address[] - массив адрессов
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