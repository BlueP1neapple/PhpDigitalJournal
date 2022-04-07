<?php

namespace JoJoBizzareCoders\DigitalJournal\Controller;

use Doctrine\ORM\EntityManagerInterface;
use JoJoBizzareCoders\DigitalJournal\Exception;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Auth\HttpAuthProvider;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Controller\ControllerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerResponseFactory;
use Psr\Log\LoggerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\ViewTemplate\ViewTemplateInterface;
use JoJoBizzareCoders\DigitalJournal\Service\NewItemService;
use JoJoBizzareCoders\DigitalJournal\Service\NewItemService\NewItemDto;
use JoJoBizzareCoders\DigitalJournal\Service\NewLessonService;
use JoJoBizzareCoders\DigitalJournal\Service\NewReportService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchAssessmentReportService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchClassService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchItemService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\NewLessonDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\SearchLessonServiceCriteria;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\NewAssessmentReportDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\SearchReportAssessmentCriteria;
use JoJoBizzareCoders\DigitalJournal\Service\SearchStudentService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchTeacherService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class JournalAdministrationController implements
    ControllerInterface
{
    /**
     * Фабрика для создания http ответов
     *
     * @var ServerResponseFactory
     */
    private ServerResponseFactory $serverResponseFactory;

    /**
     * Логгер
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;


    /**
     * Сервис поиска оценок
     *
     * @var SearchAssessmentReportService
     */
    private SearchAssessmentReportService $reportService;

    /**
     *Шаблон рендера
     *
     * @var ViewTemplateInterface
     */
    private ViewTemplateInterface $viewTemplate;

    /**
     * Сервис поиска уроков
     *
     * @var SearchLessonService
     */
    private SearchLessonService $lessonService;

    /**
     * Сервис создания урока
     *
     * @var NewLessonService
     */
    private NewLessonService $newLessonService;

    /**
     * Сервис предметов
     *
     * @var SearchItemService
     */
    private SearchItemService $itemService;

    /**
     * Сервис учетелей
     *
     * @var SearchTeacherService
     */
    private SearchTeacherService $teacherService;

    /**
     * Сервис классов
     *
     * @var SearchClassService
     */
    private SearchClassService $classService;

    /**
     * Сервис по созданию репортов
     *
     * @var NewReportService
     */
    private NewReportService $newReportService;

    /**
     *  аунтификация
     *
     * @var HttpAuthProvider
     */
    private HttpAuthProvider $httpAuthProvider;

    /**
     * Поиск студентов
     *
     * @var SearchStudentService
     */
    private SearchStudentService $searchStudentService;

    /**
     * Сервис создания предмета
     *
     * @var NewItemService
     */
    private NewItemService $newItemService;

    private EntityManagerInterface $entityManager;

    /**
     * @param LoggerInterface $logger
     * @param SearchAssessmentReportService $reportService
     * @param ViewTemplateInterface $viewTemplate
     * @param SearchLessonService $lessonService
     * @param NewLessonService $newLessonService
     * @param SearchItemService $itemService
     * @param SearchTeacherService $teacherService
     * @param SearchClassService $classService
     * @param NewReportService $newReportService
     * @param SearchStudentService $searchStudentService
     * @param HttpAuthProvider $httpAuthProvider
     * @param NewItemService $newItemService
     * @param ServerResponseFactory $serverResponseFactory
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        LoggerInterface $logger,
        SearchAssessmentReportService $reportService,
        ViewTemplateInterface $viewTemplate,
        SearchLessonService $lessonService,
        NewLessonService $newLessonService,
        SearchItemService $itemService,
        SearchTeacherService $teacherService,
        SearchClassService $classService,
        NewReportService $newReportService,
        SearchStudentService $searchStudentService,
        HttpAuthProvider $httpAuthProvider,
        NewItemService $newItemService,
        ServerResponseFactory $serverResponseFactory,
        EntityManagerInterface $entityManager
    ) {
        $this->logger = $logger;
        $this->reportService = $reportService;
        $this->viewTemplate = $viewTemplate;
        $this->lessonService = $lessonService;
        $this->newLessonService = $newLessonService;
        $this->itemService = $itemService;
        $this->teacherService = $teacherService;
        $this->classService = $classService;
        $this->newReportService = $newReportService;
        $this->searchStudentService = $searchStudentService;
        $this->httpAuthProvider = $httpAuthProvider;
        $this->newItemService = $newItemService;
        $this->serverResponseFactory = $serverResponseFactory;
        $this->entityManager = $entityManager;
    }


    /**
     * @inheritDoc
     */
    public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
    {
        try {
            $this->entityManager->beginTransaction();
            if (false === $this->httpAuthProvider->isAuth()) {
                return $this->httpAuthProvider->doAuth($serverRequest->getUri());
            }

            $this->logger->info('run JournalAdministrationController::__invoke');

            $resultCreatingTextDocuments = [];
            if ('POST' === $serverRequest->getMethod()) {
                $resultCreatingTextDocuments = $this->creationOfLesson($serverRequest);
            }


            $dtoReportCollection = $this->reportService->search(new SearchReportAssessmentCriteria());
            $dtoLessonCollection = $this->lessonService->search(new SearchLessonServiceCriteria());
            $dtoItemCollection = $this->itemService->search();
            $dtoTeacherCollection = $this->teacherService->search();
            $dtoClassCollection = $this->classService->search();
            $dtoStudentCollection = $this->searchStudentService->search();

            $viewData = [
                'reports' => $dtoReportCollection,
                'lessons' => $dtoLessonCollection,
                'items' => $dtoItemCollection,
                'teachers' => $dtoTeacherCollection,
                'classes' => $dtoClassCollection,
                'students' => $dtoStudentCollection
            ];

            $context = array_merge($viewData, $resultCreatingTextDocuments);
            $template = 'journal.administration.twig';
            $httpCode = 200;
            $this->entityManager->commit();
            $this->entityManager->flush();
        } catch (Throwable $e) {
            $this->entityManager->rollback();
            $httpCode = 500;
            $template = 'errors.twig';
            $context = [
                'errors' => [
                    $e->getMessage()
                ]
            ];
        }

        $html = $this->viewTemplate->render(
            $template,
            $context,
        );

        return $this->serverResponseFactory->createHtmlResponse($httpCode, $html);
    }

    private function creationOfLesson(ServerRequestInterface $serverRequest): array
    {
        $dataToCreate = [];
        parse_str($serverRequest->getBody(), $dataToCreate);

        if (false === array_key_exists('type', $dataToCreate)) {
            throw new Exception\RuntimeException('Wryy Отсутствуют данные о создаваемом типе');
        }

        $result = [
            'formValidationResult' => [
                'lesson' => [],
                'report' => [],
                'item' => [],

            ]
        ];

        if ('lesson' === $dataToCreate['type']) {
            $result['formValidationResult']['lesson'] = $this->validateLesson($dataToCreate);

            if (0 === count($result['formValidationResult']['lesson'])) {
                $this->createLesson($dataToCreate);
            }
        } elseif ('report' === $dataToCreate['type']) {
            $result['formValidationResult']['report'] = $this->validateReport($dataToCreate);

            if (0 === count($result['formValidationResult']['report'])) {
                $this->createReport($dataToCreate);
            }
        } elseif ('item' === $dataToCreate['type']) {
            $result['formValidationResult']['item'] = $this->validateItem($dataToCreate);

            if (0 === count($result['formValidationResult']['item'])) {
                $this->createItem($dataToCreate);
            }
        } else {
            throw new Exception\RuntimeException('Неизвестный тип');
        }
        return $result;
    }

    /**
     * Функция для создания реперта
     *
     * @param array $dataToCreate
     */
    private function createReport(array $dataToCreate): void
    {
        $this->newReportService->registerAssessmentReport(
            new NewAssessmentReportDto(
                (int)$dataToCreate['lesson_id'],
                (int)$dataToCreate['student_id'],
                (int)$dataToCreate['mark']
            )
        );
    }

    /**
     * Функция для создания урока
     *
     * @param array $dataToCreate
     */
    private function createLesson(array $dataToCreate): void
    {
        $this->newLessonService->registerLesson(
            new NewLessonDto(
                (int)$dataToCreate['item_id'],
                $this->createDate($dataToCreate),
                (int)$dataToCreate['lesson_duration'],
                (int)$dataToCreate['teacher_id'],
                (int)$dataToCreate['class_id'],
            )
        );
    }

    /**
     * Функция для создания предмета
     *
     * @param array $dataToCreate
     */
    private function createItem(array $dataToCreate): void
    {
        $this->newItemService->registerItem(
            new NewItemDto(
                $dataToCreate['name'],
                $dataToCreate['description']
            )
        );
    }

    /**
     * Создаёт дату и время в нужном формате
     *
     * @param array $dataToCreate
     * @return string
     */
    private function createDate(array $dataToCreate): string
    {
        $date = $dataToCreate['date'];
        $time = $dataToCreate['time'];

        return date("Y.m.d", strtotime($date)) . " " . $time;
    }

    private function validateLesson(array $dataToCreate): array
    {
        $errs = [];
        $errItemId = $this->validateItemId($dataToCreate);
        if (count($errItemId) > 0) {
            $errs = array_merge($errs, $errItemId);
        }

        $errDate = $this->validateDate($dataToCreate);
        if (count($errDate) > 0) {
            $errs = array_merge($errs, $errDate);
        }

        $errLessonDuration = $this->validateLessonDuration($dataToCreate);
        if (count($errLessonDuration) > 0) {
            $errs = array_merge($errs, $errLessonDuration);
        }

        $errTeacherId = $this->validateTeacherId($dataToCreate);
        if (count($errTeacherId) > 0) {
            $errs = array_merge($errs, $errTeacherId);
        }

        $errClassId = $this->validateClassId($dataToCreate);
        if (count($errClassId) > 0) {
            $errs = array_merge($errs, $errClassId);
        }
        return $errs;
    }

    private function validateItemId(array $dataToCreate): array
    {
        if (false === array_key_exists('item_id', $dataToCreate)) {
            throw new Exception\RuntimeException('Нет id предмета');
        }
        return [];
    }

    private function validateDate(array $dataToCreate): array
    {
        $errs = [];
        if (false === array_key_exists('date', $dataToCreate)) {
            throw new Exception\RuntimeException('Нет даты');
        }

        if (false === is_string($dataToCreate['date'])) {
            throw new Exception\RuntimeException('Данные о дате должны быть строкой');
        }

        $DateLength = strlen(trim($dataToCreate['date']));
        $errDate = [];

        if ($DateLength > 250) {
            $errDate[] = 'Дата не может быть длиннее 250 символов';
        } elseif (0 === $DateLength) {
            $errDate[] = 'Дата не может быть пустым';
        }

        if (0 !== count($errDate)) {
            $errs['date'] = $errDate;
        }

        return $errs;
    }

    private function validateLessonDuration(array $dataToCreate): array
    {
        $errs = [];
        if (false === array_key_exists('lesson_duration', $dataToCreate)) {
            throw new Exception\RuntimeException('Нет длительности урока');
        }

        if (false === is_string($dataToCreate['lesson_duration'])) {
            throw new Exception\RuntimeException('Данные о длительности должны быть строкой');
        }

        $durationLength = strlen(trim($dataToCreate['lesson_duration']));
        $errDate = [];

        if ($durationLength > 250) {
            $errDate[] = 'длительность не может быть длиннее 250 символов';
        } elseif (0 === $durationLength) {
            $errDate[] = 'длительность не может быть пустым';
        }

        if (0 !== count($errDate)) {
            $errs['lesson_duration'] = $errDate;
        }

        return $errs;
    }

    private function validateTeacherId(array $dataToCreate): array
    {
        if (false === array_key_exists('teacher_id', $dataToCreate)) {
            throw new Exception\RuntimeException('Нет id учителя');
        }
        return [];
    }

    private function validateClassId(array $dataToCreate): array
    {
        if (false === array_key_exists('class_id', $dataToCreate)) {
            throw new Exception\RuntimeException('Нет id класса');
        }
        return [];
    }

    private function validateReport(array $dataToCreate): array
    {
         //Юзер не может ввести не коректные данные
        return [];
    }

    private function validateItem(array $dataToCreate): array
    {
        $errs = [];

        if (false === array_key_exists('name', $dataToCreate)) {
            throw new Exception\RuntimeException('Нет данных о имени предмета');
        }

        if (false === is_string($dataToCreate['name'])) {
            throw new Exception\RuntimeException('Данные о передмете должны быть строкой');
        }

        $titleLength = strlen(trim($dataToCreate['name']));
        $errTitle = [];

        if ($titleLength > 250) {
            $errTitle[] = 'Название не может быть длиннее 250 символов';
        } elseif (0 === $titleLength) {
            $errTitle[] = 'Название не может быть пустым';
        }

        if (0 !== count($errTitle)) {
            $errs['name'] = $errTitle;
        }

        if (false === array_key_exists('description', $dataToCreate)) {
            throw new Exception\RuntimeException('Нет данных о расшифровке предмета');
        }

        if (false === is_string($dataToCreate['description'])) {
            throw new Exception\RuntimeException('Данные о расшифровке должны быть строкой');
        }

        $titleLength = strlen(trim($dataToCreate['description']));
        $errTitle = [];

        if ($titleLength > 250) {
            $errTitle[] = 'Расшифровка не может быть длиннее 250 символов';
        } elseif (0 === $titleLength) {
            $errTitle[] = 'Расшифровка не может быть пустым';
        }

        if (0 !== count($errTitle)) {
            $errs['description'] = $errTitle;
        }


        return $errs;
    }
}
