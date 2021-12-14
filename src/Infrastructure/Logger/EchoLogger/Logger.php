<?php
namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\EchoLogger;
    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\LoggerInterface;

    require_once __DIR__.'/../LoggerInterface.php';

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