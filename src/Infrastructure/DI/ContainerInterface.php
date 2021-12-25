<?php

    namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\DI;


    /**
     * Интерфейс контейнера зависимостей
     */
    interface ContainerInterface
    {
        /**
         * Возвращает сервис по заданному имени
         *
         * @param string $serviceName - имя искомого сервиса
         * @return mixed
         */
        public function get(string $serviceName);
    }