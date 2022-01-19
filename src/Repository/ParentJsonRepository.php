<?php

namespace JoJoBizzareCoders\DigitalJournal\Repository;

use JoJoBizzareCoders\DigitalJournal\Entity\AbstractUserClass;
use JoJoBizzareCoders\DigitalJournal\Entity\ParentRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\ParentUserClass;
use JoJoBizzareCoders\DigitalJournal\Exception\RuntimeException;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DataLoader\DataLoaderInterface;

class ParentJsonRepository implements ParentRepositoryInterface
{

    /**
     * Путь до родителей
     *
     * @var string
     */
    private string $pathToParents;

    /**
     * Массив с данными
     *
     * @var array|null
     */
    private ?array $data = null;

    /**
     * Даталоадер
     *
     * @var DataLoaderInterface
     */
    private DataLoaderInterface $dataLoader;

    /**
     * @param string $pathToParents
     * @param DataLoaderInterface $dataLoader
     */
    public function __construct(string $pathToParents, DataLoaderInterface $dataLoader)
    {
        $this->pathToParents = $pathToParents;
        $this->dataLoader = $dataLoader;
    }


    /**
     * @inheritDoc
     */
    public function findBy(array $criteria): array
    {
        $dataItems = $this->loadData();
        $foundEntities = [];

        foreach ($dataItems as $user){
            if(false === is_array($user)){
                throw new RuntimeException('данные о пользоватале должны быть массивим');
            }

            if (array_key_exists('login', $criteria)) {
                $userMeetSearchCriteria = $criteria['login'] === $user['login'];
            }
            if (array_key_exists('surname', $criteria)) {
                $userMeetSearchCriteria = $criteria['surname'] === $user['surname'];
            }
            if ($userMeetSearchCriteria && array_key_exists('id', $criteria)) {
                $userMeetSearchCriteria = $criteria['id'] === $user['id'];
            }
            if($userMeetSearchCriteria){
                $entity = $this->createParent($user);
                $foundEntities[] = $entity;
            }


        }


        return $foundEntities;

    }

    /**
     * @inheritDoc
     */
    public function findUserByLogin(string $login): ?AbstractUserClass
    {
        $entities = $this->findBy(['login' => $login]);
        $countEntities = count($entities);

        if($countEntities > 1){
            throw new RuntimeException('Найдены пользователи с дублирующимися логинами');
        }

        return 0 === $countEntities ? null : current($entities);

    }

    private function loadData()
    {
        if (null === $this->data) {
            $this->data = $this->dataLoader->LoadDate($this->pathToParents);
            if(false === is_array($this->data)){
                throw new RuntimeException('Данные о пользователе должны быть массивом');
            }
        }
        return $this->data;

    }

    private function createParent(array $user): ParentUserClass
    {
        $this->validateParent($user);

        return new ParentUserClass(
            $user['id'],
            $user['fio'],
            $user['dateOfBirth'],
            $user['phone'],
            $user['address'],
            $user['placeOfWork'],
            $user['email'],
            $user['login'],
            $user['password']
        );

    }

    private function validateParent(array $user):void
    {
        if (false === array_key_exists('id', $user)) {
            throw new RuntimeException('нет поля id');
        }
        if (false === array_key_exists('login', $user)) {
            throw new RuntimeException('нет поля login');
        }
        if (false === array_key_exists('password', $user)) {
            throw new RuntimeException('нет поля password');
        }
        if (false === array_key_exists('fio', $user)) {
            throw new RuntimeException('нет поля fio');
        }
        if (false === array_key_exists('dateOfBirth', $user)) {
            throw new RuntimeException('нет поля dateOfBirth');
        }
        if (false === array_key_exists('phone', $user)) {
            throw new RuntimeException('нет поля phone');
        }
        if (false === array_key_exists('address', $user)) {
            throw new RuntimeException('нет поля address');
        }
        if (false === array_key_exists('placeOfWork', $user)) {
            throw new RuntimeException('нет поля placeOfWork');
        }
        if (false === array_key_exists('email', $user)) {
            throw new RuntimeException('нет поля email');
        }




    }

}