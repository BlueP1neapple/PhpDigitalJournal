<?php
namespace JoJoBizzareCoders\DigitalJournal\Service;

use JoJoBizzareCoders\DigitalJournal\Entity\ItemClass;
use JoJoBizzareCoders\DigitalJournal\Entity\ItemRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\TeacherUserClass;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\LoggerInterface;
use JoJoBizzareCoders\DigitalJournal\Service\SearchItemService\ItemDto;

class SearchItemService
{
    /**
     * Используемый логгер
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Репозиторий предметов
     *
     * @var ItemRepositoryInterface
     */
    private ItemRepositoryInterface $itemRepository;

    /**
     * @param LoggerInterface $logger
     * @param ItemRepositoryInterface $itemRepository
     */
    public function __construct(
        LoggerInterface $logger,
        ItemRepositoryInterface $itemRepository)
    {
        $this->logger = $logger;
        $this->itemRepository = $itemRepository;
    }

    public function search():array
    {
        $entitiesCollection = $this->itemRepository->findBy([]);
        $dtoCollection = [];
        foreach ($entitiesCollection as $entity){
            $dtoCollection[] = $this->createDto($entity);
        }
        $this->logger->info('found item: ' . count($entitiesCollection));
        return $dtoCollection;
    }

    private function createDto(ItemClass $entity): ItemDto
    {
        return new ItemDto(
            $entity->getId(),
            $entity->getName(),
            $entity->getDescription()
        );
    }
}