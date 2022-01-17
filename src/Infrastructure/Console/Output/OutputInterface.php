<?php

    namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Console\Output;

    /**
     * Интерфейс отвечающий за вывод данных в консоль
     */
    interface OutputInterface
    {
        /**
         * Выводит информацию в консоль
         *
         * @param string $text - сообщение с информацией выводимое в консоль
         * @return void
         */
        public function print(string $text):void;
    }