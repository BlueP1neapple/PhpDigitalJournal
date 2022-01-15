<?php

namespace JoJoBizzareCoders\DigitalJournal\Repository;

use JoJoBizzareCoders\DigitalJournal\Entity\ItemClass;
use JoJoBizzareCoders\DigitalJournal\Entity\ItemRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\TeacherUserClass;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DataLoader\DataLoaderInterface;

class ItemJsonFileRepository implements ItemRepositoryInterface
{

    /**
     * Путь до файла с предметами
     *
     * @var string
     */
    private string $patchToItems;

    /**
     * Даталоадер
     *
     * @var DataLoaderInterface
     */
    private DataLoaderInterface $dataLoader;

    private ?array $data = null;

    /**
     * @param string $patchToItem
     * @param DataLoaderInterface $dataLoader
     */
    public function __construct(string $patchToItem, DataLoaderInterface $dataLoader)
    {
        $this->patchToItems = $patchToItem;
        $this->dataLoader = $dataLoader;
    }

    private function loadData(): array
    {
        if (null === $this->data) {
            $this->data = $this->dataLoader->LoadDate($this->patchToItems);
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
}