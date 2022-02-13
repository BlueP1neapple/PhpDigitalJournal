<?php

namespace JoJoBizzareCoders\DigitalJournal\Controller;

use JoJoBizzareCoders\DigitalJournal\Infrastructure\Controller\ControllerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerResponseFactory;
use JoJoBizzareCoders\DigitalJournal\Service\NewReportService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\NewAssessmentReportDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\ResultRegisteringAssessmentReportDto;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class CreateRegisterAssessmentReportController implements
    ControllerInterface
{

    /**
     * Фабрика для создания http ответов
     *
     * @var ServerResponseFactory
     */
    private ServerResponseFactory $serverResponseFactory;

    /**
     * Сервис по добавдению новой оценки
     *
     * @var NewReportService
     */
    private NewReportService $reportService;

    /**
     * @param NewReportService $reportService - Сервис по добавдению новой оценки
     * @param ServerResponseFactory $serverResponseFactory
     */
    public function __construct(NewReportService $reportService,
        ServerResponseFactory $serverResponseFactory
    )
    {
        $this->reportService = $reportService;
        $this->serverResponseFactory = $serverResponseFactory;
    }


    /**
     * @inheritDoc
     */
    public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
    {
        try{
            $requestData = json_decode($serverRequest->getBody(), true, 10, JSON_THROW_ON_ERROR);
            $validationResult = $this->validateData($requestData);
            if(0 === count($validationResult)){
                $responseDto = $this->runService($requestData);
                $httpCode = 201;
                $jsonData = $this->buildJsonData($responseDto);
            }else{
                $httpCode = 400;
                $jsonData = [
                    'status' => 'fail',
                    'message' => implode('. ', $validationResult)
                ];
            }
        }catch (Throwable $e){
            $httpCode = 500;
            $jsonData = [
                'status' => 'fail',
                'message' => $e->getMessage()
            ];
        }
        return $this->serverResponseFactory->createJsonResponse($httpCode, $jsonData);
    }

    /**
     * Запуск сервиса
     *
     * @param array $requestData
     * @return ResultRegisteringAssessmentReportDto
     */
    private function runService(array $requestData):ResultRegisteringAssessmentReportDto
    {
        $requestDto = new NewAssessmentReportDto(
            $requestData['lesson_id'],
            $requestData['student_id'],
            $requestData['mark']
        );
        return $this->reportService->registerAssessmentReport($requestDto);
    }

    /**
     * Создания массива формата Json
     *
     * @param ResultRegisteringAssessmentReportDto $responseDto
     * @return void
     */
    private function buildJsonData(ResultRegisteringAssessmentReportDto $responseDto):array
    {
        return [
            'id'=>$responseDto->getId()
        ];
    }

    /**
     * Валидирует входные данные
     *
     * @param $requestData
     * @return array
     */
    private function validateData($requestData):array
    {
        $err = [];
        if(false === is_array($requestData)){
            $err[] = 'Данные о новой оценке не являються массивом';
        }else{
            if (false === array_key_exists('lesson_id', $requestData)) {
                $err[] = 'Отсутсвует информация о занятии';
            } elseif (false === is_int($requestData['lesson_id'])) {
                $err[] = 'Id занятия должно быть целым числом';
            }

            if (false === array_key_exists('student_id', $requestData)) {
                $err[] = 'Отсутсвует информация о студенте';
            } elseif (false === is_int($requestData['student_id'])) {
                $err[] = 'Id студента должно быть целым числом';
            }

            if (false === array_key_exists('mark', $requestData)) {
                $err[] = 'Отсутсвует информация о оценке';
            } elseif (false === is_int($requestData['mark'])) {
                $err[] = 'Значение оценки должно быть целым числом';
            }elseif (0 >= $requestData['mark'] || $requestData['mark'] > 5){
                $err[] = 'Значение оценки не должно быть меньше 0, быть равным 0 или быть больше 5';
            }
        }
        return $err;
    }

}