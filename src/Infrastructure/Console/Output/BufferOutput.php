<?php

    namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Console\Output;

    /**
     * Реализация вывод данных в буфер, предгазначен для тестирования консольных приложения
     */
    class BufferOutput implements OutputInterface
    {

        //Свойство
        /**
         * Буффер для хранения результатов выводимы на консоль
         *
         * @var array
         */
        private array $buffer;

        //Методы

        /**
         * @inheritDoc
         */
        public function print(string $text): void
        {
            $this->buffer[] = $text;
        }

        /**
         * Возвращает буффер для хранения результатов выводимы на консоль
         *
         * @return array
         */
        public function getBuffer(): array
        {
            return $this->buffer;
        }
    }