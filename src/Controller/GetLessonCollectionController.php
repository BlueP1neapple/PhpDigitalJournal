<?php

namespace JoJoBizzareCoders\DigitalJournal\Controller;

use JoJoBizzareCoders\DigitalJournal\Entity\ClassClass;
use JoJoBizzareCoders\DigitalJournal\Entity\ItemClass;
use JoJoBizzareCoders\DigitalJournal\Entity\LessonClass;
use JoJoBizzareCoders\DigitalJournal\Entity\TeacherUserClass;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Controller\ControllerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DataLoader\JsonDataLoader;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\HttpResponse;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerRequest;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerResponseFactory;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\LoggerInterface;

use JoJoBizzareCoders\DigitalJournal\Infrastructure\Validator\Assert;

use JsonException;


/**
 * Контроллер отвечающий за поиск занятий
 */
class GetLessonCollectionController implements ControllerInterface
{
    //Свойства
    /**
     * Используемый логгер
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
     * Путь до файла с данными об Учителях
     *
     * @var string
     */
    private string $pathToTeachers;

    /**
     * Путь до файла с данными об Классах
     *
     * @var string
     */
    private string $pathToClasses;

    /**
     * Путь до файла с данными об Занятиях
     *
     * @var string
     */
    private string $pathToLesson;

    //Методы

    /**
     * Конструктор контроллера поиска занятий
     *
     * @param LoggerInterface $logger - Используемый логгер
     * @param string $pathToItems - Путь до файла с данными об предметах
     * @param string $pathToTeachers - Путь до файла с данными об Учителях
     * @param string $pathToClasses - Путь до файла с данными об Классах
     * @param string $pathToLesson - Путь до файла с данными об Занятиях
     */
    public function __construct(
        LoggerInterface $logger,
        string $pathToItems,
        string $pathToTeachers,
        string $pathToClasses,
        string $pathToLesson
    ) {
        $this->logger = $logger;
        $this->pathToItems = $pathToItems;
        $this->pathToTeachers = $pathToTeachers;
        $this->pathToClasses = $pathToClasses;
        $this->pathToLesson = $pathToLesson;
    }

    /**
     * Загрузка сущностей Предметы
     *
     * @return array
     * @throws JsonException
     */
    private function loadEntityItems():array
    {
        $itemsIdToInfo = [];

        $loader = new JsonDataLoader();
        $items = $loader->LoadDate($this->pathToItems);
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
     * @throws JsonException
     */
    private function loadEntityTeachers():array
    {
        $teachersIdToInfo = [];
        $loader = new JsonDataLoader();
        $teachers = $loader->LoadDate($this->pathToTeachers);
        $itemsIdToInfo = $this->loadEntityItems();
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
     * @throws JsonException
     */
    private function loadEntityClasses():array
    {
        $classesIdToInfo = [];
        $loader = new JsonDataLoader();
        $classes = $loader->LoadDate($this->pathToClasses);
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
     * @throws JsonException
     */
    private function LoadDataLesson():array
    {
        return (new JsonDataLoader())->LoadDate($this->pathToLesson);
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
        $params = array_merge($serverRequest->getQueryParams(), $serverRequest->getAttributes());


        return Assert::arrayElementsIsString($paramValidations, $params);
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
        $searchCriteria = array_merge($serverRequest->getQueryParams(), $serverRequest->getAttributes());

        foreach ($lessons as $lesson)
        {
            if (array_key_exists('item_name', $searchCriteria))
            {
                $LessonMeetSearchCriteria = ($searchCriteria['item_name'] === $itemsIdToInfo[$lesson['item_id']]
                        ->getName());
            }
            if (array_key_exists('item_description', $searchCriteria)) {
                $LessonMeetSearchCriteria = ($searchCriteria['item_description'] === $itemsIdToInfo[$lesson['item_id']]
                        ->getDescription());
            }
            if (array_key_exists('lesson_date', $searchCriteria))
            {
                $LessonMeetSearchCriteria = ($searchCriteria['lesson_date'] === $lesson['date']);
            }
            if (array_key_exists('teacher_fio', $searchCriteria))
            {
                $LessonMeetSearchCriteria = ($searchCriteria['teacher_fio'] === $teachersIdToInfo[$lesson['teacher_id']]
                        ->getFio());
            }
            if (array_key_exists('teacher_cabinet', $searchCriteria))
            {
                $LessonMeetSearchCriteria = ((int)$searchCriteria['teacher_cabinet']
                    === $teachersIdToInfo[$lesson['teacher_id']]->getCabinet());
            }
            if (array_key_exists('class_number', $searchCriteria))
            {
                $LessonMeetSearchCriteria = ((int)$searchCriteria['class_number']
                    === $classesIdToInfo[$lesson['class_id']]->getNumber());
            }
            if (array_key_exists('class_letter', $searchCriteria))
            {
                $LessonMeetSearchCriteria = ($searchCriteria['class_letter'] === $classesIdToInfo[$lesson['class_id']]
                        ->getLetter());
            }
            if ($LessonMeetSearchCriteria) {
                $foundLessons[] = $this->lessonFactory(
                    $lesson,
                    $itemsIdToInfo,
                    $teachersIdToInfo,
                    $classesIdToInfo
                );
            }
        }
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
     * @throws JsonException
     */
    public function __invoke(ServerRequest $serverRequest):HttpResponse
    {
        $this->log('dispatch "lesson" url');
        $resultOfParamsValidation=$this->validateQueryParams($serverRequest);
        if (null === $resultOfParamsValidation) {

            $itemsIdToInfo=$this->loadEntityItems();
            $teachersIdToInfo=$this->loadEntityTeachers();
            $classesIdToInfo=$this->loadEntityClasses();
            $lessons=$this->LoadDataLesson();
            $foundLesson=$this->searchLesson($serverRequest, $lessons, $itemsIdToInfo, $teachersIdToInfo, $classesIdToInfo);
            $httpCode = $this->buildHttpCode($foundLesson);
            $result = $this->buildResult($foundLesson);
        }else{
            $httpCode=500;
            $result=[
                'status' => 'fail',
                'message' => $resultOfParamsValidation
            ];
        }
        return ServerResponseFactory::createJsonResponse($httpCode, $result);
    }

    /**
     * Определят httpCode
     * @param array $foundAuthors
     * @return int
     */
    protected function buildHttpCode(array $foundAuthors):int
    {
        return 200;
    }

    /**
     * Подготавливает данныые для ответа
     * @param array $foundAuthors
     * @return array
     */
    protected function buildResult(array $foundAuthors)
    {
        return $foundAuthors;
    }

}