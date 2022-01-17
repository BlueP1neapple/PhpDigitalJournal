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

use JoJoBizzareCoders\DigitalJournal\Service\SearchAssessmentReportService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\AssessmentReportDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\SearchReportAssessmentCriteria;
use JoJoBizzareCoders\DigitalJournal\ValueObject\Address;
use JoJoBizzareCoders\DigitalJournal\ValueObject\Fio;
use JsonException;


/**
 * Контроллер отвечающий за поиск оценок
 */
class GetAssessmentReportCollectionController implements ControllerInterface
{
    /**
     * Использвуемый логгер
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Сервис поиска оценок
     *
     * @var SearchAssessmentReportService
     */
    private SearchAssessmentReportService $assessmentReportService;


    /**
     * Конструктор поиска Оценок
     *
     * @param LoggerInterface $logger - используемый логгер
     * @param SearchAssessmentReportService $SearchAssessmentReportService - сервис поиска оценок
     */
    public function __construct(
        LoggerInterface $logger,
        SearchAssessmentReportService $SearchAssessmentReportService
    ) {
        $this->logger = $logger;
        $this->assessmentReportService = $SearchAssessmentReportService;
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
        $Params=array_merge($serverRequest->getQueryParams(),$serverRequest->getAttributes());
        return Assert::arrayElementsIsString($paramValidations, $Params);
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
        $this->logger->Log('assessmentReport" url');
        $resultOfParamValidation = $this->ValidateQueryParams($serverRequest);
        if (null === $resultOfParamValidation) {
            $params = array_merge($serverRequest->getQueryParams(), $serverRequest->getAttributes());
            $searchAssessmentReportCriteria = new SearchReportAssessmentCriteria();
            $searchAssessmentReportCriteria->setId($params['id'] ?? null);
            $searchAssessmentReportCriteria->setItemName($params['item_name'] ?? null);
            $searchAssessmentReportCriteria->setItemDescription($params['item_description'] ?? null);
            $searchAssessmentReportCriteria->setLessonDate($params['lesson_date'] ?? null);
            $searchAssessmentReportCriteria->setStudentSurname($params['student_fio_surname'] ?? null);
            $searchAssessmentReportCriteria->setStudentName($params['student_fio_name'] ?? null);
            $searchAssessmentReportCriteria->setStudentPatronymic($params['student_fio_patronymic'] ?? null);
            $foundReport = $this->assessmentReportService->search($searchAssessmentReportCriteria);
            $httpCode = $this->buildHttpCode($foundReport);
            $result = $this->buildResult($foundReport);
        } else {
            $httpCode = 500;
            $result = [
                'status' => 'fail',
                'message' => $resultOfParamValidation
            ];
        }
        return ServerResponseFactory::createJsonResponse($httpCode, $result);
    }

    /**
     * Создаёт http код
     *
     * @param array $foundReport - коллекция найденных оценок
     * @return int
     */
    protected function buildHttpCode(array $foundReport):int
    {
        return 200;
    }

    /**
     * Создаёт результат поиска оценок
     *
     * @param AssessmentReportDto[] $foundReports - коллекция найденных оценок
     * @return array|ReportClass
     */
    protected function buildResult(array $foundReports)
    {
        $result = [];
        foreach ($foundReports as $foundReport) {
            $result[] = $this->serializeAuthor($foundReport);
        }
        return $result;
    }

    /**
     * Логика формирования jsonData
     *
     * @param AssessmentReportDto $reportDto - объект dto c информацией об оценках
     * @return array
     */
    protected function serializeAuthor(AssessmentReportDto $reportDto): array
    {
        return [
            'id' => $reportDto->getId(),
            'lesson' => [
                'id' => $reportDto->getLesson()->getId(),
                'item' => [
                    'id' => $reportDto->getLesson()->getItem()->getId(),
                    'name' => $reportDto->getLesson()->getItem()->getName(),
                    'description' => $reportDto->getLesson()->getItem()->getDescription()
                ],
                'date' => $reportDto->getLesson()->getDate(),
                'lessonDuration' => $reportDto->getLesson()->getLessonDuration(),
                'teacher' => [
                    'id' => $reportDto->getLesson()->getTeacher()->getId(),
                    'fio' =>[
                        'surname'=>$reportDto->getLesson()->getTeacher()->getFio()[0]->getSurname(),
                        'name'=>$reportDto->getLesson()->getTeacher()->getFio()[0]->getName(),
                        'patronymic'=>$reportDto->getLesson()->getTeacher()->getFio()[0]->getPatronymic(),
                    ],
                    'dateOfBirth' => $reportDto->getLesson()->getTeacher()->getDateOfBirth(),
                    'phone' => $reportDto->getLesson()->getTeacher()->getPhone(),
                    'address' => [
                        'street'=>$reportDto->getLesson()->getTeacher()->getAddress()[0]->getStreet(),
                        'home'=>$reportDto->getLesson()->getTeacher()->getAddress()[0]->getHome(),
                        'apartment'=>$reportDto->getLesson()->getTeacher()->getAddress()[0]->getApartment(),
                    ],
                    'item' => [
                        'id' => $reportDto->getLesson()->getItem()->getId(),
                        'name' => $reportDto->getLesson()->getItem()->getName(),
                        'description' => $reportDto->getLesson()->getItem()->getDescription()
                    ],
                    'cabinet' => $reportDto->getLesson()->getTeacher()->getCabinet(),
                    'email' => $reportDto->getLesson()->getTeacher()->getEmail()
                ],
                'class' => [
                    'id' => $reportDto->getLesson()->getClass()->getId(),
                    'number' => $reportDto->getLesson()->getClass()->getNumber(),
                    'letter' => $reportDto->getLesson()->getClass()->getLetter()
                ]
            ],
            'student' => [
                'id' => $reportDto->getStudent()->getId(),
                'fio' => [
                    'surname'=>$reportDto->getStudent()->getFio()[0]->getSurname(),
                    'name'=>$reportDto->getStudent()->getFio()[0]->getName(),
                    'patronymic'=>$reportDto->getStudent()->getFio()[0]->getPatronymic()
                ],
                'dateOfBirth' => $reportDto->getStudent()->getDateOfBirth(),
                'phone' => $reportDto->getStudent()->getPhone(),
                'address' => [
                    'street'=>$reportDto->getStudent()->getAddress()[0]->getStreet(),
                    'home'=>$reportDto->getStudent()->getAddress()[0]->getHome(),
                    'apartment'=>$reportDto->getStudent()->getAddress()[0]->getApartment()
                ],
                'class' => [
                    'id' => $reportDto->getStudent()->getClass()->getId(),
                    'number' => $reportDto->getStudent()->getClass()->getNumber(),
                    'letter' => $reportDto->getStudent()->getClass()->getLetter()
                ],
                'parent' => [
                    'id' => $reportDto->getStudent()->getParent()->getId(),
                    'fio' => [
                      'surname'=>$reportDto->getStudent()->getParent()->getFio()[0]->getSurname(),
                      'name'=>$reportDto->getStudent()->getParent()->getFio()[0]->getName(),
                      'patronymic'=>$reportDto->getStudent()->getParent()->getFio()[0]->getPatronymic()
                    ],
                    'dateOfBirth' => $reportDto->getStudent()->getParent()->getDateOfBirth(),
                    'phone' => $reportDto->getStudent()->getParent()->getPhone(),
                    'address' => [
                        'street'=>$reportDto->getStudent()->getParent()->getAddress()[0]->getStreet(),
                        'home'=>$reportDto->getStudent()->getParent()->getAddress()[0]->getHome(),
                        'apartment'=>$reportDto->getStudent()->getParent()->getAddress()[0]->getApartment(),
                    ],
                    'placeOfWork' => $reportDto->getStudent()->getParent()->getPlaceOfWork(),
                    'email' => $reportDto->getStudent()->getParent()->getEmail()
                ]
            ],
            'mark' => $reportDto->getMark()
        ];
    }
}