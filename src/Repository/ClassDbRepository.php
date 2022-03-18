<?php

namespace JoJoBizzareCoders\DigitalJournal\Repository;

use JoJoBizzareCoders\DigitalJournal\Entity\ClassClass;
use JoJoBizzareCoders\DigitalJournal\Entity\ClassRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Db\ConnectionInterface;
use JoJoBizzareCoders\DigitalJournal\Exception\RuntimeException;

class ClassDbRepository implements ClassRepositoryInterface
{

    /**
     * Коллекция разрешённых критериев поиска
     */
    private const ALLOWED_CRITERIA = [
        'id'
    ];

    /**
     * Компонент отвечающий за соединение с базой данных
     *
     * @var ConnectionInterface
     */
    private ConnectionInterface $connection;

    /**
     * @param ConnectionInterface $connection - Компонент отвечающий за соединение с базой данных
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @inheritDoc
     */
    public function findBy(array $criteria): array
    {
        $this->validate($criteria);
        $classData = $this->loadData($criteria);
        return $this->buildClassEntities($classData);
    }

    /**
     * Реализация логики загрузки данных о найденных по критериям поиска классах из базы данных
     *
     * @param array $criteria - коллекция критериев поиска
     * @return array - коллекция данных о найденных по критериям классах
     */
    private function loadData(array $criteria): array
    {
        $whereParts = [];
        $whereParams = [];
        $sql = <<<EOF
SELECT
       id,
       number,
       letter
FROM class
EOF;

        foreach ($criteria as $criteriaName => $criteriaValue) {
            $whereParts[] = "$criteriaName = :$criteriaName";
            $whereParams[$criteriaName] = $criteriaValue;
        }
        if (count($whereParts) > 0) {
            $sql .= ' WHERE ' . implode(' AND ', $whereParts);
        }
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($whereParams);
        return $stmt->fetchAll();
    }

    /**
     * Реализация логики валидации критериев поиска
     *
     * @param array $criteria - коллекция критериев поиска
     * @return void
     */
    private function validate(array $criteria): void
    {
        $invalidCriteria = array_diff(array_keys($criteria), self::ALLOWED_CRITERIA);
        if (count($invalidCriteria) > 0) {
            $errMsg = 'Неподдерживаемые критерии поиска классов: ' . implode(',', $invalidCriteria);
            throw new RuntimeException($errMsg);
        }
    }

    /**
     * Реализация логики формирования коллекции сущностей найденных классов
     *
     * @param array $classData - коллекция данных о найденных классах
     * @return ClassClass[] - коллекция сущностей найденных классов
     */
    private function buildClassEntities(array $classData): array
    {
        $classEntities = [];
        foreach ($classData as $classItem) {
            $classEntities[] = ClassClass::createFromArray($classItem);
        }
        return $classEntities;
    }


}