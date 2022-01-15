<?php

namespace JoJoBizzareCoders\DigitalJournal\Repository;

use JoJoBizzareCoders\DigitalJournal\Entity\TeacherRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\TeacherUserClass;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DataLoader\DataLoaderInterface;
use JsonException;

class TeacherJsonFileRepository implements TeacherRepositoryInterface
{

    /**
     * Путь до учетелей
     *
     * @var string
     */
    private string $pathToTeachers;

    /**
     * Даталоадер
     *
     * @var DataLoaderInterface
     */
    private DataLoaderInterface $dataLoader;

    /**
     * Данные о учетелях
     *
     * @var array|null
     */
    private ?array $data = null;

    /**
     * @param string $pathToTeacher
     * @param DataLoaderInterface $dataLoader
     */
    public function __construct(string $pathToTeacher, DataLoaderInterface $dataLoader)
    {
        $this->pathToTeachers = $pathToTeacher;
        $this->dataLoader = $dataLoader;
    }

    /**
     * Метод реализующий загрузку данных о авторах
     *
     * @return array
     * @throws JsonException
     */
    private function loadData(): array
    {
        if (null === $this->data) {
            $this->data = $this->dataLoader->LoadDate($this->pathToTeachers);
        }
        return $this->data;
    }


    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function findBy(array $criteria): array
    {
        $teachers = $this->loadData();
        $foundTeacher = [];
        foreach ($teachers as $teacher) {
            if (array_key_exists('surname', $criteria)) {
                $authorMeetSearchCriteria = $criteria['surname'] === $teacher['surname'];
            } else {
                $authorMeetSearchCriteria = true;
            }

            if ($authorMeetSearchCriteria && array_key_exists('id', $criteria)) {
                $authorMeetSearchCriteria = $criteria['id'] === $teacher['id'];
            }
            if ($authorMeetSearchCriteria) {
                $foundTeacher[] = TeacherUserClass::createFromArray($teacher);
            }
        }

        return $foundTeacher;
    }

}