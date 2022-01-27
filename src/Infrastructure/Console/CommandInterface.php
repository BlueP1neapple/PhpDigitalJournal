<?php

    namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Console;

    /**
     * Консольная команда
     */
    interface CommandInterface
    {
        /**
         * Возвращает конфиг описывающий короткие опции команды
         *
         * @return string
         */
        public static function getShortOptions():string;

        /**
         * Возвращает конфиг описывающий длинные опции команды
         *
         * @return array
         */
        public static function getLongOptions():array;

        /**
         * Запуск консольной команды
         *
         * @param array $params - параметры консольной команды
         * @return void
         */
        public function __invoke(array $params):void;
    }