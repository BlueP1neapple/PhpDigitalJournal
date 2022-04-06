<?php

namespace JoJoBizzareCoders\DigitalJournal\Service;

use JoJoBizzareCoders\DigitalJournal\Entity\AssessmentReportRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\ReportClass;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\ClassDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\ItemDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\LessonDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\TeacherDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\AssessmentReportDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\ParentDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\SearchReportAssessmentCriteria;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\StudentDto;
use Psr\Log\LoggerInterface;

/**
 * Сервис поиска оценок
 */
class SearchAssessmentReportService
{
    /**
     * Использвуемый логгер
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Репризиторий для работы с оценками
     *
     * @var AssessmentReportRepositoryInterface
     */
    private AssessmentReportRepositoryInterface $assessmentReportRepository;


    /**
     * Конструктор Сервиса поиска оценок
     *
     * @param LoggerInterface $logger - Использвуемый логгер
     * @param AssessmentReportRepositoryInterface $assessmentReportRepository
     */
    public function __construct(
        LoggerInterface $logger,
        AssessmentReportRepositoryInterface $assessmentReportRepository
    ) {
        $this->logger = $logger;
        $this->assessmentReportRepository = $assessmentReportRepository;
    }

    /**
     * Метод поиска оценки по критериям
     *
     * @param SearchReportAssessmentCriteria $searchCriteria - критерии посика оценки
     * @return array
     */
    public function search(SearchReportAssessmentCriteria $searchCriteria): array
    {
        $criteria = $this->searchCriteriaForArray($searchCriteria);
        if (array_key_exists('id', $criteria)) {
            $criteria['report_id'] = $criteria['id'];
            unset($criteria['id']);
        }
        $entitiesCollection = $this->assessmentReportRepository->findBy($criteria);
        $dtoCollection = [];
        foreach ($entitiesCollection as $entity) {
            $dtoCollection[] = $this->createDto($entity);
        }
        $this->logger->info('found Report: ' . count($entitiesCollection));
        return $dtoCollection;
    }

    /**
     * Создание dto объекта с информацией о оценках
     *
     * @param ReportClass $report - информация о оценке
     * @return AssessmentReportDto
     */
    private function createDto(ReportClass $report): AssessmentReportDto
    {
        $lesson = $report->getLesson();
        $item = $lesson->getItem();
        $itemDto = new ItemDto(
            $item->getId(),
            $item->getName(),
            $item->getDescription()
        );
        $teacher = $lesson->getTeacher();
        $teacherDto = new TeacherDto(
            $teacher->getId(),
            [
                $teacher->getFio()->getSurname(),
                $teacher->getFio()->getName(),
                $teacher->getFio()->getPatronymic()
            ],
            $teacher->getDateOfBirth(),
            $teacher->getPhone(),
            [
                $teacher->getAddress()->getStreet(),
                $teacher->getAddress()->getHome(),
                $teacher->getAddress()->getApartment()
            ],
            $itemDto,
            $teacher->getCabinet(),
            $teacher->getEmail()
        );
        $classForLesson = $lesson->getClass();
        $classForLessonDto = new ClassDto(
            $classForLesson->getId(),
            $classForLesson->getNumber(),
            $classForLesson->getLetter()
        );
        $lessonDto = new LessonDto(
            $lesson->getId(),
            $itemDto,
            $lesson->getDate(),
            $lesson->getLessonDuration(),
            $teacherDto,
            $classForLessonDto
        );
        $student = $report->getStudent();
        $parents = $student->getParents();
        foreach ($parents as $parent) {
            $parentDto[] = new ParentDto(
                $parent->getId(),
                [
                    $parent->getFio()->getSurname(),
                    $parent->getFio()->getName(),
                    $parent->getFio()->getPatronymic()
                ],
                $parent->getDateOfBirth(),
                $parent->getPhone(),
                [
                    $parent->getAddress()->getStreet(),
                    $parent->getAddress()->getHome(),
                    $parent->getAddress()->getApartment()
                ],
                $parent->getPlaceOfWork(),
                $parent->getEmail()
            );
        }

        $classForStudent = $student->getClass();
        $classForStudentDto = new ClassDto(
            $classForStudent->getId(),
            $classForStudent->getNumber(),
            $classForStudent->getLetter()
        );
        $studentDto = new StudentDto(
            $student->getId(),
            [
                $student->getFio()->getSurname(),
                $student->getFio()->getName(),
                $student->getFio()->getPatronymic()
            ],
            $student->getDateOfBirth(),
            $student->getPhone(),
            [
                $student->getAddress()->getStreet(),
                $student->getAddress()->getHome(),
                $student->getAddress()->getApartment()
            ],
            $classForStudentDto,
            $parentDto
        );
        return new AssessmentReportDto(
            $report->getId(),
            $lessonDto,
            $studentDto,
            $report->getMark()
        );
    }

    /**
     * преобразует критерии поиска в массив
     *
     * @param SearchReportAssessmentCriteria $searchCriteria - - критерии поиска
     * @return array
     */
    private function searchCriteriaForArray(SearchReportAssessmentCriteria $searchCriteria): array
    {
        $criteriaForRepository = [
            'item_name' => $searchCriteria->getItemName(),
            'item_description' => $searchCriteria->getItemDescription(),
            'lesson_date' => $searchCriteria->getLessonDate(),
            'student_fio_surname' => $searchCriteria->getStudentSurname(),
            'student_fio_name' => $searchCriteria->getStudentName(),
            'student_fio_patronymic' => $searchCriteria->getStudentPatronymic(),
            'id' => $searchCriteria->getId()
        ];
        return array_filter($criteriaForRepository, static function ($v): bool {
            return null !== $v;
        });
    }
}
