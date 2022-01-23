<?php

namespace JoJoBizzareCoders\DigitalJournal\AppService\UserDataStorage;

use JoJoBizzareCoders\DigitalJournal\Entity\AbstractUserClass;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Auth\UserDataProviderInterface;

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


    /**
     * @inheritDoc
     */
    public function getLogin(): string
    {
        return $this->user->getLogin();
    }

    /**
     * @inheritDoc
     */
    public function getPassword(): string
    {
        return $this->user->getPassword();
    }
}
