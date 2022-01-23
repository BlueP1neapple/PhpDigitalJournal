<?php

namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Auth;

/**
 * интерфейс провайдера (поставщика) данных о пользователе
 */
interface UserDataProviderInterface
{
    /**
     * Возвращает логин
     *
     * @return string
     */
    public function getLogin():string;

    /**
     * Возвращает пароль
     *
     * @return string
     */
    public function getPassword():string;
}
