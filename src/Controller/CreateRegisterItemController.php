<?php

namespace JoJoBizzareCoders\DigitalJournal\Controller;

use Doctrine\ORM\EntityManagerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Controller\ControllerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerResponseFactory;
use JoJoBizzareCoders\DigitalJournal\Service\NewItemService;
use JoJoBizzareCoders\DigitalJournal\Service\NewItemService\NewItemDto;
use JoJoBizzareCoders\DigitalJournal\Service\NewItemService\ResultRegisteringItemDto;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class CreateRegisterItemController implements ControllerInterface
{
    /**
     * Менеджер сущностей
     *
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;
    /**
     * Сервис создания Предметов
     *
     * @var NewItemService
     */
    private NewItemService $newItemService;

    /**
     * Фабрика для создания http ответов
     *
     * @var ServerResponseFactory
     */
    private ServerResponseFactory $serverResponseFactory;


    /**
     * @param NewItemService $newItemService
     * @param ServerResponseFactory $serverResponseFactory
     * @param EntityManagerInterface $em
     */
    public function __construct(
        NewItemService $newItemService,
        ServerResponseFactory $serverResponseFactory,
        EntityManagerInterface $em
    ) {
        $this->newItemService = $newItemService;
        $this->serverResponseFactory = $serverResponseFactory;
        $this->em = $em;
    }

    public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
    {
        try {
            $this->em->beginTransaction();
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
            $this->em->commit();
            $this->em->flush();
        } catch (Throwable $e) {
            $this->em->rollBack();
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
