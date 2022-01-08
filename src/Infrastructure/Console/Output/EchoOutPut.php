<?php

namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Console\Output;

/**
 * Вывод в консоль с помощью эхо
 */
final class EchoOutPut implements OutputInterface
{

    /**
     * @inheritDoc
     */
    public function print(string $text): void
    {
        echo $text;
    }
}
