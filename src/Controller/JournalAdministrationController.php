<?php

namespace JoJoBizzareCoders\DigitalJournal\Controller;

use JoJoBizzareCoders\DigitalJournal\Infrastructure\Controller\ControllerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\HttpResponse;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerRequest;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerResponseFactory;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\LoggerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\ViewTemplate\ViewTemplateInterface;
use JoJoBizzareCoders\DigitalJournal\Repository\LessonJsonRepository;
use JoJoBizzareCoders\DigitalJournal\Service\SearchAssessmentReportService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\SearchLessonServiceCriteria;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\SearchReportAssessmentCriteria;

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
     * @param LoggerInterface $logger
     * @param SearchAssessmentReportService $reportService
     * @param ViewTemplateInterface $viewTemplate
     * @param SearchLessonService $lessonService
     */
    public function __construct(
        LoggerInterface $logger,
        SearchAssessmentReportService $reportService,
        ViewTemplateInterface $viewTemplate,
        SearchLessonService $lessonService
    ) {
        $this->logger = $logger;
        $this->reportService = $reportService;
        $this->viewTemplate = $viewTemplate;
        $this->lessonService = $lessonService;
    }


    /**
     * @inheritDoc
     */
    public function __invoke(ServerRequest $serverRequest): HttpResponse
    {
        $this->logger->log('run JournalAdministrationController::__invoke');

        $resultCreatingTextDocuments = [];
        if('POST' === $serverRequest->getMethod()){
           // $resultCreatingTextDocuments = $this->creationOfTextDocument($serverRequest);
        }


        $dtoReportCollection = $this->reportService->search(new SearchReportAssessmentCriteria());
        $dtoLessonCollection = $this->lessonService->search(new SearchLessonServiceCriteria());

        $viewData = [
            'reports' => $dtoReportCollection,
            'lesson' => $dtoLessonCollection
        ];

        $context = array_merge($viewData, $resultCreatingTextDocuments);

        $html = $this->viewTemplate->render(
            __DIR__ . '/../../templates/journal.administration.phtml',
            $context,
        );

        return ServerResponseFactory::createHtmlResponse(200, $html);

    }
}