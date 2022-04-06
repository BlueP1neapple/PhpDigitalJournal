<?php

namespace JoJoBizzareCoders\DigitalJournal\Repository\UserDataProviderRepository;

use JoJoBizzareCoders\DigitalJournal\Entity\AbstractUserClass;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Auth\UserDataProviderInterface;

/**
 * Поставщик данных
 *
 *
 */
class UserDataProvider implements UserDataProviderInterface
{
    private AbstractUserClass $user;

    /**
     * @param AbstractUserClass $user
     */
    public function __construct(AbstractUserClass $user)
    {
        $this->user = $user;
    }

    public function getLogin(): string
    {
        return $this->user->getLogin();
    }

    public function getPassword(): string
    {
        return $this->getPassword();
    }
}
