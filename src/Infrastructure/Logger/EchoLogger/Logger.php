<?php
namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\EchoLogger;
    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\LoggerInterface;


    /**
     * Логирует в консоль с помошью эхо
     */
    class Logger implements LoggerInterface
    {

        /**
         * @inheritDoc
         */
        public function log(string $msg): void
        {
            echo "$msg\n";
        }
    }