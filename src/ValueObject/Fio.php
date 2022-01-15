<?php

    namespace JoJoBizzareCoders\DigitalJournal\ValueObject;

    use JoJoBizzareCoders\DigitalJournal\Exception\DomainException;

    /**
     * Фио пользователя
     */
    final class Fio
    {
        //Свойства
        /**
         * Фамилия пользователя
         *
         * @var string
         */
        private string $surname;

        /**
         * Имя пользователя
         *
         * @var string
         */
        private string $name;

        /**
         * Отчество пользователя
         *
         * @var string
         */
        private string $patronymic;

        //Методы

        /**
         * Конструктор фио пользователя
         *
         * @param string $surname - фамилия пользователя
         * @param string $name - имя пользователя
         * @param string $patronymic - отчество пользователя
         */
        public function __construct(string $surname, string $name, string $patronymic)
        {
            $this->surname = $surname;
            $this->name = $name;
            $this->patronymic = $patronymic;
        }


        /**
         * возвращает отчество пользователя
         *
         * @return string
         */
        public function getPatronymic(): string
        {
            return $this->patronymic;
        }

        /**
         * возвращает фамилию пользователя
         *
         * @return string
         */
        public function getSurname(): string
        {
            return $this->surname;
        }

        /**
         * возвращает имя пользователя
         *
         * @return string
         */
        public function getName(): string
        {
            return $this->name;
        }
    }