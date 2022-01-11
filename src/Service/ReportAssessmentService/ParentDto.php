<?php

namespace JoJoBizzareCoders\DigitalJournal\Service\ReportAssessmentService;


/**
 * Dto объект с родителями
 */
final class ParentDto
{

    /**
     * id Родителя
     *
     * @var int
     */
    private int $id;

    /**
     * Фио родителя
     *
     * @var string
     */
    private string $fio;

    /**
     * Дата рождения родителя
     *
     * @var string
     */
    private string $dateOfBirth;

    /**
     * Номер телефона родителя
     *
     * @var string
     */
    private string $phone;

    /**
     * Адресс проживания родителя
     *
     * @var string
     */
    private string $address;

    /**
     * Место работы родителя
     *
     * @var string
     */
    private string $placeOfWork;

    /**
     * email Родителя
     *
     * @var string
     */
    private string $email;

    //Методы

    /**
     * Конструктор Dto объект с информацией об родителях
     *
     * @param int $id - id родителя
     * @param string $fio - Фио - родителя
     * @param string $dateOfBirth - Дата рождения родителя
     * @param string $phone - Номер телефона родителя
     * @param string $address - Аддресс проживания родителя
     * @param string $placeOfWork - Место работы родителя
     * @param string $email - email родителя
     */
    public function __construct(
        int $id,
        string $fio,
        string $dateOfBirth,
        string $phone,
        string $address,
        string $placeOfWork,
        string $email
    ) {
        $this->id = $id;
        $this->fio = $fio;
        $this->dateOfBirth = $dateOfBirth;
        $this->phone = $phone;
        $this->address = $address;
        $this->placeOfWork = $placeOfWork;
        $this->email = $email;
    }

    /**
     * Возвращает id родителя
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Возвращает Фио - родителя
     *
     * @return string
     */
    public function getFio(): string
    {
        return $this->fio;
    }

    /**
     * Возвращает Дата рождения родителя
     *
     * @return string
     */
    public function getDateOfBirth(): string
    {
        return $this->dateOfBirth;
    }

    /**
     * Возвращает Номер телефона родителя
     *
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * Возвращает аддресс проживания родителя
     *
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * Возвращает Место работы родителя
     *
     * @return string
     */
    public function getPlaceOfWork(): string
    {
        return $this->placeOfWork;
    }

    /**
     * Возвращает email родителя
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
