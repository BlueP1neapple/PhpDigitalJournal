<?php
namespace JoJoBizzareCoders\DigitalJournal\Entity;

use JoJoBizzareCoders\DigitalJournal\ValueObject\Address;
use JoJoBizzareCoders\DigitalJournal\ValueObject\Fio;


/**
 * Класс пользователя
 */
abstract class AbstractUserClass
{
    /**
     * Id пользователя
     *
     * @var int
     */
    private int $id;

    /**
     * ФИО пользователя
     *
     * @var Fio[]
     */
    private array $fio;

    /**
     * День Рождения пользователя
     *
     * @var string
     */
    private string $dateOfBirth;

    /**
     * Номер телефона пользователя
     *
     * @var string
     */
    private string $phone;

    /**
     * Адресс пользователя
     *
     * @var Address[]
     */
    private array $address;


    /**
     * Конструктор Пользователя
     *
     * @param int $id - Id пользователя
     * @param array $fio - ФИО пользователя
     * @param string $dateOfBirth - Дата рождения Пользователя
     * @param string $phone - Номер телефона Пользователя
     * @param array $address - Домашний адресс пользователя
     */
    public function __construct(int $id, array $fio, string $dateOfBirth, string $phone, array $address)
    {
        $this->id = $id;
        $this->fio = $fio;
        $this->dateOfBirth = $dateOfBirth;
        $this->phone = $phone;
        $this->address = $address;
    }


    /**
     * Получение id
     *
     * @return int
     */
    final public function getId(): int
    {
        return $this->id;
    }


    /**
     * Получение ФИО
     *
     * @return Fio[]
     */
    final public function getFio(): array
    {
        return $this->fio;
    }

    /**
     * Получение даты рождения
     *
     * @return string
     */
    public function getDateOfBirth(): string
    {
        return $this->dateOfBirth;
    }

    /**
     * Получение номера телефона
     *
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * Получение домашнего адресса
     *
     * @return Address[]
     */
    public function getAddress(): array
    {
        return $this->address;
    }

    /**
     * Создание объекта класса пользователя из массива данных об Пользователе
     *
     * @param array $data - массив данных об Пользователе
     * @return AbstractUserClass - объект класса пользователь
     */
    abstract public static function createFromArray(array $data): AbstractUserClass;
}