<?php
namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\FileLogger;
    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\LoggerInterface;


    /**
     * Логирование в файл
     */
    class Logger implements LoggerInterface
    {
        /**
         * Путь до файла в который пишуться логи
         *
         * @var string
         */
        private string $pathToFile;

        /**
         * @param string $pathToFile
         */
        public function __construct(string $pathToFile)
        {
            $this->pathToFile = $pathToFile;
        }

        /**
         * @inheritDoc
         */
        public function log(string $msg): void
        {
            file_put_contents($this->pathToFile,"$msg\n",FILE_APPEND);
        }

    }