<?php

    namespace JoJoBizzareCoders\DigitalJournal\ValueObject;

    /**
     * Адресс пользователя
     */
    final class Address
    {
        //Свойства
        /**
         * Улица пользователя
         *
         * @var string
         */
        private string $street;

        /**
         * Номер дома пользователя
         *
         * @var string
         */
        private string $home;

        /**
         * Номер квартиры пользователя
         *
         * @var string
         */
        private string $apartment;

        //Метод

        /**
         * Конструктор адресса пользователя
         *
         * @param string $street - улица пользователя
         * @param string $home - номер дома пользователя
         * @param string $apartment - номер квартиры пользователя
         */
        public function __construct(string $street, string $home, string $apartment)
        {
            $this->street = $street;
            $this->home = $home;
            $this->apartment = $apartment;
        }

        /**
         * возвращает улицу пользователя
         *
         * @return string
         */
        public function getStreet(): string
        {
            return $this->street;
        }

        /**
         * возвращает номер дома пользователя
         *
         * @return string
         */
        public function getHome(): string
        {
            return $this->home;
        }

        /**
         * возвращает номер квартиры пользователя
         *
         * @return string
         */
        public function getApartment(): string
        {
            return $this->apartment;
        }
    }