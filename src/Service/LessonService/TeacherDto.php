<?php

namespace JoJoBizzareCoders\DigitalJournal\Service\LessonService;


/**
 * DTO объект с Учителями
 */
final class TeacherDto
{
    /**
     * id преподователя
     *
     * @var int
     */
    private int $id;

    /**
     * Фио - преподавателя
     *
     * @var string
     */
    private string $fio;

    /**
     * Дата рождения преподавателя
     *
     * @var string
     */
    private string $dateOfBirth;

    /**
     * Номер телефона преподавателя
     *
     * @var string
     */
    private string $phone;

    /**
     * Адресс проживания преподователя
     *
     * @var string
     */
    private string $address;

    /**
     * id предмета Преподавателя
     *
     * @var ItemDto
     */
    private ItemDto $item;

    /**
     * Кабинет преподавателя
     *
     * @var int
     */
    private int $cabinet;

    /**
     * email Преподавателя
     *
     * @var string
     */
    private string $email;

    //Методы

    /**
     * Конструктор DTO объект специфицирующий результаты работы сервиса Учителей
     *
     * @param int $id - ид преподователя
     * @param string $fio - Фио - преподавателя
     * @param string $dateOfBirth - Дата рождения преподавателя
     * @param string $phone - Номер телефона преподавателя
     * @param string $address - Адресс проживания преподователя
     * @param ItemDto $item - id предмета Преподавателя
     * @param int $cabinet - Кабинет преподавателя
     * @param string $email - email Преподавателя
     */
    public function __construct(
        int $id,
        string $fio,
        string $dateOfBirth,
        string $phone,
        string $address,
        ItemDto $item,
        int $cabinet,
        string $email
    ) {
        $this->id = $id;
        $this->fio = $fio;
        $this->dateOfBirth = $dateOfBirth;
        $this->phone = $phone;
        $this->address = $address;
        $this->item = $item;
        $this->cabinet = $cabinet;
        $this->email = $email;
    }

    /**
     * Возвращает id преподователя
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Возвращает Фио - преподавателя
     *
     * @return string
     */
    public function getFio(): string
    {
        return $this->fio;
    }

    /**
     * Возвращает Дату рождения преподавателя
     *
     * @return string
     */
    public function getDateOfBirth(): string
    {
        return $this->dateOfBirth;
    }

    /**
     * Возвращает Номер телефона преподавателя
     *
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * Возвращает Адресс проживания преподователя
     *
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * Возвращает id предмета Преподавателя
     *
     * @return ItemDto
     */
    public function getItem(): ItemDto
    {
        return $this->item;
    }

    /**
     * Возвращает Кабинет преподавателя
     *
     * @return int
     */
    public function getCabinet(): int
    {
        return $this->cabinet;
    }

    /**
     * Возвращает email Преподавателя
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
