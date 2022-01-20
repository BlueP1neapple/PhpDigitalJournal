<?php
namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger;
    /**
     *Интерфэйс логера
     */
    interface LoggerInterface
    {
        /**
         * Система полностью не работает
         *
         * @param string $massage сообщение
         * @param array $context дополнительный контекст
         */
        public function emergency(string $massage, array $context = []):void;
        /**
         * Действие требует безотлагательного вмешательства
         *
         * @param string $massage сообщение
         * @param array $context дополнительный контекст
         */
        public function alert(string $massage, array $context = []):void;
        /**
         * Критические состояния
         *
         * @param string $massage сообщение
         * @param array $context дополнительный контекст
         */
        public function critical(string $massage, array $context = []):void;
        /**
         * Это уже у нас ошибка, не требующие вмешательства
         *
         * @param string $massage сообщение
         * @param array $context дополнительный контекст
         */
        public function error(string $massage, array $context = []):void;
        /**
         * Исключительные случаи, но не ошибки
         *
         * @param string $massage сообщение
         * @param array $context дополнительный контекст
         */
        public function warning(string $massage, array $context = []):void;
        /**
         * Существенные события
         *
         * @param string $massage сообщение
         * @param array $context дополнительный контекст
         */
        public function notice(string $massage, array $context = []):void;
        /**
         * Интересные события
         *
         * @param string $massage сообщение
         * @param array $context дополнительный контекст
         */
        public function info(string $massage, array $context = []):void;
        /**
         * Подробная информация о отладке
         *
         * @param string $massage сообщение
         * @param array $context дополнительный контекст
         */
        public function debug(string $massage, array $context = []):void;

        /**
         * Запись
         *
         * @param string $level - Уровень логирования
         * @param string $message - сообщение
         * @param array $context - дополнительный контекст
         */
        public function log(string $level, string $message, array $context = []): void;

    }