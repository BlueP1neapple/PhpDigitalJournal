<?php

namespace JoJoBizzareCoders\DigitalJournal\Service;

use JoJoBizzareCoders\DigitalJournal\Entity\TeacherRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\TeacherUserClass;
use JoJoBizzareCoders\DigitalJournal\Entity\UserRepositoryInterface;
use Psr\Log\LoggerInterface;
use JoJoBizzareCoders\DigitalJournal\Service\SearchItemService\ItemDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchTeacherService\TeacherDto;

class SearchTeacherService
{
    /**
     * Используемый логгер
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Репозиторий учетелй
     *
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $teacherRepository;

    /**
     * @param LoggerInterface $logger
     * @param UserRepositoryInterface $teacherRepository
     */
    public function __construct(
        LoggerInterface $logger,
        UserRepositoryInterface $teacherRepository
    ) {
        $this->logger = $logger;
        $this->teacherRepository = $teacherRepository;
    }

    public function search(): array
    {
        $entitiesCollection = $this->teacherRepository->findBy([]);
        $dtoCollection = [];
        foreach ($entitiesCollection as $entity) {
            $dtoCollection[] = $this->createDto($entity);
        }
        $this->logger->info('found item: ' . count($entitiesCollection));
        return $dtoCollection;
    }

    private function createDto(TeacherUserClass $entity): TeacherDto
    {
        return new TeacherDto(
            $entity->getId(),
            [
                $entity->getFio()->getName(),
            $entity->getFio()->getSurname(),
            $entity->getFio()->getPatronymic()
            ],
            $entity->getDateOfBirth(),
            $entity->getPhone(),
            [
                $entity->getAddress()->getStreet(),
                $entity->getAddress()->getHome(),
                $entity->getAddress()->getApartment()
            ],
            new ItemDto(
                $entity->getItem()->getId(),
                $entity->getItem()->getName(),
                $entity->getItem()->getDescription()
            ),
            $entity->getCabinet(),
            $entity->getEmail()
        );
    }
}
