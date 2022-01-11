<?php

namespace JoJoBizzareCoders\DigitalJournal\Service\ReportAssessmentService;

use JoJoBizzareCoders\DigitalJournal\ValueObject\AdditionalInfo;

/**
 * Dto объект с оценками
 */
final class AssessmentReportDto
{
    /**
     * id оценка
     *
     * @var int
     */
    private int $id;

    /**
     * Dto объект c занятиями
     *
     * @var LessonDto
     */
    private LessonDto $lesson;

    /**
     * Dto объект с студентами
     *
     * @var StudentDto
     */
    private StudentDto $student;

    /**
     * Оценка
     *
     * @var int
     */
    private int $mark;

    /**
     *  Дополнительная информация по уроку
     *
     * @var AdditionalInfo
     */
    private AdditionalInfo $additional_info;


    /**
     * Конструктор dto объекта с информацией о оценках
     *
     * @param int $id - id оценки
     * @param LessonDto $lesson - занятие на которой была поставленна оценка
     * @param StudentDto $student - студент которому была поставлена оценка
     * @param int $mark - оценка
     */
    public function __construct(int $id, LessonDto $lesson, StudentDto $student, int $mark, AdditionalInfo $additional_info)
    {
        $this->id = $id;
        $this->lesson = $lesson;
        $this->student = $student;
        $this->mark = $mark;
        $this->additional_info;
    }

    /**
     * Возвращает id оценки
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Возвращает занятие на которой была поставленна оценка
     *
     * @return LessonDto
     */
    public function getLesson(): LessonDto
    {
        return $this->lesson;
    }

    /**
     * Возвращает студент которому была поставлена оценка
     *
     * @return StudentDto
     */
    public function getStudent(): StudentDto
    {
        return $this->student;
    }

    /**
     * Возвращает оценка
     *
     * @return int
     */
    public function getMark(): int
    {
        return $this->mark;
    }

    /**
     * Возвращает доп инфу об уроке
     *
     * @return AdditionalInfo
     */
    public function getAdditionalInfo(): AdditionalInfo
    {
        return $this->additional_info;
    }

}
