<?php
namespace JoJoBizzareCoders\DigitalJournal\Service;


use JoJoBizzareCoders\DigitalJournal\Entity\TeacherRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\TeacherUserClass;
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
     * @var TeacherRepositoryInterface
     */
    private TeacherRepositoryInterface $teacherRepository;

    /**
     * @param LoggerInterface $logger
     * @param TeacherRepositoryInterface $teacherRepository
     */
    public function __construct(
        LoggerInterface $logger,
        TeacherRepositoryInterface $teacherRepository)
    {
        $this->logger = $logger;
        $this->teacherRepository = $teacherRepository;
    }

    public function search():array
    {
        $entitiesCollection = $this->teacherRepository->findBy([]);
        $dtoCollection = [];
        foreach ($entitiesCollection as $entity){
            $dtoCollection[] = $this->createDto($entity);
        }
        $this->logger->info('found item: ' . count($entitiesCollection));
        return $dtoCollection;
    }

    private function createDto(TeacherUserClass $entity): TeacherDto
    {
        return new TeacherDto(
            $entity->getId(),
            $entity->getFio(),
            $entity->getDateOfBirth(),
            $entity->getPhone(),
            $entity->getAddress(),
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