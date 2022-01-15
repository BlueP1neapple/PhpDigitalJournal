<?php

namespace JoJoBizzareCoders\DigitalJournal\Controller;

use JoJoBizzareCoders\DigitalJournal\Infrastructure\Controller\ControllerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\HttpResponse;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerRequest;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerResponseFactory;
use JoJoBizzareCoders\DigitalJournal\Service\NewLessonService;
use JoJoBizzareCoders\DigitalJournal\Service\NewLessonService\NewLessonDto;
use JoJoBizzareCoders\DigitalJournal\Service\NewLessonService\ResultRegistrationLessonDto;
use Throwable;

class CreateRegisterLessonController implements ControllerInterface
{

    /**
     * Сервис для создания урока
     *
     * @var NewLessonService
     */
    private NewLessonService $newLessonService;

    /**
     * @param NewLessonService $newLessonService
     */
    public function __construct(NewLessonService $newLessonService)
    {
        $this->newLessonService = $newLessonService;
    }


    /**
     * @inheritDoc
     */
    public function __invoke(ServerRequest $serverRequest): HttpResponse
    {
        try{
            $requestData = json_decode($serverRequest->getBody(), true,20, JSON_THROW_ON_ERROR);
            $validationResult = $this->validationData($requestData);

            if(0 === count($validationResult)) {
                $responseDto = $this->runService($requestData);
                $httpCode = 201;
                $jsonData = $this->buildJsondata($responseDto);
            }else{
                $httpCode = 400;
                $jsonData = ['status' => 'fail', 'massage' => implode('. ', $validationResult)];
            }

        }catch (Throwable $e){
            $httpCode = 500;
            $jsonData = ['status' => 'fail', 'massage' => $e->getMessage()];

        }

        return ServerResponseFactory::createJsonResponse($httpCode, $jsonData);
    }

    private function validationData($requestData)
    {
        $err = [];

        if(false === is_array($requestData)){
            $err[] = 'Данные о новом уроке не являются массивом';
        }else{
            if(false === array_key_exists('item_id', $requestData)){
                $err[] = 'отсутствует айди предмета';
            } elseif (false === is_int($requestData['item_id'])){
                $err[] = 'айди предмета должен быть числом';
            }
        }
        if(false === array_key_exists('date', $requestData)){
            $err[] = 'отсутствует дата урока';
        } elseif (false === is_string($requestData['date'])){
            $err[] = 'дата урока должна быть строкой';
        }

        if(false === array_key_exists('teacher_id', $requestData)){
            $err[] = 'отсутствует преподаватель';
        } elseif (false === is_int($requestData['teacher_id'])){
            $err[] = 'id преподавателя должно быть предстваленна целым числом';
        }

        if(false === array_key_exists('class_id', $requestData)){
            $err[] = 'отсутствует класс';
        } elseif (false === is_int($requestData['class_id'])){
            $err[] = 'id класса должно быть предстваленна целым числом';
        }

        return $err;
    }

    /**
     *
     * @param array $requestData
     * @return ResultRegistrationLessonDto
     */
    private function runService(array $requestData): ResultRegistrationLessonDto
    {
        $requestDto = new NewLessonDto(
            $requestData['item_id'],
            $requestData['date'],
            $requestData['lessonDuration'],
            $requestData['teacher_id'],
            $requestData['class_id'],
        );
        return $this->newLessonService->registerLesson($requestDto);
    }

    private function buildJsondata(ResultRegistrationLessonDto $responseDto)
    {
        return[
            'id' => $responseDto->getId()

        ];
    }
}