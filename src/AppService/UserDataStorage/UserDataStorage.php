<?php

namespace JoJoBizzareCoders\DigitalJournal\AppService\UserDataStorage;

use JoJoBizzareCoders\DigitalJournal\Entity\AbstractUserRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Exception\UnexpectedValueException;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Auth\UserDataProviderInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Auth\UserDataStorageInterface;

class UserDataStorage implements UserDataStorageInterface
{
    /**
     * @var AbstractUserRepositoryInterface
     */
    private AbstractUserRepositoryInterface $repository;


    /**
     * @param AbstractUserRepositoryInterface $userRepository
     */
    public function __construct(AbstractUserRepositoryInterface $userRepository)
    {
        $this->repository = $userRepository;
    }


    /**
     * @inheritDoc
     */
    public function findUserByLogin(string $login): ?UserDataProviderInterface
    {

        $users = $this->repository->findBy(['login' => $login]);

        if (count($users) > 1) {
            throw new UnexpectedValueException('Найдено больше 1 пользователя с логином');
        }
        return 0 === count($users) ? null : new UserDataProvider(current($users));
    }
}
