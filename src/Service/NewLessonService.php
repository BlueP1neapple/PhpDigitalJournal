<?php

namespace JoJoBizzareCoders\DigitalJournal\Service;

use JoJoBizzareCoders\DigitalJournal\Entity\LessonClass;
use JoJoBizzareCoders\DigitalJournal\Entity\LessonRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Exception\RuntimeException;
use JoJoBizzareCoders\DigitalJournal\Repository\AssessmentReportJsonRepository;
use JoJoBizzareCoders\DigitalJournal\Repository\ClassJsonFileRepository;
use JoJoBizzareCoders\DigitalJournal\Repository\ItemJsonFileRepository;
use JoJoBizzareCoders\DigitalJournal\Repository\LessonJsonRepository;
use JoJoBizzareCoders\DigitalJournal\Repository\TeacherJsonFileRepository;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\NewLessonDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\ResultRegistrationLessonDto;

/**
 * Сервис регистрации нового урока
 */
class NewLessonService
{
    /**
     * Репозиторий уроков
     *
     * @var LessonJsonRepository
     */
    private LessonJsonRepository $lessonRepository;

    /**
     * Репозиторий учетелей
     *
     * @var TeacherJsonFileRepository
     */
    private TeacherJsonFileRepository $teacherRepository;

    /**
     * Репозиторий предметов
     *
     * @var ItemJsonFileRepository
     */
    private ItemJsonFileRepository $itemRepository;

    /**
     * Репозиторий классов
     *
     * @var ClassJsonFileRepository
     */
    private ClassJsonFileRepository $classRepository;

    /**
     * @param LessonRepositoryInterface $lessonRepository
     * @param TeacherJsonFileRepository $teacherRepository
     * @param ItemJsonFileRepository $itemRepository
     * @param ClassJsonFileRepository $classRepository
     */
    public function __construct(
        LessonRepositoryInterface $lessonRepository,
        TeacherJsonFileRepository $teacherRepository,
        ItemJsonFileRepository $itemRepository,
        ClassJsonFileRepository $classRepository
    )
    {
        $this->lessonRepository = $lessonRepository;
        $this->teacherRepository = $teacherRepository;
        $this->itemRepository = $itemRepository;
        $this->classRepository = $classRepository;
    }

    public function registerLesson(NewLessonDto $lessonDto):ResultRegistrationLessonDto
    {
        $teacherId = $lessonDto->getTeacherId();
        $itemId = $lessonDto->getItemId();
        $classId = $lessonDto->getClassId();

        $teacherCollection = $this->teacherRepository->findBy(['id' => $teacherId]);
        $itemCollection = $this->itemRepository->findBy(['id' => $itemId]);
        $classCollection = $this->classRepository->findBy(['id' => $classId]);

        if(1 !== count($teacherCollection)){
            throw new RuntimeException("Нельзя зарегестрировать урок с преподом = '$teacherId'");
        }
        $teacher = current($teacherCollection);

        if(1 !== count($itemCollection)){
            throw new RuntimeException("Нельзя зарегестрировать предмет = '$itemId'");
        }
        $item = current($itemCollection);

        if(1 !== count($classCollection)){
            throw new RuntimeException("Нельзя зарегестрировать класс = '$classId'");
        }
        $class = current($classCollection);


        $entity = new LessonClass(
            $this->lessonRepository->nextId(),
            $item,
            $lessonDto->getDate(),
            $lessonDto->getLessonDuration(),
            $teacher,
            $class
        );
        $this->lessonRepository->add($entity);

        return new ResultRegistrationLessonDto(
            $entity->getId()
        );
    }

}