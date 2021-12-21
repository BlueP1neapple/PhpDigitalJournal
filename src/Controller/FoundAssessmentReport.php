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
    /**
     * Загрузка данных о предметах и создаёт сущности Предметов на основе этих данных
     *
     * @param AppConfig $appConfig - конфиг приложения
     * @return array
     */
    private function loadItemsEntity(AppConfig $appConfig): array
    {
        $items = loadData($appConfig->getPathToItems());
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
     * @param AppConfig $appConfig - конфиг приложения
     * @param array $itemsIdToInfo - Сущности Предметов
     * @return array
     */
    private function loadTeachersEntity(AppConfig $appConfig, array $itemsIdToInfo): array
    {
        $teachers = loadData($appConfig->getPathToTeachers());
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
     * @param AppConfig $appConfig - конфиг приложения
     * @return array
     */
    private function loadClassEntity(AppConfig $appConfig): array
    {
        $classes = loadData($appConfig->getPathToClasses());
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
     * @param AppConfig $appConfig - конфиг приложения
     * @param array $itemsIdToInfo - сущности Предметов
     * @param array $teachersIdToInfo - сущности Учителя
     * @param array $classesIdToInfo - Сущности Классы
     * @return array
     */
    private function loadLessonEntity(
        AppConfig $appConfig,
        array $itemsIdToInfo,
        array $teachersIdToInfo,
        array $classesIdToInfo
    ): array {
        $lessons = loadData($appConfig->getPathToLesson());
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
     * @param AppConfig $appConfig - конфиг приложения
     * @return array
     */
    private function loadReportData(AppConfig $appConfig): array
    {
        return loadData($appConfig->getPathToAssessmentReport());
    }

    /**
     * Загрузка данных о родителях и создаёт сущности Родители на основе этих данных
     *
     * @param AppConfig $appConfig - конфиг приложения
     * @return array
     */
    private function loadParentsEntity(AppConfig $appConfig): array
    {
        $parents = loadData($appConfig->getPathToParents());
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
     * @param AppConfig $appConfig - конфиг приложения
     * @param array $classesIdToInfo - сущности Классы
     * @param array $parentIdToInfo - сущности Родители
     * @return array
     */
    private function loadStudentsEntity(AppConfig $appConfig, array $classesIdToInfo, array $parentIdToInfo): array
    {
        $students = loadData($appConfig->getPathToStudents());
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
     * @param LoggerInterface $logger - логгер
     * @param array $reports - массив данных оценок
     * @param array $itemsIdToInfo - сущность Предметов
     * @param array $lessonIdToInfo - массив сущностей Занятий
     * @param array $studentIdToInfo - массив сущностей Студентов
     * @return array
     */
    private function searchForAssessmentReportInData(
        ServerRequest $serverRequest,
        LoggerInterface $logger,
        array $reports,
        array $itemsIdToInfo,
        array $lessonIdToInfo,
        array $studentIdToInfo
    ): array {
        $foundReport = [];
        $requestParams = $serverRequest->getQueryParams();
        // Поиск оценок
        foreach ($reports as $report) {
            //$ReportMeetSearchCriteria = getSearch($request, $report, $appConfig);
            $ReportMeetSearchCriteria = null;
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
                $report['lesson_id'] = $lessonIdToInfo[$report['lesson_id']];
                $report['student_id'] = $studentIdToInfo[$report['student_id']];
                $foundReport[] = ReportClass::createFromArray($report);
            }
        }//Цикл по оценкам [конец]

        $this->Log($logger,'found Report' . count($foundReport));
        return $foundReport;
    }


    private function ReportFactory (): ReportClass
    {
        //TODO Доделать класс поиска оценок, доделать метод отвечающий за реализацию логики создания текствого документа
    }

    /**
     * Обработка запроса поиска оценок
     *
     * @param ServerRequest $request - http запрос
     * @param LoggerInterface $logger - название функции логирования
     * @param AppConfig $appConfig - конфигурация приложения
     * @return HttpResponse - http ответ
     * @throws InvalidDataStructureException
     */
    public function __invoke(ServerRequest $request, LoggerInterface $logger, AppConfig $appConfig): HttpResponse
    {
        $items = loadData($appConfig->getPathToItems());
        $teachers = loadData($appConfig->getPathToTeachers());
        $classes = loadData($appConfig->getPathToClasses());
        $lessons = loadData($appConfig->getPathToLesson());
        $reports = loadData($appConfig->getPathToAssessmentReport());
        $students = loadData($appConfig->getPathToStudents());
        $parents = loadData($appConfig->getPathToParents());

        $logger->log('assessmentReport" url');

        $paramValidations = [
            'item_name' => 'Incorrect item name',
            'item_description' => 'Incorrect item description',
            'lesson_date' => 'Incorrect lesson date',
            'student_fio' => 'Incorrect student fio',
        ];
        $requestParams = $request->getQueryParams();

        if (null === ($result = paramTypeValidation($paramValidations, $requestParams))) {
            //Хэшмапирование
            $foundReport = [];
            $itemsIdToInfo = [];
            $teachersIdToInfo = [];
            $classesIdToInfo = [];
            $lessonIdToInfo = [];
            $studentIdToInfo = [];
            $parentIdToInfo = [];

            foreach ($items as $item) {
                $itemsObj = ItemClass::createFromArray($item);
                $itemsIdToInfo[$itemsObj->getId()] = $itemsObj;
            }

            foreach ($teachers as $teacher) {
                $teacher['idItem'] = $itemsIdToInfo[$teacher['idItem']];
                $teachersObj = TeacherUserClass::createFromArray($teacher);
                $teachersIdToInfo[$teachersObj->getId()] = $teachersObj;
            }

            foreach ($classes as $class) {
                $classesObj = ClassClass::createFromArray($class);
                $classesIdToInfo[$classesObj->getId()] = $classesObj;
            }

            foreach ($lessons as $lesson) {
                $lesson['item_id'] = $itemsIdToInfo[$lesson['item_id']];
                $lesson['teacher_id'] = $teachersIdToInfo[$lesson['teacher_id']];
                $lesson['class_id'] = $classesIdToInfo[$lesson['class_id']];
                $lessonsObj = LessonClass::createFromArray($lesson);
                $lessonIdToInfo[$lessonsObj->getId()] = $lessonsObj;
            }

            foreach ($parents as $parent) {
                $parentsObj = ParentUserClass::createFromArray($parent);
                $parentIdToInfo[$parentsObj->getId()] = $parentsObj;
            }

            foreach ($students as $student) {
                $student['class_id'] = $classesIdToInfo[$student['class_id']];
                $student['parent_id'] = $parentIdToInfo[$student['parent_id']];
                $studentsObj = StudentUserClass::createFromArray($student);
                $studentIdToInfo[$studentsObj->getId()] = $studentsObj;
            }

            // Поиск оценок
            foreach ($reports as $report) {
                //$ReportMeetSearchCriteria = getSearch($request, $report, $appConfig);
                $ReportMeetSearchCriteria = null;
                if (array_key_exists(
                    'item_name',
                    $requestParams
                )) // Поиск по присутвию item_name в GET запросе и совпадению item_name в запросе и массиве оценок. [начало]
                {
                    $ReportMeetSearchCriteria = ($requestParams['item_name'] === $itemsIdToInfo[$lessonIdToInfo[$report['lesson_id']]->getItem(
                        )->getId()]->getName());
                }// Поиск по присутвию item_name в GET запросе и совпадению item_name в запросе и массиве оценок. [конец]
                if (array_key_exists('item_description', $requestParams)) {
                    $ReportMeetSearchCriteria = ($requestParams['item_description'] === $itemsIdToInfo[$lessonIdToInfo[$report['lesson_id']]->getItem(
                        )->getId()]->getDescription());
                }
                if (array_key_exists('lesson_date', $requestParams)) {
                    $ReportMeetSearchCriteria = ($requestParams['lesson_date'] === $lessonIdToInfo[$report['lesson_id']]->getDate(
                        ));
                }
                if (array_key_exists('student_fio', $requestParams)) {
                    $ReportMeetSearchCriteria = ($requestParams['student_fio'] === $studentIdToInfo[$report['student_id']]->getFio(
                        ));
                }


                if ($ReportMeetSearchCriteria) { // Отбор наёденных оценок
                    $report['lesson_id'] = $lessonIdToInfo[$report['lesson_id']];
                    $report['student_id'] = $studentIdToInfo[$report['student_id']];
                    $foundReport[] = ReportClass::createFromArray($report);
                }
            }//Цикл по оценкам [конец]

            $logger->log('found Report' . count($foundReport));

            $result = [
                'httpCode' => 200,
                'result' => $foundReport
            ];
        }
        return ServerResponseFactory::createJsonResponse($result['httpCode'], $result['result']);
    }
}