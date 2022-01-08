<?php

namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Console\Output;
/**
 * Интерфейс - данные в консоль
 */
interface OutputInterface
{
    /**
     * Выводит информацию в консоль
     * @param string $text
     */
    public function print(string $text):void;
}
