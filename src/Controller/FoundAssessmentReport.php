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
use JoJoBizzareCoders\DigitalJournal\Infrastructure\AppConfig;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\HttpResponse;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerRequest;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerResponseFactory;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\LoggerInterface;

use function JoJoBizzareCoders\DigitalJournal\Infrastructure\loadData;
use function JoJoBizzareCoders\DigitalJournal\Infrastructure\paramTypeValidation;

/**
 * Контроллер отвечающий за поиск оценок
 */
final class FoundAssessmentReport
{
    // Свойства
    /**
     * Использвуемый логгер
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Используемый конфиг приложения
     *
     * @var AppConfig
     */
    private AppConfig $appConfig;

    //Методы

    /**
     * Конструктор поиска Оценок
     *
     * @param LoggerInterface $logger - используемый логгер
     * @param AppConfig $appConfig - Конфиг приложения
     */
    public function __construct(LoggerInterface $logger, AppConfig $appConfig)
    {
        $this->logger = $logger;
        $this->appConfig = $appConfig;
    }


    /**
     * Загрузка данных о предметах и создаёт сущности Предметов на основе этих данных
     *
     * @return array
     */
    private function loadItemsEntity(): array
    {
        $items = loadData($this->appConfig->getPathToItems());
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
     */
    private function loadTeachersEntity(array $itemsIdToInfo): array
    {
        $teachers = loadData($this->appConfig->getPathToTeachers());
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
     */
    private function loadClassEntity(): array
    {
        $classes = loadData($this->appConfig->getPathToClasses());
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
     */
    private function loadLessonEntity(
        array $itemsIdToInfo
    ): array {
        $lessons = loadData($this->appConfig->getPathToLesson());
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
     */
    private function loadReportData(): array
    {
        return loadData($this->appConfig->getPathToAssessmentReport());
    }

    /**
     * Загрузка данных о родителях и создаёт сущности Родители на основе этих данных
     *
     * @return array
     */
    private function loadParentsEntity(): array
    {
        $parents = loadData($this->appConfig->getPathToParents());
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
     */
    private function loadStudentsEntity(): array
    {
        $students = loadData($this->appConfig->getPathToStudents());
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
        $requestParams = $serverRequest->getQueryParams();
        return paramTypeValidation($paramValidations, $requestParams);
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
        $foundReport = [];
        $requestParams = $serverRequest->getQueryParams();
        // Поиск оценок
        $ReportMeetSearchCriteria = null;
        foreach ($reports as $report) {
            //$ReportMeetSearchCriteria = getSearch($request, $report, $appConfig);
            if (array_key_exists(
                'item_name',
                $requestParams
            )) // Поиск по присутвию item_name в GET запросе и совпадению item_name в запросе и массиве оценок. [начало]
            {
                $ReportMeetSearchCriteria = ($requestParams['item_name']
                    === $itemsIdToInfo[$lessonIdToInfo[$report['lesson_id']]
                        ->getItem()
                        ->getId()]
                        ->getName());
            }// Поиск по присутвию item_name в GET запросе и совпадению item_name в запросе и массиве оценок. [конец]
            if (array_key_exists('item_description', $requestParams)) {
                $ReportMeetSearchCriteria = ($requestParams['item_description']
                    === $itemsIdToInfo[$lessonIdToInfo[$report['lesson_id']]
                        ->getItem()
                        ->getId()]
                        ->getDescription());
            }
            if (array_key_exists('lesson_date', $requestParams)) {
                $ReportMeetSearchCriteria = ($requestParams['lesson_date']
                    === $lessonIdToInfo[$report['lesson_id']]
                        ->getDate());
            }
            if (array_key_exists('student_fio', $requestParams)) {
                $ReportMeetSearchCriteria = ($requestParams['student_fio']
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
        return ReportClass::createFromArray($report);
    }

    /**
     * Обработка запроса поиска оценок
     *
     * @param ServerRequest $serverRequest - http запрос
     * @return HttpResponse - http ответ
     * @throws InvalidDataStructureException
     */
    public function __invoke(ServerRequest $serverRequest): HttpResponse
    {
        $this->Log($this->logger, 'assessmentReport" url');
        $resultOfParamValidation = $this->ValidateQueryParams($serverRequest);
        if (null === $resultOfParamValidation) {
            $httpCode=200;
            $itemsIdToInfo = $this->loadItemsEntity();
            $lessonIdToInfo = $this->loadLessonEntity($itemsIdToInfo);
            $studentIdToInfo = $this->loadStudentsEntity();
            $reports = $this->loadReportData();

            $result = $this->searchForAssessmentReportInData(
                $serverRequest,
                $reports,
                $itemsIdToInfo,
                $lessonIdToInfo,
                $studentIdToInfo
            );
        } else {
            $httpCode = 500;
            $result = [
                'status' => 'fail',
                'message' => $resultOfParamValidation
            ];
        }
        return ServerResponseFactory::createJsonResponse($httpCode, $result);
    }
}