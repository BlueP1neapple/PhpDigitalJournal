<?php

namespace JoJoBizzareCoders\DigitalJournal\Repository;

use JoJoBizzareCoders\DigitalJournal\Entity\ClassClass;
use JoJoBizzareCoders\DigitalJournal\Entity\ClassRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\TeacherUserClass;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DataLoader\DataLoaderInterface;
use JsonException;

class ClassJsonFileRepository implements ClassRepositoryInterface
{

    /**
     * Путь до файла с классами
     *
     * @var string
     */
    private string $pathToClasses;

    /**
     * Dataloader
     *
     * @var DataLoaderInterface
     */
    private DataLoaderInterface $dataLoader;

    /**
     * Данные
     *
     * @var array|null
     */
    private ?array $data = null;

    /**
     * @param string $pathToClasses
     * @param DataLoaderInterface $dataLoader
     */
    public function __construct(
        string $pathToClasses,
        DataLoaderInterface $dataLoader)
    {
        $this->pathToClasses = $pathToClasses;
        $this->dataLoader = $dataLoader;
    }


    /**
     * Метод реализующий загрузку данных
     *
     * @return array
     * @throws JsonException
     */
    private function loadData(): array
    {
        if (null === $this->data) {
            $this->data = $this->dataLoader->LoadDate($this->pathToClasses);
        }
        return $this->data;
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function findBy(array $criteria): array
    {
        $classes = $this->loadData();
        $foundClass = [];
        foreach ($classes as $class) {
            if (array_key_exists('number', $criteria)) {
                $authorMeetSearchCriteria = $criteria['number'] === $class['number'];
            } else {
                $authorMeetSearchCriteria = true;
            }

            if ($authorMeetSearchCriteria && array_key_exists('id', $criteria)) {
                $authorMeetSearchCriteria = $criteria['id'] === $class['id'];
            }
            if ($authorMeetSearchCriteria) {
                $foundClass[] = ClassClass::createFromArray($class);
            }
        }

        return $foundClass;
    }

}