<?php
namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\NullLogger;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\LoggerInterface;


    /**
     * Логирует в Null
     */
    class Logger implements LoggerInterface
    {

        /**
         * @inheritDoc
         */
        public function Log(string $msg): void {}
    }