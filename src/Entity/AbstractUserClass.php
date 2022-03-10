<?php
namespace JoJoBizzareCoders\DigitalJournal\Entity;

use DateTimeImmutable;
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
    private DateTimeImmutable $dateOfBirth;

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
     * Логин пользователя
     *
     * @var string
     */
    private string $login;

    /**
     * Пароль пользователя
     *
     * @var string
     */
    private string $password;

    /**
     * Конструктор Пользователя
     *
     * @param int $id - Id пользователя
     * @param array $fio - ФИО пользователя
     * @param DateTimeImmutable $dateOfBirth - Дата рождения Пользователя
     * @param string $phone - Номер телефона Пользователя
     * @param array $address - Домашний адресс пользователя
     * @param string $login
     * @param string $password
     */
    public function __construct(int $id, array $fio, DateTimeImmutable $dateOfBirth, string $phone, array $address,string $login, string $password)
    {
        $this->id = $id;
        $this->fio = $fio;
        $this->dateOfBirth = $dateOfBirth;
        $this->phone = $phone;
        $this->address = $address;
        $this->login = $login;
        $this->password = $password;
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
     * @return DateTimeImmutable
     */
    public function getDateOfBirth(): DateTimeImmutable
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
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }


    /**
     * Создание объекта класса пользователя из массива данных об Пользователе
     *
     * @param array $data - массив данных об Пользователе
     * @return AbstractUserClass - объект класса пользователь
     */
    abstract public static function createFromArray(array $data): AbstractUserClass;
}