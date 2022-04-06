<?php

namespace JoJoBizzareCoders\DigitalJournal\Repository;

use JoJoBizzareCoders\DigitalJournal\Entity\UserRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Exception\RuntimeException;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Auth\UserDataProviderInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Auth\UserDataStorageInterface;
use JoJoBizzareCoders\DigitalJournal\Repository\UserDataProviderRepository\UserDataProvider;

class UserDataProviderRepository implements
    UserDataStorageInterface
{

    private UserRepositoryInterface $userRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @inheritDoc
     */
    public function findUserByLogin(string $login): ?UserDataProviderInterface
    {
        $entities = $this->userRepository->findBy(['login' => $login]);
        $countEntities = count($entities);

        if ($countEntities > 1) {
            throw new RuntimeException('Найдены пользователи с дублирующимися логинами');
        }

        return 0 === $countEntities ? null : new UserDataProvider(current($entities));
    }
}