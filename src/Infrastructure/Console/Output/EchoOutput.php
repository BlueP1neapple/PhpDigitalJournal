<?php

    namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Console\Output;

    /**
     * еализация вывода информации в консоль посредством использования эзо
     */
    class EchoOutput implements OutputInterface
    {

        /**
         * @inheritDoc
         */
        public function print(string $text): void
        {
            echo $text . "\n";
        }
    }