<?php

namespace JoJoBizzareCoders\DigitalJournal\Service;

use JoJoBizzareCoders\DigitalJournal\Entity\StudentRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\StudentUserClass;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\ClassDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\ParentDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\StudentDto;

class SearchStudentService
{

    /**
     * Репозиторий студентов
     *
     * @var StudentRepositoryInterface
     */
    private StudentRepositoryInterface $studentRepository;

    /**
     * @param StudentRepositoryInterface $studentRepository
     */
    public function __construct(StudentRepositoryInterface $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    public function search():array
    {
        $entitiesCollection = $this->studentRepository->findBy([]);
        $dtoCollection = [];
        foreach ($entitiesCollection as $entity){
            $dtoCollection[] = $this->createDto($entity);
        }
        return $dtoCollection;
    }

    private function createDto(StudentUserClass $entity):StudentDto
    {
        return new StudentDto(
            $entity->getId(),
            $entity->getFio(),
            $entity->getDateOfBirth(),
            $entity->getPhone(),
            $entity->getAddress(),
            new ClassDto(
                $entity->getClass()->getId(),
                $entity->getClass()->getNumber(),
                $entity->getClass()->getLetter()
            ),
            new ParentDto(
                $entity->getParent()->getId(),
                $entity->getParent()->getFio(),
                $entity->getParent()->getDateOfBirth(),
                $entity->getParent()->getPhone(),
                $entity->getParent()->getAddress(),
                $entity->getParent()->getPlaceOfWork(),
                $entity->getParent()->getEmail()
            )
        );
    }
}