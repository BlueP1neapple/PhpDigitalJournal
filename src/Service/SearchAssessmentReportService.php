<?php

use JoJoBizzareCoders\DigitalJournal\Entity\ClassClass;
use JoJoBizzareCoders\DigitalJournal\Entity\ItemClass;
use JoJoBizzareCoders\DigitalJournal\Entity\LessonClass;
use JoJoBizzareCoders\DigitalJournal\Entity\ParentUserClass;
use JoJoBizzareCoders\DigitalJournal\Entity\ReportClass;
use JoJoBizzareCoders\DigitalJournal\Entity\StudentUserClass;
use JoJoBizzareCoders\DigitalJournal\Entity\TeacherUserClass;
use JoJoBizzareCoders\DigitalJournal\Exception\InvalidDataStructureException;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DataLoader\DataLoaderInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DataLoader\JsonDataLoader;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\LoggerInterface;
use JoJoBizzareCoders\DigitalJournal\Service\ReportAssessmentService\AssessmentReportDto;
use JoJoBizzareCoders\DigitalJournal\Service\ReportAssessmentService\ClassDto;
use JoJoBizzareCoders\DigitalJournal\Service\ReportAssessmentService\FullReportDto;
use JoJoBizzareCoders\DigitalJournal\Service\ReportAssessmentService\ItemDto;
use JoJoBizzareCoders\DigitalJournal\Service\ReportAssessmentService\LessonDto;
use JoJoBizzareCoders\DigitalJournal\Service\ReportAssessmentService\ParentDto;
use JoJoBizzareCoders\DigitalJournal\Service\ReportAssessmentService\StudentDto;
use JoJoBizzareCoders\DigitalJournal\Service\ReportAssessmentService\TeacherDto;
use JoJoBizzareCoders\DigitalJournal\ValueObject\AdditionalInfo;

use JoJoBizzareCoders\DigitalJournal\Exception;

class SearchAssessmentReportService
{
    // Свойства
    /**
     * Использвуемый логгер
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

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

        //Методы

        /**
         * Конструктор Сервиса поиска оценок
         *
         * @param LoggerInterface $logger - Использвуемый логгер
         * @param string $pathToItems - путь до файла с данными об предметах
         * @param string $pathToTeachers - путь до файла с данными об учителях
         * @param string $pathToClasses - путь до файла с данными об классах
         * @param string $pathToStudents - путь до файла с данными об Учителях
         * @param string $pathToParents - путь до файла с данными об Родителях
         * @param string $pathToLesson - путь до файла с данными об Занятиях
         * @param string $pathToAssessmentReport - путь до файла с данными об оценках
         * @param DataLoaderInterface $dataLoader - Загрузчик данных
         */
        public function __construct(
    LoggerInterface $logger,
    string $pathToItems,
    string $pathToTeachers,
    string $pathToClasses,
    string $pathToStudents,
    string $pathToParents,
    string $pathToLesson,
    string $pathToAssessmentReport,
    DataLoaderInterface $dataLoader
) {
    $this->logger = $logger;
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
    $items = $this->dataLoader->LoadDate($this->pathToItems);
    $itemsIdToInfo = [];
    foreach ($items as $item) {
        $itemsObj = ItemClass::createFromArray($item);
        $itemsIdToInfo[$itemsObj->getId()] = $itemsObj;
    }
    return $itemsIdToInfo;
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
        $loader = new JsonDataLoader();
        $teachers = $loader->LoadDate($this->pathToTeachers);
        $teachersIdToInfo = [];
        foreach ($teachers as $teacher) {
            $teacher['idItem'] = $itemsIdToInfo[$teacher['idItem']];
            $teachersObj = TeacherUserClass::createFromArray($teacher);
            $teachersIdToInfo[$teachersObj->getId()] = $teachersObj;
        }
        return $teachersIdToInfo;
    }


    /**
         * Загрузка данных о классах и создаёт сущности Классы на основе этих данных
         *
         * @return array
         * @throws JsonException
         */
    private function loadClassEntity(): array
    {
        $loader = new JsonDataLoader();
        $classes = $loader->LoadDate($this->pathToClasses);
        $classesIdToInfo = [];
        foreach ($classes as $class) {
            $classesObj = ClassClass::createFromArray($class);
            $classesIdToInfo[$classesObj->getId()] = $classesObj;
        }
        return $classesIdToInfo;
    }


    /**
         * Загрузка данных о занятиях и создаёт сущности Занятия на основе этих данных
         *
         * @param array $itemsIdToInfo - сущности Предметов
         * @return array
         * @throws JsonException
         */
    private function loadLessonEntity(
        array $itemsIdToInfo
    ): array {
        $loader = new JsonDataLoader();
        $lessons = $loader->LoadDate($this->pathToLesson);
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
        return $lessonIdToInfo;
    }


    /**
         * Метод реализующий загрузку данных о Оцкенках
         *
         * @return array
         * @throws JsonException
         */
        private function loadReportData(): array
{
    return $this->dataLoader->LoadDate($this->pathToAssessmentReport);
}

        /**
         * Загрузка данных о родителях и создаёт сущности Родители на основе этих данных
         *
         * @return array
         * @throws JsonException
         */
    private function loadParentsEntity(): array
    {
        $loader=new JsonDataLoader();
        $parents = $loader->LoadDate($this->pathToParents);
        $parentIdToInfo = [];
        foreach ($parents as $parent) {
            $parentsObj = ParentUserClass::createFromArray($parent);
            $parentIdToInfo[$parentsObj->getId()] = $parentsObj;
        }
        return $parentIdToInfo;
    }


    /**
         * Загрузка данных о Учениках и создаёт сущности Ученики на основе этих данных
         *
         * @return array
         * @throws JsonException
         */
    private function loadStudentsEntity(): array
    {
        $loader=new JsonDataLoader();
        $students = $loader->LoadDate($this->pathToStudents);
        $classesIdToInfo = $this->loadClassEntity();
        $parentIdToInfo = $this->loadParentsEntity();
        $studentIdToInfo = [];
        foreach ($students as $student) {
            $student['class_id'] = $classesIdToInfo[$student['class_id']];
            $student['parent_id'] = $parentIdToInfo[$student['parent_id']];
            $studentsObj = StudentUserClass::createFromArray($student);
            $studentIdToInfo[$studentsObj->getId()] = $studentsObj;
        }
        return $studentIdToInfo;
    }


    /**
         * Поиск оценки
         *
         * @param FullReportDto $criteriaForSearch - критерии поиска
         * @return array
         * @throws JsonException
         */
        private function searchEntity(FullReportDto $criteriaForSearch): array
{
    $foundReport = [];
    $reports = $this->loadReportData();
    $itemsIdToInfo = $this->loadItemsEntity();
    $lessonIdToInfo = $this->loadLessonEntity($itemsIdToInfo);
    $studentIdToInfo = $this->loadStudentsEntity();
    $ReportMeetSearchCriteria = null;
    foreach ($reports as $report) {
        if (null !== $criteriaForSearch->getItemName()) {
            $ReportMeetSearchCriteria = ($criteriaForSearch->getItemName()
                === $itemsIdToInfo[$lessonIdToInfo[$report['lesson_id']]
                    ->getItem()
                    ->getId()]
                    ->getName());
        }
        if (null !== $criteriaForSearch->getItemDescription()) {
            $ReportMeetSearchCriteria = ($criteriaForSearch->getItemDescription()
                === $itemsIdToInfo[$lessonIdToInfo[$report['lesson_id']]
                    ->getItem()
                    ->getId()]
                    ->getDescription());
        }
        if (null !== $criteriaForSearch->getLessonDate()) {
            $ReportMeetSearchCriteria = ($criteriaForSearch->getLessonDate()
                === $lessonIdToInfo[$report['lesson_id']]
                    ->getDate());
        }
        if (null !== $criteriaForSearch->getStudentFio()) {
            $ReportMeetSearchCriteria = ($criteriaForSearch->getStudentFio()
                === $studentIdToInfo[$report['student_id']]
                    ->getFio());
        }
        if (null !== $criteriaForSearch->getId()) {
            $ReportMeetSearchCriteria = ($criteriaForSearch->getId()
                === (string)$report['id']);
        }
        if ($ReportMeetSearchCriteria) {
            $foundReport[] = $this->ReportFactory($report, $lessonIdToInfo, $studentIdToInfo);
            $ReportMeetSearchCriteria = null;
        }
    }

    $this->logger->Log('found Report' . count($foundReport));
    return $foundReport;
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
    $report['additional_info'] = $this->createAdditionalInfo($report);
    return ReportClass::createFromArray($report);
}



    private function createAdditionalInfo($additionalInfo): AdditionalInfo
    {
        if(false === is_array($additionalInfo['additional_info'])){
            throw new Exception\InvalidDataStructureException("Не верный формат данных");
        }

        if(false === array_key_exists('topic', $additionalInfo['additional_info'])){
            throw new Exception\InvalidDataStructureException("Нет заголовка");
        }
        if(false === is_string($additionalInfo['additional_info']['topic'])){
            throw new Exception\InvalidDataStructureException("Заголовок не строка");
        }

        if(false === array_key_exists('comment', $additionalInfo['additional_info'])){
            throw new Exception\InvalidDataStructureException("Нет коментария");
        }
        if(false === is_string($additionalInfo['additional_info']['comment'])){
            throw new Exception\InvalidDataStructureException("Комментарий не строка");
        }
        return new AdditionalInfo(
            $additionalInfo['additional_info']['topic'],
            $additionalInfo['additional_info']['comment']
        );
    }


        /**
         * Метод поиска оценки по критериям
         *
         * @param FullReportDto $searchCriteria - критерии посика оценки
         * @return array
         * @throws JsonException
         */
        public function search(FullReportDto $searchCriteria): array
{
    $entitiesCollection = $this->searchEntity($searchCriteria);
    $dtoCollection = [];
    foreach ($entitiesCollection as $entity) {
        $dtoCollection[] = $this->createDto($entity);
    }
    $this->logger->log('found Report: ' . count($entitiesCollection));
    return $dtoCollection;
}

        /**
         * Создание dto объекта с информацией о оценках
         *
         * @param ReportClass $report - информация о оценке
         * @return AssessmentReportDto
         */
        private function createDto(ReportClass $report): AssessmentReportDto
{
    $lesson = $report->getLesson();
    $item = $lesson->getItem();
    $itemDto = new ItemDto(
        $item->getId(),
        $item->getName(),
        $item->getDescription()
    );
    $teacher = $lesson->getTeacher();
    $teacherDto = new TeacherDto(
        $teacher->getId(),
        $teacher->getFio(),
        $teacher->getDateOfBirth(),
        $teacher->getPhone(),
        $teacher->getAddress(),
        $itemDto,
        $teacher->getCabinet(),
        $teacher->getEmail()
    );
    $classForLesson = $lesson->getClass();
    $classForLessonDto = new ClassDto(
        $classForLesson->getId(),
        $classForLesson->getNumber(),
        $classForLesson->getLetter()
    );
    $lessonDto = new LessonDto(
        $lesson->getId(),
        $itemDto,
        $lesson->getDate(),
        $lesson->getLessonDuration(),
        $teacherDto,
        $classForLessonDto
    );
    $student = $report->getStudent();
    $parent = $student->getParent();
    $parentDto = new ParentDto(
        $parent->getId(),
        $parent->getFio(),
        $parent->getDateOfBirth(),
        $parent->getPhone(),
        $parent->getAddress(),
        $parent->getPlaceOfWork(),
        $parent->getEmail()
    );
    $classForStudent = $student->getClass();
    $classForStudentDto=new ClassDto(
        $classForStudent->getId(),
        $classForStudent->getNumber(),
        $classForStudent->getLetter()
    );
    $studentDto = new StudentDto(
        $student->getId(),
        $student->getFio(),
        $student->getDateOfBirth(),
        $student->getPhone(),
        $student->getAddress(),
        $classForStudentDto,
        $parentDto
    );
    return new AssessmentReportDto(
        $report->getId(),
        $lessonDto,
        $studentDto,
        $report->getMark(),
        $report->getAdditionalInfo()
    );
}
    }
