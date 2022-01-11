<?php

namespace JoJoBizzareCoders\DigitalJournal\Service\ReportAssessmentService;



/**
 * Dto объект с информацией о Студенты
 */
final class StudentDto
{
    /**
     * id студента
     *
     * @var int
     */
    private int $id;

    /**
     * ФИО студента
     *
     * @var string
     */
    private string $fio;

    /**
     * Дата рождения студента
     *
     * @var string
     */
    private string $dateOfBirth;

    /**
     * Номер телефона студента
     *
     * @var string
     */
    private string $phone;

    /**
     * Адресс студента
     *
     * @var string
     */
    private string $address;

    /**
     * Dto объект с информацией о классах
     *
     * @var ClassDto
     */
    private ClassDto $class;

    /**
     * Родители студента
     *
     * @var ParentDto
     */
    private ParentDto $parent;

    //Методы

    /**
     * Конструктор Dto объект с информацией о Студенты
     *
     * @param int $id - id студента
     * @param string $fio - ФИО студента
     * @param string $dateOfBirth - дата рождения студента
     * @param string $phone - номер телефона студента
     * @param string $address - адресс продивания студента
     * @param ClassDto $class - класс студента
     * @param ParentDto $parent - родители студента
     */
    public function __construct(
        int $id,
        string $fio,
        string $dateOfBirth,
        string $phone,
        string $address,
        ClassDto $class,
        ParentDto $parent
    ) {
        $this->id = $id;
        $this->fio = $fio;
        $this->dateOfBirth = $dateOfBirth;
        $this->phone = $phone;
        $this->address = $address;
        $this->class = $class;
        $this->parent = $parent;
    }

    /**
     * Возвращает id студента
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Возвращает Фио студента
     *
     * @return string
     */
    public function getFio(): string
    {
        return $this->fio;
    }

    /**
     * Возвращает дату рождения студента
     *
     * @return string
     */
    public function getDateOfBirth(): string
    {
        return $this->dateOfBirth;
    }

    /**
     * Возвращает номер телефона
     *
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * Возвращает аддресс проживания студента
     *
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * Возвращает класс студента
     *
     * @return ClassDto
     */
    public function getClass(): ClassDto
    {
        return $this->class;
    }

    /**
     * Возвращает dto с родителями
     *
     * @return ParentDto
     */
    public function getParent(): ParentDto
    {
        return $this->parent;
    }
}
