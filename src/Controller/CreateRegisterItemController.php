<?php

namespace JoJoBizzareCoders\DigitalJournal\Controller;

use JoJoBizzareCoders\DigitalJournal\Infrastructure\Controller\ControllerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\HttpResponse;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerRequest;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerResponseFactory;
use JoJoBizzareCoders\DigitalJournal\Service\NewItemService;
use JoJoBizzareCoders\DigitalJournal\Service\NewItemService\NewItemDto;
use JoJoBizzareCoders\DigitalJournal\Service\NewItemService\ResultRegisteringItemDto;
use Throwable;

class CreateRegisterItemController implements ControllerInterface
{
    /**
     * Сервис создания Предметов
     *
     * @var NewItemService
     */
    private NewItemService $newItemService;

    /**
     * @param NewItemService $newItemService
     */
    public function __construct(NewItemService $newItemService)
    {
        $this->newItemService = $newItemService;
    }

    public function __invoke(ServerRequest $serverRequest): HttpResponse
    {
        try {
            $requestData = json_decode($serverRequest->getBody(), true, 10, JSON_THROW_ON_ERROR);
            $validationResult = $this->validateData($requestData);
            if (0 === count($validationResult)) {
                $responseDto = $this->runService($requestData);
                $httpCode = 201;
                $jsonData = $this->buildJsonData($responseDto);
            } else {
                $httpCode = 400;
                $jsonData = [
                    'status' => 'fail',
                    'message' => implode('. ', $validationResult)
                ];
            }
        } catch (Throwable $e) {
            $httpCode = 500;
            $jsonData = [
                'status' => 'fail',
                'message' => $e->getMessage()
            ];
        }
        return ServerResponseFactory::createJsonResponse($httpCode, $jsonData);
    }

    /**
     * Запуск сервиса
     *
     * @param array $data
     * @return ResultRegisteringItemDto
     */
    private function runService(array $data): ResultRegisteringItemDto
    {
        $requestDto = new NewItemDto(
            $data['name'],
            $data['description']
        );
        return $this->newItemService->registerItem($requestDto);
    }

    /**
     * Создания массива формата Json
     *
     * @param ResultRegisteringItemDto $responseDto
     * @return void
     */
    private function buildJsonData(ResultRegisteringItemDto $responseDto): array
    {
        return [
            'id' => $responseDto->getId()
        ];
    }

    /**
     * Валидирует входные данные
     *
     * @param $requestData
     * @return array
     */
    private function validateData($requestData): array
    {
        $err = [];
        if (false === is_array($requestData)) {
            $err[] = 'Данные о новой предмете не массив';
        } else {
            if (false === array_key_exists('name', $requestData)) {
                $err[] = 'Отсутсвует название предмета';
            } elseif (false === is_string($requestData['name'])) {
                $err[] = 'Название предмета должено быть строкой';
            } elseif ('' === trim($requestData['name'])) {
                $err[] = 'Название предмета не должено быть пустым ';
            }

            if (false === array_key_exists('description', $requestData)) {
                $err[] = 'Отсутсвует description предмета';
            } elseif (false === is_string($requestData['description'])) {
                $err[] = 'description предмета должен быть строкой';
            } elseif ('' === trim($requestData['description'])) {
                $err[] = 'description предмета не должен быть пустой';
            }
        }
        return $err;
    }

}