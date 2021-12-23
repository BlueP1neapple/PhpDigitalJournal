<?php

namespace JoJoBizzareCoders\DigitalJournal\Controller;

use JoJoBizzareCoders\DigitalJournal\Entity\ClassClass;
use JoJoBizzareCoders\DigitalJournal\Entity\ItemClass;
use JoJoBizzareCoders\DigitalJournal\Entity\LessonClass;
use JoJoBizzareCoders\DigitalJournal\Entity\TeacherUserClass;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\AppConfig;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\HttpResponse;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerRequest;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerResponseFactory;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\LoggerInterface;

use function JoJoBizzareCoders\DigitalJournal\Infrastructure\loadData;
use function JoJoBizzareCoders\DigitalJournal\Infrastructure\paramTypeValidation;

/**
 * Контроллер отвечающий за поиск занятий
 */
class FoundLesson
{
    //Свойства
    /**
     * Используемый логгер
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * спользуемый конфиг приложения
     *
     * @var AppConfig
     */
    private AppConfig $appConfig;

    //Методы

    /**
     * Конструктор контроллера отвечающего за поиск занятий
     *
     * @param LoggerInterface $logger - используемый логгер
     * @param AppConfig $appConfig - используемый конфиг приложения
     */
    public function __construct(LoggerInterface $logger, AppConfig $appConfig)
    {
        $this->logger = $logger;
        $this->appConfig = $appConfig;
    }

    /**
     * Загрузка сущностей Предметы
     *
     * @return array
     */
    private function loadEntityItems():array
    {
        $itemsIdToInfo = [];
        $items = loadData($this->appConfig->getPathToItems());
        foreach ($items as $item) {
            $itemsObj = ItemClass::createFromArray($item);
            $itemsIdToInfo[$itemsObj->getId()] = $itemsObj;
        }
        return $itemsIdToInfo;
    }

    /**
     * Загрузка сущностей Учителя
     *
     * @return array
     */
    private function loadEntityTeachers():array
    {
        $teachersIdToInfo = [];
        $teachers = loadData($this->appConfig->getPathToTeachers());
        $itemsIdToInfo=$this->loadEntityItems();
        foreach ($teachers as $teacher) {
            $teacher['idItem'] = $itemsIdToInfo[$teacher['idItem']];
            $teachersObj = TeacherUserClass::createFromArray($teacher);
            $teachersIdToInfo[$teachersObj->getId()] = $teachersObj;
        }
        return $teachersIdToInfo;
    }

    /**
     * Загрузка сущностей Классы
     *
     * @return array
     */
    private function loadEntityClasses():array
    {
        $classesIdToInfo = [];
        $classes = loadData($this->appConfig->getPathToClasses());
        foreach ($classes as $class) {
            $classesObj = ClassClass::createFromArray($class);
            $classesIdToInfo[$classesObj->getId()] = $classesObj;
        }
        return $classesIdToInfo;
    }

    /**
     * Загрузка данных о Занятиях из Файла в массив
     *
     * @return array
     */
    private function LoadDataLesson():array
    {
        return loadData($this->appConfig->getPathToLesson());
    }

    /**
     * Метод реализующий логирование в классе
     *
     * @param $msg - логируемое сообщение
     * @return void
     */
    private function Log($msg):void
    {
        $this->logger->Log($msg);
    }

    /**
     * Валидирует параметры запроса
     *
     * @param ServerRequest $serverRequest - объект сервернного запроса http
     * @return string|null - строка с ошибкой или null если ошибки нет
     */
    private function validateQueryParams(ServerRequest $serverRequest): ?string
    {
        $paramValidations = [
            'item_name' => 'Incorrect item name',
            'item_description' => 'Incorrect item description',
            'lesson_date' => 'Incorrect date',
            'teacher_fio' => 'Incorrect teacher fio',
            'teacher_cabinet' => 'Incorrect teacher cabinet',
            'class_number' => 'Incorrect class number',
            'class_letter' => 'Incorrect class letter',
        ];
        $requestParams = $serverRequest->getQueryParams();
        return paramTypeValidation($paramValidations, $requestParams);
    }

    /**
     * Метод реализующий поиск занятия по критериям
     *
     * @param ServerRequest $serverRequest - объект серверного http запроса
     * @param array $lessons - массив данных об занятиях
     * @param array $itemsIdToInfo - массив сущностей Предметов
     * @param array $teachersIdToInfo - массив сузностей Учителей
     * @param array $classesIdToInfo - массив сущностей Классов
     * @return array
     */
    private function searchLesson(
        ServerRequest $serverRequest,
        array $lessons,
        array $itemsIdToInfo,
        array $teachersIdToInfo,
        array $classesIdToInfo
    ):array
    {
        $foundLessons = [];
        $LessonMeetSearchCriteria = null;
        $requestParams = $serverRequest->getQueryParams();
        foreach ($lessons as $lesson) // Цикл по все занятиям. [начало]
        {
            if (array_key_exists(
                'item_name',
                $requestParams
            )) // Поиск по присутвию item_name в GET запросе и совпадению item_name в запросе и массиве занятий. [начало]
            {
                $LessonMeetSearchCriteria = ($requestParams['item_name'] === $itemsIdToInfo[$lesson['item_id']]
                        ->getName());
            }// Поиск по присутвию item_name в GET запросе и совпадению item_name в запросе и массиве занятий. [конец]
            if (array_key_exists(
                'item_description',
                $requestParams
            )) // Поиск по присутвию item_description в GET запросе и совпадению item_description в запросе и массиве занятий. [начало]
            {
                $LessonMeetSearchCriteria = ($requestParams['item_description'] === $itemsIdToInfo[$lesson['item_id']]
                        ->getDescription());
            }// Поиск по присутвию item_description в GET запросе и совпадению item_description в запросе и массиве занятий. [конец]
            if (array_key_exists(
                'lesson_date',
                $requestParams
            )) // Поиск по присутвию date в GET запросе и совпадению date в запросе и массиве занятий. [начало]
            {
                $LessonMeetSearchCriteria = ($requestParams['lesson_date'] === $lesson['date']);
            }// Поиск по присутвию date в GET запросе и совпадению date в запросе и массиве занятий. [конец]
            if (array_key_exists(
                'teacher_fio',
                $requestParams
            )) // Поиск по присутвию teacher_fio в GET запросе и совпадению teacher_fio в запросе и массиве занятий. [начало]
            {
                $LessonMeetSearchCriteria = ($requestParams['teacher_fio'] === $teachersIdToInfo[$lesson['teacher_id']]
                        ->getFio());
            }// Поиск по присутвию teacher_fio в GET запросе и совпадению teacher_fio в запросе и массиве занятий. [конец]
            if (array_key_exists(
                'teacher_cabinet',
                $requestParams
            )) // Поиск по присутвию teacher_cabinet в GET запросе и совпадению teacher_cabinet в запросе и массиве занятий. [начало]
            {
                $LessonMeetSearchCriteria = ((int)$requestParams['teacher_cabinet']
                    === $teachersIdToInfo[$lesson['teacher_id']]->getCabinet());
            }// Поиск по присутвию teacher_cabinet в GET запросе и совпадению teacher_cabinet в запросе и массиве занятий. [конец]
            if (array_key_exists(
                'class_number',
                $requestParams
            )) // Поиск по присутвию class_number в GET запросе и совпадению class_number в запросе и массиве занятий. [начало]
            {
                $LessonMeetSearchCriteria = ((int)$requestParams['class_number']
                    === $classesIdToInfo[$lesson['class_id']]->getNumber());
            }// Поиск по присутвию class_number в GET запросе и совпадению class_number в запросе и массиве занятий. [конец]
            if (array_key_exists(
                'class_letter',
                $requestParams
            )) // Поиск по присутвию class_letter в GET запросе и совпадению class_letter в запросе и массиве занятий. [начало]
            {
                $LessonMeetSearchCriteria = ($requestParams['class_letter'] === $classesIdToInfo[$lesson['class_id']]
                        ->getLetter());
            }// Поиск по присутвию class_letter в GET запросе и совпадению class_letter в запросе и массиве занятий. [конец]


            if ($LessonMeetSearchCriteria) { // Отбор найденных занятий
                $foundLessons[] = $this->lessonFactory(
                    $lesson,
                    $itemsIdToInfo,
                    $teachersIdToInfo,
                    $classesIdToInfo
                );
            }
        }  //Цикл по все занятиям. [конец]
        $this->log('found lessons' . count($foundLessons));
        return $foundLessons;
    }

    /**
     * Метод отвечающий за создание объекта Занятия
     *
     * @param array $lesson - отобранное по критериям занятие
     * @param array $itemsIdToInfo - мвссив сущностей Придметы
     * @param array $teachersIdToInfo - массив сущностей Учителя
     * @param array $classesIdToInfo - массив сущностей Классы
     * @return LessonClass - объект класса Lesson
     */
    private function lessonFactory(
        array $lesson,
        array $itemsIdToInfo,
        array $teachersIdToInfo,
        array $classesIdToInfo
    ):LessonClass
    {
        $lesson['item_id'] = $itemsIdToInfo[$lesson['item_id']];
        $lesson['teacher_id'] = $teachersIdToInfo[$lesson['teacher_id']];
        $lesson['class_id'] = $classesIdToInfo[$lesson['class_id']];
        return LessonClass::createFromArray($lesson);
    }

    /**
     * Обработка запроса поиска занятия
     *
     * @param ServerRequest $serverRequest - объект серверного http запроса
     * @return HttpResponse - объект http ответа
     */
    public function __invoke(ServerRequest $serverRequest):HttpResponse
    {
        $this->log('dispatch "lesson" url');
        $resultOfParamsValidation=$this->validateQueryParams($serverRequest);
        if (null === $resultOfParamsValidation) {
            $httpCode=200;
            $itemsIdToInfo=$this->loadEntityItems();
            $teachersIdToInfo=$this->loadEntityTeachers();
            $classesIdToInfo=$this->loadEntityClasses();
            $lessons=$this->LoadDataLesson();
            $result=$this->searchLesson(
                $serverRequest,
                $lessons,
                $itemsIdToInfo,
                $teachersIdToInfo,
                $classesIdToInfo
            );
        }else{
            $httpCode=500;
            $result=[
                'status' => 'fail',
                'message' => $resultOfParamsValidation
            ];
        }
        return ServerResponseFactory::createJsonResponse($httpCode, $result);
    }
}