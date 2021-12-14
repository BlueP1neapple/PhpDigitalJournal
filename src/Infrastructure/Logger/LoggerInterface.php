<?php
namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger;
    /**
     *Интерфэйс логера
     */
    interface LoggerInterface
    {
        /**
         * Запись в логи сообщение
         *
         * @param string $msg - логируемое сообщение
         *
         * @return void
         */
        public function Log(string $msg): void;
    }