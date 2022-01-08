<?php

namespace JoJoBizzareCoders\DigitalJournal\Controller;

use JoJoBizzareCoders\DigitalJournal\Entity\ClassClass;
use JoJoBizzareCoders\DigitalJournal\Entity\ItemClass;
use JoJoBizzareCoders\DigitalJournal\Entity\LessonClass;
use JoJoBizzareCoders\DigitalJournal\Entity\ParentUserClass;
use JoJoBizzareCoders\DigitalJournal\Entity\ReportClass;
use JoJoBizzareCoders\DigitalJournal\Entity\StudentUserClass;
use JoJoBizzareCoders\DigitalJournal\Entity\TeacherUserClass;
use JoJoBizzareCoders\DigitalJournal\Exception\InvalidDataStructureException;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Controller\ControllerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DataLoader\JsonDataLoader;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\HttpResponse;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerRequest;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerResponseFactory;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\LoggerInterface;

use JoJoBizzareCoders\DigitalJournal\Infrastructure\Validator\Assert;
use JoJoBizzareCoders\DigitalJournal\Exception;
use JoJoBizzareCoders\DigitalJournal\ValueObject\AdditionalInfo;
use JsonException;


/**
 * Контроллер отвечающий за поиск оценок
 */
class GetReportCollectionController implements ControllerInterface
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

    //Методы

    /**
     * Конструктор поиска Оценок
     *
     * @param LoggerInterface $logger - используемый логгер
     * @param string $pathToItems - Путь до файла с данными об предметах
     * @param string $pathToTeachers - Путь до файла с данными об учителях
     * @param string $pathToClasses - Путь до файла с данными об классах
     * @param string $pathToStudents - Путь до файла с данными об Учителях
     * @param string $pathToParents - Путь до файла с данными об Родителях
     */
    public function __construct(
        LoggerInterface $logger,
        string $pathToItems,
        string $pathToTeachers,
        string $pathToClasses,
        string $pathToStudents,
        string $pathToParents,
        string $pathToLesson,
        string $pathToAssessmentReport
    ) {
        $this->logger = $logger;
        $this->pathToItems = $pathToItems;
        $this->pathToTeachers = $pathToTeachers;
        $this->pathToClasses = $pathToClasses;
        $this->pathToStudents = $pathToStudents;
        $this->pathToParents = $pathToParents;
        $this->pathToLesson = $pathToLesson;
        $this->pathToAssessmentReport = $pathToAssessmentReport;
    }


    /**
     * Загрузка данных о предметах и создаёт сущности Предметов на основе этих данных
     *
     * @return array
     * @throws JsonException
     */
    private function loadItemsEntity(): array
    {
        $loader=new JsonDataLoader();
        $items = $loader->LoadDate($this->pathToItems);
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
        return (new JsonDataLoader())->LoadDate($this->pathToAssessmentReport);
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
     * Метод логирования
     *
     * @param LoggerInterface $logger
     * @param string $msg
     * @return void
     */
    private function Log(LoggerInterface $logger, string $msg): void
    {
        $logger->Log($msg);
    }

    /**
     * Валидирует парметры запроса
     *
     * @param ServerRequest $serverRequest - серверный http запрос
     * @return string|null - возвращает сообщение об ошибке, или null если ошибки нет.
     */
    private function ValidateQueryParams(ServerRequest $serverRequest): ?string
    {
        $paramValidations = [
            'item_name' => 'Incorrect item name',
            'item_description' => 'Incorrect item description',
            'lesson_date' => 'Incorrect lesson date',
            'student_fio' => 'Incorrect student fio',
        ];
        $queryParams = array_merge($serverRequest->getQueryParams(), $serverRequest->getAttributes());
        return Assert::arrayElementsIsString($paramValidations, $queryParams);
    }

    /**
     * Поиск оценки
     *
     * @param ServerRequest $serverRequest - серверный http запрос
     * @param array $reports - массив данных оценок
     * @param array $itemsIdToInfo - сущность Предметов
     * @param array $lessonIdToInfo - массив сущностей Занятий
     * @param array $studentIdToInfo - массив сущностей Студентов
     * @return array
     */
    private function searchForAssessmentReportInData(
        ServerRequest $serverRequest,
        array $reports,
        array $itemsIdToInfo,
        array $lessonIdToInfo,
        array $studentIdToInfo
    ): array {
        $searchCriteria = array_merge( $serverRequest->getQueryParams(), $serverRequest->getAttributes());
        $foundReport = [];
        // Поиск оценок
        $ReportMeetSearchCriteria = null;
        foreach ($reports as $report) {
            if (array_key_exists('item_name', $searchCriteria)) // Поиск по присутвию item_name в GET запросе и совпадению item_name в запросе и массиве оценок. [начало]
            {
                $ReportMeetSearchCriteria = ($searchCriteria['item_name'] === $itemsIdToInfo[$lessonIdToInfo[$report['lesson_id']]
                        ->getItem()
                        ->getId()]
                        ->getName());
            }// Поиск по присутвию item_name в GET запросе и совпадению item_name в запросе и массиве оценок. [конец]
            if (array_key_exists('item_description', $searchCriteria)) {
                $ReportMeetSearchCriteria = ($searchCriteria['item_description'] === $itemsIdToInfo[$lessonIdToInfo[$report['lesson_id']]
                        ->getItem()
                        ->getId()]
                        ->getDescription());
            }
            if (array_key_exists('lesson_date', $searchCriteria)) {
                $ReportMeetSearchCriteria = ($searchCriteria['lesson_date'] === $lessonIdToInfo[$report['lesson_id']]
                        ->getDate());
            }
            if (array_key_exists('student_fio', $searchCriteria)) {
                $ReportMeetSearchCriteria = ($searchCriteria['student_fio']
                    === $studentIdToInfo[$report['student_id']]
                        ->getFio());
            }
            if ($ReportMeetSearchCriteria) { // Отбор наёденных оценок
                $foundReport[] = $this->ReportFactory($report, $lessonIdToInfo, $studentIdToInfo);
                $ReportMeetSearchCriteria = null;
            }
        }//Цикл по оценкам [конец]

        $this->Log($this->logger, 'found Report' . count($foundReport));
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

    /**
     * Обработка запроса поиска оценок
     *
     * @param ServerRequest $serverRequest - http запрос
     * @return HttpResponse - http ответ
     * @throws InvalidDataStructureException|JsonException
     */
    public function __invoke(ServerRequest $serverRequest): HttpResponse
    {
        $this->Log($this->logger, 'assessmentReport" url');
        $resultOfParamValidation = $this->ValidateQueryParams($serverRequest);
        if (null === $resultOfParamValidation) {
            $itemsIdToInfo = $this->loadItemsEntity();
            $lessonIdToInfo = $this->loadLessonEntity($itemsIdToInfo);
            $studentIdToInfo = $this->loadStudentsEntity();
            $reports = $this->loadReportData();

            $foundReport = $this->searchForAssessmentReportInData(
                $serverRequest,
                $reports,
                $itemsIdToInfo,
                $lessonIdToInfo,
                $studentIdToInfo
            );
            $result = $this->buildResult($foundReport);
            $httpCode = $this->buildHttpCode($foundReport);
        } else {
            $httpCode = 500;
            $result = [
                'status' => 'fail',
                'message' => $resultOfParamValidation
            ];
        }
        return ServerResponseFactory::createJsonResponse($httpCode, $result);
    }
    protected function buildResult(array $foundReport)
    {
        return $foundReport;
    }

    /**
     * HttpCode
     * @param array $foundReport
     * @return int
     */
    protected function buildHttpCode(array $foundReport):int
    {
        return 200;
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

}