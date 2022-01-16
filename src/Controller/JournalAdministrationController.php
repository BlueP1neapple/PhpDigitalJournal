<?php

namespace JoJoBizzareCoders\DigitalJournal\Controller;

use JoJoBizzareCoders\DigitalJournal\Infrastructure\Controller\ControllerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\HttpResponse;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerRequest;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerResponseFactory;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\LoggerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\ViewTemplate\ViewTemplateInterface;
use JoJoBizzareCoders\DigitalJournal\Repository\LessonJsonRepository;
use JoJoBizzareCoders\DigitalJournal\Service\NewLessonService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchAssessmentReportService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\NewLessonDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\SearchLessonServiceCriteria;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\SearchReportAssessmentCriteria;

use JoJoBizzareCoders\DigitalJournal\Exception;

class JournalAdministrationController implements
    ControllerInterface
{

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
     * @param LoggerInterface $logger
     * @param SearchAssessmentReportService $reportService
     * @param ViewTemplateInterface $viewTemplate
     * @param SearchLessonService $lessonService
     * @param NewLessonService $newLessonService
     */
    public function __construct(
        LoggerInterface $logger,
        SearchAssessmentReportService $reportService,
        ViewTemplateInterface $viewTemplate,
        SearchLessonService $lessonService,
        NewLessonService $newLessonService
    ) {
        $this->logger = $logger;
        $this->reportService = $reportService;
        $this->viewTemplate = $viewTemplate;
        $this->lessonService = $lessonService;
        $this->newLessonService = $newLessonService;
    }


    /**
     * @inheritDoc
     */
    public function __invoke(ServerRequest $serverRequest): HttpResponse
    {
        $this->logger->log('run JournalAdministrationController::__invoke');

        $resultCreatingTextDocuments = [];
        if('POST' === $serverRequest->getMethod()){
            $resultCreatingTextDocuments = $this->creationOfLesson($serverRequest);
        }


        $dtoReportCollection = $this->reportService->search(new SearchReportAssessmentCriteria());
        $dtoLessonCollection = $this->lessonService->search(new SearchLessonServiceCriteria());

        $viewData = [
            'reports' => $dtoReportCollection,
            'lessons' => $dtoLessonCollection
        ];

        $context = array_merge($viewData, $resultCreatingTextDocuments);

        $html = $this->viewTemplate->render(
            __DIR__ . '/../../templates/journal.administration.phtml',
            $context,
        );

        return ServerResponseFactory::createHtmlResponse(200, $html);

    }

    private function creationOfLesson(ServerRequest $serverRequest):array
    {
        $dataToCreate = [];
        parse_str($serverRequest->getBody(),$dataToCreate);

        if(false === array_key_exists('type', $dataToCreate)){
            throw new Exception\RuntimeException('Wryy Отсутствуют данные о создаваемом типе');
        }

        $result = [
            'formValidationResult' =>[
                'lesson' => [],

            ]
        ];

        if('lesson' === $dataToCreate['type']){
            $result['formValidationResult']['lesson'] = $this->validateLesson($dataToCreate);

            if(0 === count($result['formValidationResult']['lesson'])){
                $this->createLesson($dataToCreate);
            }

        }
        else{
            throw new Exception\RuntimeException('Неизвестный тип тексового документа');
        }

        return [];
    }

    private function createLesson(array $dataToCreate):void
    {
        $this->newLessonService->registerLesson(
                new NewLessonDto(
                    (int)$dataToCreate['item_id'],
                    $dataToCreate['date'],
                    (int)$dataToCreate['lesson_duration'],
                    (int)$dataToCreate['teacher_id'],
                    (int)$dataToCreate['class_id'],
                )
        );
    }

    private function validateLesson(array $dataToCreate):array
    {
        $errs = [];
        $errItemId = $this->validateItemId($dataToCreate);
        if(count($errItemId) > 0) {
            $errs = array_merge($errs, $errItemId);
        }

        $errDate = $this->validateDate($dataToCreate);
        if(count($errDate) > 0) {
            $errs = array_merge($errs, $errDate);
        }

        $errLessonDuration = $this->validateLessonDuration($dataToCreate);
        if(count($errLessonDuration) > 0) {
            $errs = array_merge($errs, $errLessonDuration);
        }

        $errTeacherId = $this->validateTeacherId($dataToCreate);
        if(count($errTeacherId) > 0) {
            $errs = array_merge($errs, $errTeacherId);
        }

        $errClassId = $this->validateClassId($dataToCreate);
        if(count($errClassId) > 0) {
            $errs = array_merge($errs, $errClassId);
        }
    }

    private function validateItemId(array $dataToCreate):array
    {
        if(false === array_key_exists('item_id', $dataToCreate)){
            throw new Exception\RuntimeException('Нет id предмета');
        }
        return [];
    }

    private function validateDate(array $dataToCreate):array
    {
        $err = [];
        if(false === array_key_exists('date', $dataToCreate)){
            throw new Exception\RuntimeException('Нет даты');
        }elseif (false === is_string($dataToCreate['date'])){
            throw new Exception\RuntimeException('Данные о дате должны быть строкой');
        }else{
            $DateLength = strlen(trim($dataToCreate['date']));
            $errDate = [];

            if($DateLength > 250){
                $errDate[] = 'Дата не может быть длиннее 250 символов';
            } elseif (0 === $DateLength){
                $errDate[] = 'Дата не может быть пустым';
            }

            if(0 === count($errDate)){
                $errs['date'] = $errDate;
            }
        }

        return $errs;

    }

    private function validateLessonDuration(array $dataToCreate):array
    {
        if(false === array_key_exists('lesson_duration', $dataToCreate)){
            throw new Exception\RuntimeException('Нет длительности урока');
        }elseif (false === is_string($dataToCreate['lesson_duration'])){
            throw new Exception\RuntimeException('Данные о длительности должны быть строкой');
        }else{
            $durationLength = strlen(trim($dataToCreate['lesson_duration']));
            $errDate = [];

            if($durationLength > 250){
                $errDate[] = 'длительность не может быть длиннее 250 символов';
            } elseif (0 === $durationLength){
                $errDate[] = 'длительность не может быть пустым';
            }

            if(0 === count($errDate)){
                $errs['lesson_duration'] = $errDate;
            }
        }

        return $errs;
    }

    private function validateTeacherId(array $dataToCreate):array
    {
        if(false === array_key_exists('teacher_id', $dataToCreate)){
            throw new Exception\RuntimeException('Нет id учителя');
        }
        return [];
    }

    private function validateClassId(array $dataToCreate):array
    {
        if(false === array_key_exists('class_id', $dataToCreate)){
            throw new Exception\RuntimeException('Нет id класса');
        }
        return [];
    }


}