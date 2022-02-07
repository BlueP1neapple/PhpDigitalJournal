<?php

namespace JoJoBizzareCoders\DigitalJournal\Repository;

use JoJoBizzareCoders\DigitalJournal\Entity\ItemClass;
use JoJoBizzareCoders\DigitalJournal\Entity\ItemRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\TeacherUserClass;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DataLoader\DataLoaderInterface;
use JsonException;

class ItemJsonFileRepository implements ItemRepositoryInterface
{

    /**
     * Путь до файла с предметами
     *
     * @var string
     */
    private string $pathToItems;

    /**
     * Даталоадер
     *
     * @var DataLoaderInterface
     */
    private DataLoaderInterface $dataLoader;

    /**
     * @var array|null
     */
    private ?array $data = null;

    private ?array $itemsIdToIndex = null;

    /**
     * Текущий id пользователя
     *
     * @var
     */
    private int $currentId;

    /**
     * @param string $pathToItems
     * @param DataLoaderInterface $dataLoader
     */
    public function __construct(
        string $pathToItems,
        DataLoaderInterface $dataLoader)
    {
        $this->pathToItems = $pathToItems;
        $this->dataLoader = $dataLoader;
    }

    private function loadData(): array
    {
        if (null === $this->data) {
            $this->data = $this->dataLoader->LoadDate($this->pathToItems);
            $this->itemsIdToIndex = array_combine(
                array_map(
                    static function (array $v) {
                        return $v['id'];
                    },
                    $this->data
                ),
                array_keys($this->data)
            );

            $this->currentId = max(
                array_map(
                    static function(array $v){ return $v['id'];},
                    $this->data
                )
            );


        }
        return $this->data;
    }


    /**
     * @inheritDoc
     */
    public function findBy(array $criteria): array
    {
        $items = $this->loadData();
        $foundItem = [];
        foreach ($items as $item) {
            if (array_key_exists('name', $criteria)) {
                $authorMeetSearchCriteria = $criteria['name'] === $item['name'];
            } else {
                $authorMeetSearchCriteria = true;
            }

            if ($authorMeetSearchCriteria && array_key_exists('id', $criteria)) {
                $authorMeetSearchCriteria = $criteria['id'] === $item['id'];
            }
            if ($authorMeetSearchCriteria) {
                $foundItem[] = ItemClass::createFromArray($item);
            }
        }

        return $foundItem;

    }


    /**
     * @inheritDoc
     * @return int
     * @throws JsonException
     */
    public function nexId():int
    {
        $this->loadData();
        ++$this->currentId;
        return $this->currentId;
    }

    /**
     * @throws JsonException
     */
    public function add(ItemClass $entity): ItemClass
    {
        $object = $this->buildJsonData($entity);
        $this->data[] = $object;
        $data = $this->data;
        $this->itemsIdToIndex[$entity->getId()] = array_key_last($this->data);
        $file = $this->pathToItems;
        $jsonStr = json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($file, $jsonStr);
        return $entity;
    }

    /**
     * Создание json 'а
     *
     * @param ItemClass $entity
     * @return array
     */
    private function buildJsonData(ItemClass $entity):array
    {
        return [
            'id' => $entity->getId(),
            'name' => $entity->getName(),
            'description' => $entity->getDescription()
        ];
    }

}