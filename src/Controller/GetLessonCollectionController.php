<?php

namespace JoJoBizzareCoders\DigitalJournal\Controller;

use JoJoBizzareCoders\DigitalJournal\Infrastructure\Controller\ControllerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerResponseFactory;
use Psr\Log\LoggerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Validator\Assert;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\LessonDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\SearchLessonServiceCriteria;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


/**
 * Контроллер отвечающий за поиск занятий
 */
class GetLessonCollectionController implements ControllerInterface
{
    /**
     * Фабрика для создания http ответов
     *
     * @var ServerResponseFactory
     */
    private ServerResponseFactory $serverResponseFactory;

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
     * @param ServerResponseFactory $serverResponseFactory
     */
    public function __construct(
        LoggerInterface $logger,
        SearchLessonService $searchLessonService,
        ServerResponseFactory $serverResponseFactory
    ) {
        $this->logger = $logger;
        $this->searchLessonService = $searchLessonService;
        $this->serverResponseFactory = $serverResponseFactory;
    }

    /**
     * Валидирует параметры запроса
     *
     * @param ServerRequestInterface $serverRequest - объект сервернного запроса http
     * @return string|null - строка с ошибкой или null если ошибки нет
     */
    private function validateQueryParams(ServerRequestInterface $serverRequest): ?string
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
     * @param ServerRequestInterface $serverRequest - объект серверного http запроса
     * @return ResponseInterface - объект http ответа
     */
    public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
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
        return $this->serverResponseFactory->createJsonResponse($httpCode, $result);
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
     * @return array
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
            'date' => $foundLesson->getDate()->format('Y.d.m H:i'),
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
            'dateOfBirth' => $teacherDto->getDateOfBirth()->format('Y.m.d'),
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