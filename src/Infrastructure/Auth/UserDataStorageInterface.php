<?php

namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Auth;

/**
 * интерфейс хранилища данных о пользователях
 */
interface UserDataStorageInterface
{
    /**
     * Поиск пользователя по логину
     *
     * @param string $login
     * @return UserDataProviderInterface|null
     */
    public function findUserByLogin(string $login):?UserDataProviderInterface;
}
