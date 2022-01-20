<?php

namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\Adapter;

use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\AdapterInterface;

class NullAdapter implements AdapterInterface
{

    public function write(string $logLevel, string $msg): void
    {

    }
}