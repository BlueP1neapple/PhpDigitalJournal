<?php

namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\Adapter;

use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\AdapterInterface;

/**
 * Логгирует в эхо
 */
class EchoAdapter implements AdapterInterface
{

    /**
     * @inheritDoc
     */
    public function write(string $logLevel, string $msg): void
    {
        echo "$msg\n";
    }
}