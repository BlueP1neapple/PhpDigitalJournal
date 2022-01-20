<?php

namespace JoJoBizzareCoders\DigitalJournal\Controller;

use JoJoBizzareCoders\DigitalJournal\Entity\LessonClass;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Controller\ControllerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\HttpResponse;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerRequest;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerResponseFactory;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\LoggerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Validator\Assert;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\LessonDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\SearchLessonServiceCriteria;
use JsonException;


/**
 * Контроллер отвечающий за поиск занятий
 */
class GetLessonCollectionController implements ControllerInterface
{
    /**
     * Используемый логгер
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Сервис поиска занятий
     *
     * @var SearchLessonService
     */
    private SearchLessonService $searchLessonService;

    /**
     * Конструктор контроллера поиска занятий
     *
     * @param LoggerInterface $logger - Используемый логгер
     * @param SearchLessonService $searchLessonService
     */
    public function __construct(
        LoggerInterface $logger,
        SearchLessonService $searchLessonService
    ) {
        $this->logger = $logger;
        $this->searchLessonService = $searchLessonService;
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
        $Params = array_merge($serverRequest->getQueryParams(), $serverRequest->getAttributes());
        return Assert::arrayElementsIsString($paramValidations, $Params);
    }

    /**
     * Обработка запроса поиска занятия
     *
     * @param ServerRequest $serverRequest - объект серверного http запроса
     * @return HttpResponse - объект http ответа
     * @throws JsonException
     */
    public function __invoke(ServerRequest $serverRequest): HttpResponse
    {
        $this->logger->info('dispatch "lesson" url');
        $resultOfParamsValidation = $this->validateQueryParams($serverRequest);
        if (null === $resultOfParamsValidation) {
            $params = array_merge($serverRequest->getQueryParams(), $serverRequest->getAttributes());
            $searchLessonServiceCriteria = new SearchLessonServiceCriteria();
            $searchLessonServiceCriteria->setItemName($params['item_name'] ?? null);
            $searchLessonServiceCriteria->setItemDescription($params['item_description'] ?? null);
            $searchLessonServiceCriteria->setDate($params['lesson_date'] ?? null);
            $searchLessonServiceCriteria->setTeacherCabinet($params['teacher_cabinet'] ?? null);
            $searchLessonServiceCriteria->setClassNumber($params['class_number'] ?? null);
            $searchLessonServiceCriteria->setClassLetter($params['class_letter'] ?? null);
            $searchLessonServiceCriteria->setId($params['id'] ?? null);
            $searchLessonServiceCriteria->setTeacherSurname($params['teacher_fio_surname'] ?? null);
            $searchLessonServiceCriteria->setTeacherName($params['teacher_fio_name'] ?? null);
            $searchLessonServiceCriteria->setTeacherPatronymic($params['teacher_fio_patronymic'] ?? null);
            $foundLessons = $this->searchLessonService->search($searchLessonServiceCriteria);
            $httpCode = $this->buildHttpCode($foundLessons);
            $result = $this->buildResult($foundLessons);
        } else {
            $httpCode = 500;
            $result = [
                'status' => 'fail',
                'message' => $resultOfParamsValidation
            ];
        }
        return ServerResponseFactory::createJsonResponse($httpCode, $result);
    }


    /**
     * Создаёт http код
     *
     * @param array $foundLesson - коллекция найденных занятий
     * @return int
     */
    protected function buildHttpCode(array $foundLesson): int
    {
        return 200;
    }

    /**
     * Создаёт результат поиска занятий
     *
     * @param LessonDto[] $foundLessons - коллекция найденных занятий
     * @return array|LessonClass
     */
    protected function buildResult(array $foundLessons)
    {
        $result = [];
        foreach ($foundLessons as $foundLesson) {
            $result[]=$this->serializeLesson($foundLesson);
        }
        return $result;
    }

    /**
     * Серилизация dto объекта с информацией об занятиях
     *
     * @param LessonDto $foundLesson - dto объект с информацией об занятиях
     * @return array
     */
    final protected function serializeLesson(LessonDto $foundLesson): array
    {
        $jsonData = [
            'id' => $foundLesson->getId(),
            'date' => $foundLesson->getDate(),
            'lessonDuration' => $foundLesson->getLessonDuration(),
        ];
        $itemDto = $foundLesson->getItem();
        $jsonData['item'] = [
            'id' => $itemDto->getId(),
            'name' => $itemDto->getName(),
            'description' => $itemDto->getDescription()
        ];
        $teacherDto = $foundLesson->getTeacher();
        $teacherFioDto=$teacherDto->getFio();
        $teacherAddressDto = $teacherDto->getAddress();
        $jsonData['teacher'] = [
            'id' => $teacherDto->getId(),
            'fio' => [
                'surname'=>$teacherFioDto[0]->getSurname(),
                'name'=>$teacherFioDto[0]->getName(),
                'patronymic'=>$teacherFioDto[0]->getPatronymic()
            ],
            'dateOfBirth' => $teacherDto->getDateOfBirth(),
            'phone' => $teacherDto->getPhone(),
            'address' => [
                'street'=>$teacherAddressDto->getStreet(),
                'home'=>$teacherAddressDto->getHome(),
                'apartment'=>$teacherAddressDto->getApartment()
            ],
            'item' => $jsonData['item'],
            'cabinet' => $teacherDto->getCabinet(),
            'email' => $teacherDto->getEmail()
        ];
        $classDto = $foundLesson->getClass();
        $jsonData['class'] = [
            'id' => $classDto->getId(),
            'number' => $classDto->getNumber(),
            'letter' => $classDto->getLetter()
        ];
        return $jsonData;
    }
}