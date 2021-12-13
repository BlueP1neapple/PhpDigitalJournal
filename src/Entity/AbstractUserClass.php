<?php
namespace JoJoBizzareCoders\DigitalJournal\Entity;
    use JsonSerializable;

    /**
     * Класс пользователя
     */
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
         * Конструктор Пользователя
         * @param int $id - Id пользователя
         * @param string $fio - ФИО пользователя
         * @param string $dateOfBirth - Дата рождения Пользователя
         * @param string $phone - Номер телефона Пользователя
         * @param string $address - Домашний адресс пользователя
         */
        public function __construct(int $id, string $fio, string $dateOfBirth, string $phone, string $address)
        {
            $this->id = $id;
            $this->fio = $fio;
            $this->dateOfBirth = $dateOfBirth;
            $this->phone = $phone;
            $this->address = $address;
        }


        /**
         * @return int Получение id
         */
        final public function getId(): int
        {
            return $this->id;
        }


        /**
         * @return string Получение ФИО
         */
        final public function getFio(): string
        {
            return $this->fio;
        }

        /**
         * @return array - Массив для кодирования в json
         */
        public function jsonSerialize(): array
        {
            return [
                'id' => $this->id,
                'fio' => $this->fio,
                'phone' => $this->phone,
                'dateOfBirth' => $this->dateOfBirth,
                'address' => $this->address
            ];
        }

        /**
         * Создание объекта класса пользователя из массива данных об Пользователе
         * @param array $data - массив данных об Пользователе
         * @return AbstractUserClass - объект класса пользователь
         */
        abstract public static function createFromArray(array $data): AbstractUserClass;
    }