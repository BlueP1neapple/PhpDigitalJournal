<?php


namespace JoJoBizzareCoders\DigitalJournal\Repository;

use JoJoBizzareCoders\DigitalJournal\Entity\ItemClass;
use JoJoBizzareCoders\DigitalJournal\Entity\ItemRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Exception\RuntimeException;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Db\ConnectionInterface;

/**
 * Репозиторий предметов для работы через бд
 */
final class ItemDbRepository implements ItemRepositoryInterface
{
    /**
     * Критерии поиска
     */
    private const ALLOWED_CRITERIA = [
        'id'
    ];

    /**
     * Подключение к бд
     *
     * @var ConnectionInterface
     */
    private ConnectionInterface $connection;

    /**
     * @param ConnectionInterface $connection - Подключение к бд
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
        $itemData = $this->loadItemData($criteria);
        return $this->buildItemEntities($itemData);
    }

    /**
     * @inheritDoc
     */
    public function nexId(): int
    {
        $sql = <<<EOF
SELECT
MAX(id) AS max_id
FROM item
EOF;
        $maxId = current($this->connection->query($sql)->fetchAll());
        $maxId = $maxId ?? 0;
        return ((int)$maxId) + 1;
    }

    /**
     * Добавление ентити в бд
     *
     * @param ItemClass $entity - Энтити для добавления в бд
     * @return ItemClass - добавленная сущность
     */
    public function add(ItemClass $entity): ItemClass
    {
        $sql = <<<EOF
INSERT INTO item (id, name, description)
VALUES (
        :id, :name, :description
)
EOF;
        $values = [
            'id' => $entity->getId(),
            'name' => $entity->getName(),
            'description' => $entity->getDescription(),
        ];
        $this->connection->prepare($sql)->execute($values);
        return $entity;
    }

    /**
     * Валидация критериев
     *
     * @param array $criteria - массив поиска
     * @return void
     */
    private function validate(array $criteria): void
    {
        $invalidCriteria = array_diff(array_keys($criteria), self::ALLOWED_CRITERIA);
        if (count($invalidCriteria) > 0) {
            $errMsg = 'Неподдерживаемые критерии поиска предметов: ' . implode(',', $invalidCriteria);
            throw new RuntimeException($errMsg);
        }
    }

    /**
     * Загрузка данных по заданным критериям
     *
     * @param array $criteria - массив критериев
     * @return array - массив найденных данных о предметах
     */
    private function loadItemData(array $criteria): array
    {
        $whereParts = [];
        $whereParams = [];
        $sql = <<<EOF
SELECT
       id,
       name,
       description
FROM item
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
     * Формирование массива сущьностей ItemClass по критериям поиска
     *
     * @param array $itemData - массив данных о предметах найденных по критериям
     * @return ItemClass[] - массив сущностей предметов найденных по критериям
     */
    private function buildItemEntities(array $itemData): array
    {
        $itemEntities = [];
        foreach ($itemData as $itemItem) {
            $itemEntities[] = ItemClass::createFromArray($itemItem);
        }
        return $itemEntities;
    }
}
