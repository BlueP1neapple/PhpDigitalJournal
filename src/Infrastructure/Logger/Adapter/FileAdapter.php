<?php

namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\Adapter;

use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\AdapterInterface;

class FileAdapter implements AdapterInterface
{

    /**
     * Путь до файла с логгами
     * @var string
     */
    private string $pathToLogFile;

    /**
     * @param string $pathToFile
     */
    public function __construct(string $pathToLogFile)
    {
        $this->pathToLogFile = $pathToLogFile;
    }
    /**
     * @inheritDoc
     */
    public function write(string $logLevel, string $msg): void
    {
        file_put_contents($this->pathToLogFile, "{$msg}\n", FILE_APPEND);
    }

}