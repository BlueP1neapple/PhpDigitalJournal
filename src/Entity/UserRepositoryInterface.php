<?php

namespace JoJoBizzareCoders\DigitalJournal\Entity;


interface UserRepositoryInterface
{
    /**
     * Поиск сущностей по критериям
     *
     * @param array $criteria
     * @return AbstractUserClass[]
     */
    public function findBy(array $criteria): array;

    /**
     * Поиск пользователя по логину
     *
     *
     * @param string $login
     * @return AbstractUserClass|null
     */
    public function findUserByLogin(string $login): ?AbstractUserClass;
}
