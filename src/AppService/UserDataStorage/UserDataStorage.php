<?php

namespace JoJoBizzareCoders\DigitalJournal\AppService\UserDataStorage;

use JoJoBizzareCoders\DigitalJournal\Entity\AbstractUserRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Exception\UnexpectedValueException;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Auth\UserDataProviderInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Auth\UserDataStorageInterface;

class UserDataStorage implements UserDataStorageInterface
{
    /**
     * @var AbstractUserRepositoryInterface[]
     */
    private array $repositories;


    /**
     * @param AbstractUserRepositoryInterface ...$userRepository
     */
    public function __construct(AbstractUserRepositoryInterface ...$userRepository)
    {
        $this->repositories = $userRepository;
    }


    /**
     * @inheritDoc
     */
    public function findUserByLogin(string $login): ?UserDataProviderInterface
    {
        $resultOfRepository = [[]];
        foreach ($this->repositories as $repository){
            $resultOfRepository[] = $repository->findBy(['login' => $login]);
        }
        $users = array_merge(...$resultOfRepository);
        if(count($users)>1){
            throw new UnexpectedValueException('');
        }
        return 0 === count($users)? null : new UserDataProvider(current($users));
    }
}
