<?php

namespace JoJoBizzareCoders\DigitalJournal\Repository;

use DateTimeImmutable;
use JoJoBizzareCoders\DigitalJournal\Entity\AssessmentReportRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\ClassClass;
use JoJoBizzareCoders\DigitalJournal\Entity\ItemClass;
use JoJoBizzareCoders\DigitalJournal\Entity\LessonClass;
use JoJoBizzareCoders\DigitalJournal\Entity\ParentUserClass;
use JoJoBizzareCoders\DigitalJournal\Entity\ReportClass;
use JoJoBizzareCoders\DigitalJournal\Entity\StudentUserClass;
use JoJoBizzareCoders\DigitalJournal\Entity\TeacherUserClass;
use JoJoBizzareCoders\DigitalJournal\Exception\InvalidDataStructureException;
use JoJoBizzareCoders\DigitalJournal\Exception\RuntimeException;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Db\ConnectionInterface;
use JoJoBizzareCoders\DigitalJournal\ValueObject\Address;
use JoJoBizzareCoders\DigitalJournal\ValueObject\Fio;
use JsonException;

/**
 * Репризиторий для поиска оценок. В качестве хранилища используеться json файлы
 */
class AssessmentReportDbRepository implements AssessmentReportRepositoryInterface
{
    private const SEARCH_CRITERIA_TO_SQL_PARTS = [
        'item_name' => 'item.name',
        'item_description' => 'item.description',
        'lesson_date' => 'lesson.date',
        'student_fio_surname' => 'us.surname',
        'student_fio_name' => 'us.name',
        'student_fio_patronymic' => 'us.patronymic',
        'id' => 'report.id'
    ];


    private const BASE_SEARCH_SQL = <<<EOF
select
       report.id as id,
       report.mark as mark,

       item.id as item_id,
       item.name as item_name,
       item.description as item_description,
       
       lesson.id as lesson_id,
       lesson.date as lesson_date,
       lesson.lesson_duration as lesson_duration,
       lessonclass.id as class_id,
       lessonclass.letter as class_letter,
       lessonclass.number as class_number,

       student.id as student_id,
       us.date_of_birth as student_date_of_birth,
       us.phone as student_phone,
       studentclass.id as student_class_id,
       studentclass.number as student_class_number,
       studentclass.letter as student_letter,
       
       stp.parent_id as parent_id,
       up.name as parent_name,
       up.surname as parent_surname,
       up.patronymic as parent_patronymic,
       up.date_of_birth as parent_date_of_birth,
       parents.place_of_work as parent_place_of_work,
       up.phone as parent_phone,
       parents.email as parent_email,
       up.street as parent_street,
       up.home as parent_home,
       up.apartment as parent_apartment,
       up.login as parent_login,
       up.password as parent_password,
       
       us.login as student_login,
       us.password as student_password,
       us.surname as student_surname,
       us.name as student_name,
       us.patronymic as student_patronymic,
       us.street  as student_street,
       us.home as student_home,
       us.apartment  as student_apartment,

       ut.id as teacher_id,
       ut.date_of_birth as teacher_date_of_birth,
       ut.phone as teacher_phone,
       teacher_item.id as teacher_item_id,
       teacher_item.name as teacher_item_name,
       teacher_item.description as teacher_item_description,
       teachers.cabinet as teacher_cabinet,
       teachers.email as teacher_email,
       ut.login as teacher_login,
       ut.password as teacher_password,
       ut.surname as teacher_surname,
       ut.name as teacher_name,
       ut.patronymic as teacher_patronymic,
       ut.street as teacher_street,
       ut.home as teacher_home,
       ut.apartment as teacher_apartment


from assessment_report as report
          
         left join lesson on lesson.id = report.lesson_id
    
         join users as us on  report.student_id = us.id
         left join students as student on us.id = student.id
    
         join users as ut on lesson.teacher_id = ut.id
         left join teachers on ut.id = teachers.id
             left join students_to_parents as stp on student.id = stp.student_id
            join users as up on stp.parent_id = up.id
            left join parents on stp.parent_id = parents.id
         left join class as studentclass on studentclass.id = student.class_id
         left join class as lessonclass on lessonclass.id = lesson.class_id
         left join item as item on lesson.item_id = item.id
         left join item as teacher_item on teachers.item_id = teacher_item.id
EOF;


    /**
     *
     *
     * @var ConnectionInterface
     */
    private ConnectionInterface $connection;

    /**
     * @param ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }


    /**
     * Создание массива фио пользователя
     *
     * @param $user - коллекция объектов пользователей
     * @return array
     */
    private function createArrayFio(array $user): array
    {
        if (false === array_key_exists('fio', $user)) {
            throw new InvalidDataStructureException('Нет данных о фио');
        }
        if (false === is_array($user['fio'])) {
            throw new InvalidDataStructureException('Данные о фио имеют неверный формат');
        }

        $fio[] = $this->createFio($user['fio']);

        return $fio;
    }

    /**
     * Создание фио пользователя
     *
     * @param  $userData - иформация об фио пользователя
     * @return Fio
     */
    private function createFio($userData): Fio
    {
        if (false === is_array($userData)) {
            throw new InvalidDataStructureException('Данные о фио имеют неверный формат');
        }
        if (false === array_key_exists('surname', $userData)) {
            throw new InvalidDataStructureException('Отсутствуют данные о фамилии пользователей');
        }
        if (false === is_string($userData['surname'])) {
            throw new InvalidDataStructureException('Данные о фамилии пользователей имеют не верный формат');
        }
        if (false === array_key_exists('name', $userData)) {
            throw new InvalidDataStructureException('Отсутствуют данные о имени пользователей');
        }
        if (false === is_string($userData['name'])) {
            throw new InvalidDataStructureException('Данные о имени пользователей имеют не верный формат');
        }
        if (false === array_key_exists('patronymic', $userData)) {
            throw new InvalidDataStructureException('Отсутствуют данные о отчестве пользователей');
        }
        if (false === is_string($userData['patronymic'])) {
            throw new InvalidDataStructureException('Данные о отчестве пользователей имеют не верный формат');
        }
        return new Fio(
            $userData['surname'],
            $userData['name'],
            $userData['patronymic']
        );
    }

    /**
     * Создаём коллекцию адрессов пользователя
     *
     * @param $user - коллекция объектов пользователей
     * @return array
     */
    private function createArrayAddress($user): array
    {
        if (false === array_key_exists('address', $user)) {
            throw new InvalidDataStructureException('Нет данных о аддрессе');
        }
        if (false === is_array($user['address'])) {
            throw new InvalidDataStructureException('Данные о аддрессе имеют неверный формат');
        }

        $address[] = $this->createAddress($user['address']);

        return $address;
    }

    /**
     * Создание аддресс пользователя
     *
     * @param $userData - иформация о адрессе пользователей
     * @return Address
     */
    private function createAddress($userData): Address
    {
        if (false === is_array($userData)) {
            throw new InvalidDataStructureException('Данные о аддрессах имеют неверный формат');
        }
        if (false === array_key_exists('street', $userData)) {
            throw new InvalidDataStructureException('Отсутствуют данные о улице пользователей');
        }
        if (false === is_string($userData['street'])) {
            throw new InvalidDataStructureException('Данные о улице пользователей имеют не верный формат');
        }
        if (false === array_key_exists('home', $userData)) {
            throw new InvalidDataStructureException('Отсутствуют данные о номере дома пользователей');
        }
        if (false === is_string($userData['home'])) {
            throw new InvalidDataStructureException('Данные о номере дома пользователей имеют не верный формат');
        }
        if (false === array_key_exists('apartment', $userData)) {
            throw new InvalidDataStructureException('Отсутствуют данные о номере квартире пользователей');
        }
        if (false === is_string($userData['apartment'])) {
            throw new InvalidDataStructureException(
                'Данные о номере квартире пользователей имеют не верный формат'
            );
        }
        return new Address(
            $userData['street'],
            $userData['home'],
            $userData['apartment']
        );
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function findBy(array $criteria): array
    {
        $reportData = $this->loadData($criteria);

        return $this->buildReport($reportData);
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function save(ReportClass $entity): ReportClass
    {
        $sql = <<<EOF
UPDATE assessment_report
set
    lesson_id = :lesson_id,
    student_id = :student_id,
    mark = :mark
EOF;

        $values = [
            'id' => $entity->getId(),
            'lesson_id' => $entity->getLesson()->getId(),
            'student_id' => $entity->getStudent()->getId(),
            'mark' => $entity->getMark()
        ];
        $this->connection->prepare($sql)->execute($values);

        return $entity;
    }


    /**
     *  Добавление
     *
     * @param ReportClass $entity
     * @return ReportClass
     * @throws JsonException
     */
    public function add(ReportClass $entity): ReportClass
    {
        $sql = <<<EOF
INSERT INTO assessment_report(id, lesson_id, student_id, mark) 
values (
           :id,
           :lesson_id,
           :student_id,
           :mark
)
EOF;
        $values = [
            'id' => $entity->getId(),
            'lesson_id' => $entity->getLesson()->getId(),
            'student_id' => $entity->getStudent()->getId(),
            'mark' => $entity->getMark()
        ];

        $this->connection->prepare($sql)->execute($values);

        return $entity;
    }


    public function nextId(): int
    {
        $sql = <<<EOF
SELECT nextval('assessment_report_id_seq') AS next_id
EOF;

        return (int)current($this->connection->query($sql)->fetchAll())['next_id'];
    }

    private function loadData(array $criteria): array
    {
        $sql = self::BASE_SEARCH_SQL;

        $whereParts = [];
        $params = [];
        $notSupportedCriteria = [];

        foreach ($criteria as $criteriaName => $criteriaValue) {
            if (array_key_exists($criteriaName, self::SEARCH_CRITERIA_TO_SQL_PARTS)) {
                $sqlParts = self::SEARCH_CRITERIA_TO_SQL_PARTS[$criteriaName];
                $whereParts[] = "$sqlParts=:$criteriaName";
                $params[$criteriaName] = $criteriaValue;
            } else {
                $notSupportedCriteria[] = $criteriaName;
            }
        }
        if (count($notSupportedCriteria) > 0) {
            $errMsg = 'Неподдерживаемый критерий поиска : ' . implode(',', $notSupportedCriteria);
            throw new RuntimeException($errMsg);
        }


        if (count($whereParts) > 0) {
            $sql .= ' WHERE ' . implode(' AND ', $whereParts);
        }



        $sql .= ' order by report.id';
        $statment = $this->connection->prepare($sql);
        $statment->execute($params);


        return $statment->fetchAll();
    }

    private function buildReport(array $data): array
    {
        $reportData = [];
        $parents = [];

        foreach ($data as $row) {
            $reportId = $row['id'];
            $parentId = $row['parent_id'];

            if (false === array_key_exists($reportId, $reportData)) {
                $reportData[$reportId] = [
                    'id' => $reportId,
                    'lesson' => [],
                    'student' => [],
                    'mark' => $row['mark']
                ];

                $lessonClass = new ClassClass(
                    $row['class_id'],
                    $row['class_number'],
                    $row['class_letter'],
                );

                $studentClass = new ClassClass(
                    $row['student_class_id'],
                    $row['student_class_number'],
                    $row['student_letter'],
                );


                $lessonItem = new ItemClass(
                    $row['item_id'],
                    $row['item_name'],
                    $row['item_description'],
                );


                $teacherFio['fio'] = [
                    'name' => $row['teacher_name'],
                    'surname' => $row['teacher_surname'],
                    'patronymic' => $row['teacher_patronymic'],
                ];
                $teacherAddress['address'] =
                    [
                        'street' => $row['teacher_street'],
                        'home' => $row['teacher_home'],
                        'apartment' => $row['teacher_apartment']
                    ];

                $teacherItem = new ItemClass(
                    $row['teacher_item_id'],
                    $row['teacher_item_name'],
                    $row['teacher_item_description'],
                );


                $teacherDoB = DateTimeImmutable::createFromFormat('Y-m-d', $row['teacher_date_of_birth']);


                $teacher = new TeacherUserClass(
                    $row['teacher_id'],
                    $this->createArrayFio($teacherFio),
                    $teacherDoB,
                    $row['teacher_phone'],
                    $this->createArrayAddress($teacherAddress),
                    $teacherItem,
                    $row['teacher_cabinet'],
                    $row['teacher_email'],
                    $row['teacher_login'],
                    $row['teacher_password'],
                );

                $lessonDate = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $row['lesson_date']);


                $lesson = new LessonClass(
                    $row['lesson_id'],
                    $lessonItem,
                    $lessonDate,
                    $row['lesson_duration'],
                    $teacher,
                    $lessonClass
                );
                $reportData[$reportId]['lesson'] = $lesson;

                if (false === array_key_exists($parentId, $parents)) {
                    $parentsFio['fio'] = [
                        'name' => $row['parent_name'],
                        'surname' => $row['parent_surname'],
                        'patronymic' => $row['parent_patronymic'],
                    ];

                    $parentAddress['address'] =
                        [
                            'street' => $row['parent_street'],
                            'home' => $row['parent_home'],
                            'apartment' => $row['parent_apartment']
                        ];

                    $parentDoB = DateTimeImmutable::createFromFormat('Y-m-d', $row['parent_date_of_birth']);

                    $parents[$parentId] = new ParentUserClass(
                        $row['parent_id'],
                        $this->createArrayFio($parentsFio),
                        $parentDoB,
                        $row['parent_phone'],
                        $this->createArrayAddress($parentAddress),
                        $row['parent_place_of_work'],
                        $row['parent_email'],
                        $row['parent_login'],
                        $row['parent_password']
                    );
                }


                $studentFio['fio'] = [
                    'name' => $row['student_name'],
                    'surname' => $row['student_surname'],
                    'patronymic' => $row['student_patronymic'],
                ];

                $studentAddress['address'] =
                    [
                        'street' => $row['student_street'],
                        'home' => $row['student_home'],
                        'apartment' => $row['student_apartment']
                    ];

                $studentDoB = DateTimeImmutable::createFromFormat('Y-m-d', $row['student_date_of_birth']);

                $student = new StudentUserClass(
                    $row['student_id'],
                    $this->createArrayFio($studentFio),
                    $studentDoB,
                    $row['student_phone'],
                    $this->createArrayAddress($studentAddress),
                    $studentClass,
                    $parents,
                    $row['student_login'],
                    $row['student_password']
                );
                $reportData[$reportId]['student'] = $student;
            }
        }

        $reportEntities = [];

        foreach ($reportData as $item) {
            $reportEntities[] = new ReportClass(
                $item['id'],
                $item['lesson'],
                $item['student'],
                $item['mark']
            );
        }

        return $reportEntities;
    }

}