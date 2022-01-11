<?php

namespace JoJoBizzareCoders\DigitalJournal\Service\ReportAssessmentService;

/**
 * DTO объект с входными данными
 * Критерии поиска оценок
 */
final class FullReportDto
{

    /**
     * Название предмета
     *
     * @var string|null
     */
    private ?string $itemName;

    /**
     * id оценки
     *
     * @var string|null
     */
    private ?string $id;

    /**
     * Расшифровка название предмета
     *
     * @var string|null
     */
    private ?string $itemDescription;

    /**
     * Дата проведения занятия
     *
     * @var string|null
     */
    private ?string $lessonDate;

    /**
     * Фио студента
     *
     * @var string|null
     */
    private ?string $studentFio;


    /**
     * @return string|null
     */
    public function getItemName(): ?string
    {
        return $this->itemName;
    }

    /**
     * @param string|null $itemName
     */
    public function setItemName(?string $itemName): void
    {
        $this->itemName = $itemName;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getItemDescription(): ?string
    {
        return $this->itemDescription;
    }

    /**
     * @param string|null $itemDescription
     */
    public function setItemDescription(?string $itemDescription): void
    {
        $this->itemDescription = $itemDescription;
    }

    /**
     * @return string|null
     */
    public function getLessonDate(): ?string
    {
        return $this->lessonDate;
    }

    /**
     * @param string|null $lessonDate
     */
    public function setLessonDate(?string $lessonDate): void
    {
        $this->lessonDate = $lessonDate;
    }

    /**
     * @return string|null
     */
    public function getStudentFio(): ?string
    {
        return $this->studentFio;
    }

    /**
     * @param string|null $studentFio
     */
    public function setStudentFio(?string $studentFio): void
    {
        $this->studentFio = $studentFio;
    }
}
