<?php

namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger;

    /**
     * Адаптер для записи логов
     */
interface AdapterInterface
{
    /**
     * Записать в лог
     *
     * @param string $logLevel - уровень логируемого собщения
     * @param string $msg - сообщение для записи
     */
    public function write(string $logLevel,string $msg):void;
}

