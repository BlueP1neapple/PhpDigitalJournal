<?php

namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\HttpApplication;

/**
 * Конфиг движка сайта
 */
interface AppConfigInterface
{
    /**
     * Возвращает флаг указывающий, что нужно скрыватиь сообщения по ошибкам
     *
     * @return bool
     */
    public function isHideErrorMessage(): bool;
}
