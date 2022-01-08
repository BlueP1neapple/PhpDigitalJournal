<?php

namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Console;

interface CommandInterface
{
    /**
     * Короткие опции команды
     * @return string
     */
    public static function getShortOption():string;

    /**
     * Длинныые опции команды
     * @return array
     */
    public static function getLongOption():array;

    /**
     * Запуск консольной команды
     * @param array $prams
     */
    public function __invoke(array $prams):void;

}