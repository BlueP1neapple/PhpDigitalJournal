<?php

namespace JoJoBizzareCoders\DigitalJournal\Repository;

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
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DataLoader\DataLoaderInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DataLoader\JsonDataLoader;
use JoJoBizzareCoders\DigitalJournal\ValueObject\Address;
use JoJoBizzareCoders\DigitalJournal\ValueObject\Fio;
use JsonException;

/**
 * Репризиторий для поиска оценок. В качестве хранилища используеться json файлы
 */
class AssessmentReportJsonRepository implements AssessmentReportRepositoryInterface
{
    /**
     * Путь до файла с данными об предметах
     *
     * @var string
     */
    private string $pathToItems;

    /**
     * Путь до файла с данными об учителях
     *
     * @var string
     */
    private string $pathToTeachers;

    /**
     * Путь до файла с данными об классах
     *
     * @var string
     */
    private string $pathToClasses;

    /**
     * Путь до файла с данными об Учителях
     *
     * @var string
     */
    private string $pathToStudents;

    /**
     * Путь до файла с данными об Родителях
     *
     * @var string
     */
    private string $pathToParents;

    /**
     * Путь до файла с данными об Занятиях
     *
     * @var string
     */
    private string $pathToLesson;

    /**
     * Путь до файла с данными об оценках
     *
     * @var string
     */
    private string $pathToAssessmentReport;

    /**
     * Загрузчик данных
     *
     * @var DataLoaderInterface
     */
    private DataLoaderInterface $dataLoader;

    /**
     * Данные о предметах
     *
     * @var array|null
     */
    private ?array $itemsIdToInfo = null;

    /**
     * Данные о Преподавателях
     *
     * @var array|null
     */
    private ?array $teachersIdToInfo = null;

    /**
     * Данные о классах
     *
     * @var array|null
     */
    private ?array $classesIdToInfo = null;

    /**
     * Данные о занятиях
     *
     * @var array|null
     */
    private ?array $lessonIdToInfo=null;

    /**
     * Данные о оценках
     *
     * @var array|null
     */
    private ?array $reportData = null;

    /**
     * Данные о родителях
     *
     * @var array|null
     */
    private ?array $parentIdToInfo = null;

    /**
     * Данные о родителях
     *
     * @var array|null
     */
    private ?array $studentIdToInfo = null;

    /**
     * Сопостовляет id оценки с номером элемента в $reportData
     *
     * @var array|null
     */
    private ?array $reportIdToIndex = null;

    /**
     * Текущий id
     *
     * @var int
     */
    private int $currentId;


    /**
     * Конструктор репризитория для поиска оценок. В качестве хранилища используеться json файлы
     *
     * @param string $pathToItems - Путь до файла с данными об предметах
     * @param string $pathToTeachers - Путь до файла с данными об учителях
     * @param string $pathToClasses - Путь до файла с данными об классах
     * @param string $pathToStudents - Путь до файла с данными об Учителях
     * @param string $pathToParents - Путь до файла с данными об Родителях
     * @param string $pathToLesson - Путь до файла с данными об Занятиях
     * @param string $pathToAssessmentReport - Путь до файла с данными об оценках
     * @param DataLoaderInterface $dataLoader - Загрузчик данных
     */
    public function __construct(
        string $pathToItems,
        string $pathToTeachers,
        string $pathToClasses,
        string $pathToStudents,
        string $pathToParents,
        string $pathToLesson,
        string $pathToAssessmentReport,
        DataLoaderInterface $dataLoader
    ) {
        $this->pathToItems = $pathToItems;
        $this->pathToTeachers = $pathToTeachers;
        $this->pathToClasses = $pathToClasses;
        $this->pathToStudents = $pathToStudents;
        $this->pathToParents = $pathToParents;
        $this->pathToLesson = $pathToLesson;
        $this->pathToAssessmentReport = $pathToAssessmentReport;
        $this->dataLoader = $dataLoader;
    }

    /**
     * Загрузка данных о предметах и создаёт сущности Предметов на основе этих данных
     *
     * @return array
     * @throws JsonException
     */
    private function loadItemsEntity(): array
    {
        if (null === $this->itemsIdToInfo){
            $items = $this->dataLoader->LoadDate($this->pathToItems);
            $itemsIdToInfo = [];
            foreach ($items as $item) {
                $itemsObj = ItemClass::createFromArray($item);
                $itemsIdToInfo[$itemsObj->getId()] = $itemsObj;
            }
            $this->itemsIdToInfo=$itemsIdToInfo;
        }
        return $this->itemsIdToInfo;
    }

    /**
     * Загрузка данных о учителях и создаёт сущности Учителя на основе этих данных
     *
     * @param array $itemsIdToInfo - Сущности Предметов
     * @return array
     * @throws JsonException
     */
    private function loadTeachersEntity(array $itemsIdToInfo): array
    {
        if(null === $this->teachersIdToInfo){
            $teachers = $this->dataLoader->LoadDate($this->pathToTeachers);
            $teachersIdToInfo = [];
            foreach ($teachers as $teacher) {
                $teacher['idItem'] = $itemsIdToInfo[$teacher['idItem']];
                $teacher['fio'] = $this->createArrayFio($teacher);
                $teacher['address'] = $this->createArrayAddress($teacher);
                $teachersObj = TeacherUserClass::createFromArray($teacher);
                $teachersIdToInfo[$teachersObj->getId()] = $teachersObj;
            }
            $this->teachersIdToInfo=$teachersIdToInfo;
        }
        return $this->teachersIdToInfo;
    }

    /**
     * Загрузка данных о классах и создаёт сущности Классы на основе этих данных
     *
     * @return array
     * @throws JsonException
     */
    private function loadClassEntity(): array
    {
        if(null === $this->classesIdToInfo){
            $classes = $this->dataLoader->LoadDate($this->pathToClasses);
            $classesIdToInfo = [];
            foreach ($classes as $class) {
                $classesObj = ClassClass::createFromArray($class);
                $classesIdToInfo[$classesObj->getId()] = $classesObj;
            }
            $this->classesIdToInfo=$classesIdToInfo;
        }
        return $this->classesIdToInfo;
    }

    /**
     * Загрузка данных о занятиях и создаёт сущности Занятия на основе этих данных
     *
     * @param array $itemsIdToInfo - сущности Предметов
     * @return array
     * @throws JsonException
     */
    private function loadLessonEntity(array $itemsIdToInfo): array
    {
        if(null === $this->lessonIdToInfo){
            $lessons = $this->dataLoader->LoadDate($this->pathToLesson);
            $teachersIdToInfo = $this->loadTeachersEntity($itemsIdToInfo);
            $classesIdToInfo = $this->loadClassEntity();
            $lessonIdToInfo = [];
            foreach ($lessons as $lesson) {
                $lesson['item_id'] = $itemsIdToInfo[$lesson['item_id']];
                $lesson['teacher_id'] = $teachersIdToInfo[$lesson['teacher_id']];
                $lesson['class_id'] = $classesIdToInfo[$lesson['class_id']];
                $lessonsObj = LessonClass::createFromArray($lesson);
                $lessonIdToInfo[$lessonsObj->getId()] = $lessonsObj;
            }
            $this->lessonIdToInfo=$lessonIdToInfo;
        }
        return $this->lessonIdToInfo;
    }

    /**
     * Метод реализующий загрузку данных о Оцкенках
     *
     * @return array
     * @throws JsonException
     */
    private function loadReportData(): array
    {
        if (null === $this->reportData){
            $this->reportData = $this->dataLoader->LoadDate($this->pathToAssessmentReport);
            $this->reportIdToIndex = array_combine(
                array_map(
                    static function (array $v) {
                        return $v['id'];
                    },
                    $this->reportData
                ),
                array_keys($this->reportData)
            );
        }
        return $this->reportData;
    }

    /**
     * Загрузка данных о родителях и создаёт сущности Родители на основе этих данных
     *
     * @return array
     * @throws JsonException
     */
    private function loadParentsEntity(): array
    {
        if(null === $this->parentIdToInfo){
            $loader = new JsonDataLoader();
            $parents = $loader->LoadDate($this->pathToParents);
            $parentIdToInfo = [];
            foreach ($parents as $parent) {
                $parent['fio'] = $this->createArrayFio($parent);
                $parent['address'] = $this->createArrayAddress($parent);
                $parentsObj = ParentUserClass::createFromArray($parent);
                $parentIdToInfo[$parentsObj->getId()] = $parentsObj;
            }
            $this->parentIdToInfo=$parentIdToInfo;
        }
        return $this->parentIdToInfo;
    }

    /**
     * Загрузка данных о Учениках и создаёт сущности Ученики на основе этих данных
     *
     * @return array
     * @throws JsonException
     */
    private function loadStudentsEntity(): array
    {
        if (null === $this->studentIdToInfo){
            $loader = new JsonDataLoader();
            $students = $loader->LoadDate($this->pathToStudents);
            $classesIdToInfo = $this->loadClassEntity();
            $parentIdToInfo = $this->loadParentsEntity();
            $studentIdToInfo = [];
            foreach ($students as $student) {
                $student['class_id'] = $classesIdToInfo[$student['class_id']];
                $student['parent_id'] = $parentIdToInfo[$student['parent_id']];
                $student['fio'] = $this->createArrayFio($student);
                $student['address'] = $this->createArrayAddress($student);
                $studentsObj = StudentUserClass::createFromArray($student);
                $studentIdToInfo[$studentsObj->getId()] = $studentsObj;
            }
            $this->studentIdToInfo = $studentIdToInfo;
        }
        return $this->studentIdToInfo;
    }

    /**
     * Реализация логики создания электронного журнала
     *
     * @param array $report - массив оценок
     * @param array $lessonIdToInfo - Массив сущностей Занятий
     * @param array $studentIdToInfo - Массив сущностей Студентов
     * @return ReportClass
     */
    private function ReportFactory(array $report, array $lessonIdToInfo, array $studentIdToInfo): ReportClass
    {
        $report['lesson_id'] = $lessonIdToInfo[$report['lesson_id']];
        $report['student_id'] = $studentIdToInfo[$report['student_id']];
        return ReportClass::createFromArray($report);
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
        $fio = [];
        foreach ($user['fio'] as $userData) {
            $fio[] = $this->createFio($userData);
        }
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
        $address = [];
        foreach ($user['address'] as $userData) {
            $address[] = $this->createAddress($userData);
        }
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
        $foundReport = [];
        $reports = $this->loadReportData();
        $itemsIdToInfo = $this->loadItemsEntity();
        $lessonIdToInfo = $this->loadLessonEntity($itemsIdToInfo);
        $studentIdToInfo = $this->loadStudentsEntity();
        $ReportMeetSearchCriteria = null;
        foreach ($reports as $report) {
            if (array_key_exists('item_name', $criteria)) {
                $ReportMeetSearchCriteria = ($criteria['item_name']
                    === $itemsIdToInfo[$lessonIdToInfo[$report['lesson_id']]
                        ->getItem()
                        ->getId()]
                        ->getName());
            } else{
                $ReportMeetSearchCriteria = true;
            }
            if (array_key_exists('item_description', $criteria)) {
                $ReportMeetSearchCriteria = ($criteria['item_description']
                    === $itemsIdToInfo[$lessonIdToInfo[$report['lesson_id']]
                        ->getItem()
                        ->getId()]
                        ->getDescription());
            }
            if (array_key_exists('lesson_date', $criteria)) {
                $ReportMeetSearchCriteria = ($criteria['lesson_date']
                    === $lessonIdToInfo[$report['lesson_id']]
                        ->getDate());
            }
            if (array_key_exists('student_fio_surname', $criteria)) {
                $ReportMeetSearchCriteria = ($criteria['student_fio_surname']
                    === $studentIdToInfo[$report['student_id']]
                        ->getFio()[0]
                        ->getSurname());
            }
            if (array_key_exists('student_fio_name', $criteria)) {
                $ReportMeetSearchCriteria = ($criteria['student_fio_name']
                    === $studentIdToInfo[$report['student_id']]
                        ->getFio()[0]
                        ->getName());
            }
            if (array_key_exists('student_fio_patronymic', $criteria)) {
                $ReportMeetSearchCriteria = ($criteria['student_fio_patronymic']
                    === $studentIdToInfo[$report['student_id']]
                        ->getFio()[0]
                        ->getPatronymic());
            }
            if (array_key_exists('id', $criteria)) {
                $ReportMeetSearchCriteria = ($criteria['id']
                    === (string)$report['id']);
            }
            if ($ReportMeetSearchCriteria) {
                $foundReport[] = $this->ReportFactory($report, $lessonIdToInfo, $studentIdToInfo);
                $ReportMeetSearchCriteria = null;
            }
        }
        return $foundReport;
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function save(ReportClass $entity): ReportClass
    {
        $this->loadReportData();
        $report = $this->reportData;
        $itemIndex = $this->getItemIndex($entity);
        $item = $this->buildJsonData($entity);
        $report[$itemIndex] = $item;
        $file = $this->pathToAssessmentReport;
        $jsonStr = json_encode($report, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($file, $jsonStr);
        return $entity;
    }

    /**
     * Получение индекса элемента с данными для занятия на основе id сущности
     *
     * @param ReportClass $entity - сущность
     * @return int
     */
    private function getItemIndex(ReportClass $entity):int
    {
        $id = $entity->getId();
        $entityToIndex = $this->reportIdToIndex;
        if (false === array_key_exists($id, $entityToIndex)) {
            throw new RuntimeException("Оценки с id = '$id', не найден в хранилище");
        }
        return $entityToIndex[$id];
    }

    /**
     * Логика сериализации данных о занятий
     *
     * @param ReportClass $entity - сущность
     * @return array
     */
    private function buildJsonData(ReportClass $entity):array
    {
        return [
            'id' => $entity->getId(),
            'lesson_id'=>$entity->getLesson()->getId(),
            'student_id'=>$entity->getStudent()->getId(),
            'mark'=>$entity->getMark()
        ];
    }
}