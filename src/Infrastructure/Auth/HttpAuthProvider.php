<?php

namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Auth;

class HttpAuthProvider
{
    /**
     * Репозитарий юзеров
     *
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Проводит аутентификацию
     *
     * @param string $login
     * @param string $password
     * @return bool
     */
    public function auth(string $login, string $password):bool
    {
        $isAuth = false;
        $user = $this->userRepository->findUserByLogin($login);

        if(null !== $user && $password === $user->getPassword()){
            $isAuth = true;
        }

        return $isAuth;
    }

}