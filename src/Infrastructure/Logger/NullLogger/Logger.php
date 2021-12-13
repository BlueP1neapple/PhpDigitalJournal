<?php
namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\NullLogger;
    require_once __DIR__.'/../LoggerInterface.php';

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