<?php

abstract class AbstractUserClass implements JsonSerializable
{
    /**
     * Id пользователя
     */
    private int $id;

    /**
     * ФИО пользователя
     */
    private string $fio;

    /**
     * День Рождения пользователя
     */
    private string $dateOfBirth;

    /**
     * @var string Номер телефона пользователя
     */
    private string $phone;

    /**
     * @var string Адресс пользователя
     */
    private string $address;





    /**
     * @return int Получение id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id Установить id
     */
    public function setId(int $id): AbstractUserClass
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string Получение ФИО
     */
    public function getFio(): string
    {
        return $this->fio;
    }

    /**
     * @param string $fio Установить ФИО
     */
    public function setFio(string $fio): AbstractUserClass
    {
        $this->fio = $fio;
        return $this;
    }

    /**
     * @return string Получение  Даты Дня рождения
     */
    public function getDateOfBirth(): string
    {
        return $this->dateOfBirth;
    }

    /**
     * @param string $dateOfBirth Установить Дату рождения
     */
    public function setDateOfBirth(string $dateOfBirth): AbstractUserClass
    {
        $this->dateOfBirth = $dateOfBirth;
        return $this;
    }

    /**
     * @return string Получение Номера телефона
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone Установить Номер телефона
     */
    public function setPhone(string $phone): AbstractUserClass
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string Получение Адресса
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address Установить Адресс
     */
    public function setAddress(string $address): AbstractUserClass
    {
        $this->address = $address;
        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'fio' => $this->fio,
            'phone' => $this->phone,
            'dateOfBirth' =>$this->dateOfBirth,
            'address' =>$this->address
        ];
    }

}