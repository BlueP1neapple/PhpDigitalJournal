<?php

namespace JoJoBizzareCoders\DigitalJournal\Service;

use JoJoBizzareCoders\DigitalJournal\Entity\StudentUserClass;
use JoJoBizzareCoders\DigitalJournal\Entity\UserRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\ClassDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\ParentDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\StudentDto;

class SearchStudentService
{
    /**
     * Репозиторий студентов
     *
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $studentRepository;

    /**
     * @param UserRepositoryInterface $studentRepository
     */
    public function __construct(UserRepositoryInterface $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    public function search(): array
    {
        $entitiesCollection = $this->studentRepository->findBy([]);
        $dtoCollection = [];
        foreach ($entitiesCollection as $entity) {
            $dtoCollection[] = $this->createDto($entity);
        }
        return $dtoCollection;
    }

    private function createDto(StudentUserClass $entity): StudentDto
    {
        return new StudentDto(
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
            new ClassDto(
                $entity->getClass()->getId(),
                $entity->getClass()->getNumber(),
                $entity->getClass()->getLetter()
            ),
            $this->createArrayParentDto($entity)
        );
    }

    private function createArrayParentDto(StudentUserClass $entity): array
    {
        $parents = [];
        foreach ($entity->getParents() as $parent) {
            $parents[] =
                new ParentDto(
                    $parent->getId(),
                    [
                        $parent->getFio()->getName(),
                        $parent->getFio()->getSurname(),
                        $parent->getFio()->getPatronymic(),
                    ],
                    $parent->getDateOfBirth(),
                    $parent->getPhone(),
                    [
                        $parent->getAddress()->getHome(),
                        $parent->getAddress()->getStreet(),
                        $parent->getAddress()->getApartment()
                    ],
                    $parent->getPlaceOfWork(),
                    $parent->getEmail(),
                );
        }
        return $parents;
    }
}
