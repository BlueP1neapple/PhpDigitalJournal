<?php

namespace JoJoBizzareCoders\DigitalJournal\Repository;

use JoJoBizzareCoders\DigitalJournal\Entity\ItemClass;
use JoJoBizzareCoders\DigitalJournal\Entity\TeacherRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\TeacherUserClass;
use JoJoBizzareCoders\DigitalJournal\Exception\InvalidDataStructureException;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DataLoader\DataLoaderInterface;
use JoJoBizzareCoders\DigitalJournal\ValueObject\Address;
use JoJoBizzareCoders\DigitalJournal\ValueObject\Fio;
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
     * Путь до предметов
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
     * Данные о предметах
     *
     * @var array|null
     */
    private ?array $itemsIdToInfo = null;

    /**
     * Данные о учетелях
     *
     * @var array|null
     */
    private ?array $data = null;

    /**
     * @param string $pathToTeacher
     * @param DataLoaderInterface $dataLoader
     * @param string $pathToItems
     */
    public function __construct(
        string $pathToTeacher,
        string $pathToItems,
        DataLoaderInterface $dataLoader)
    {
        $this->pathToTeachers = $pathToTeacher;
        $this->dataLoader = $dataLoader;
        $this->pathToItems = $pathToItems;
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
        $items = $this->loadItemsEntity();
        foreach ($teachers as $teacher) {
            if (array_key_exists('surname', $criteria)) {
                $teacherMeetSearchCriteria = $criteria['surname'] === $teacher['surname'];
            } else {
                $teacherMeetSearchCriteria = true;
            }

            if ($teacherMeetSearchCriteria && array_key_exists('id', $criteria)) {
                $teacherMeetSearchCriteria = $criteria['id'] === $teacher['id'];
            }
            if ($teacherMeetSearchCriteria) {
                $foundTeacher[] = $this->teacherFactory($teacher, $items);
                $teacherMeetSearchCriteria = false;
            }
        }

        return $foundTeacher;
    }

    /**
     * создание Учителя
     *
     * @param array $teacher - отобранный по критериям Учитель
     * @param array $itemIdToInfo
     * @return TeacherUserClass
     */
    private function teacherFactory(array $teacher, array $itemIdToInfo): TeacherUserClass
    {
        $teacher['idItem'] = $itemIdToInfo[$teacher['idItem']];
        $teacher['fio'] = $this->createArrayFio($teacher);
        $teacher['address'] = $this->createArrayAddress($teacher);
        return TeacherUserClass::createFromArray($teacher);
    }


    /**
     * Создание массива фио пользователя
     *
     * @param $teacher - коллекция объектов пользователей
     * @return void
     */
    private function createArrayFio(array $teacher): array
    {
        if (false === array_key_exists('fio', $teacher)) {
            throw new InvalidDataStructureException('Нет данных о фио');
        }
        if (false === is_array($teacher['fio'])) {
            throw new InvalidDataStructureException('Данные о фио имеют неверный формат');
        }
        $fio = [];
        foreach ($teacher['fio'] as $teacherData) {
            $fio[] = $this->createFio($teacherData);
        }
        return $fio;
    }

    /**
     * Создание фио пользователя
     *
     * @param $teacherData - иформация об фио пользователя
     * @return void
     */
    private function createFio($teacherData): Fio
    {
        if (false === is_array($teacherData)) {
            throw new InvalidDataStructureException('Данные о фио имеют неверный формат');
        }
        if (false === array_key_exists('surname', $teacherData)) {
            throw new InvalidDataStructureException('Отсутствуют данные о фамилии пользователей');
        }
        if (false === is_string($teacherData['surname'])) {
            throw new InvalidDataStructureException('Данные о фамилии пользователей имеют не верный формат');
        }
        if (false === array_key_exists('name', $teacherData)) {
            throw new InvalidDataStructureException('Отсутствуют данные о имени пользователей');
        }
        if (false === is_string($teacherData['name'])) {
            throw new InvalidDataStructureException('Данные о имени пользователей имеют не верный формат');
        }
        if (false === array_key_exists('patronymic', $teacherData)) {
            throw new InvalidDataStructureException('Отсутствуют данные о отчестве пользователей');
        }
        if (false === is_string($teacherData['patronymic'])) {
            throw new InvalidDataStructureException('Данные о отчестве пользователей имеют не верный формат');
        }
        return new Fio(
            $teacherData['surname'],
            $teacherData['name'],
            $teacherData['patronymic']
        );
    }


    /**
     * Создаём коллекцию адрессов пользователя
     *
     * @param $teacher -  - коллекция объектов пользователей
     * @return void
     */
    private function createArrayAddress(array $teacher): array
    {
        if (false === array_key_exists('address', $teacher)) {
            throw new InvalidDataStructureException('Нет данных о аддрессе');
        }
        if (false === is_array($teacher['address'])) {
            throw new InvalidDataStructureException('Данные о аддрессе имеют неверный формат');
        }
        $address = [];
        foreach ($teacher['address'] as $userData) {
            $address[] = $this->createAddress($userData);
        }
        return $address;
    }

    /**
     * Создание аддресса
     *
     * @param $userData - иформация о адрессе пользователей
     * @return void
     */
    private function createAddress($userData): Address
    {
        if (false === is_array($userData)) {
            throw new InvalidDataStructureException('Данные о аддрессах имеют неверный формат');
        }
        if (false === array_key_exists('street', $userData)) {
            throw new InvalidDataStructureException('Отсутствуют данные о улице пользователей');
        }
        if (false === is_string($userData['street'])) {
            throw new InvalidDataStructureException('Данные о улице пользователей имеют не верный формат');
        }
        if (false === array_key_exists('home', $userData)) {
            throw new InvalidDataStructureException('Отсутствуют данные о номере дома пользователей');
        }
        if (false === is_string($userData['home'])) {
            throw new InvalidDataStructureException('Данные о номере дома пользователей имеют не верный формат');
        }
        if (false === array_key_exists('apartment', $userData)) {
            throw new InvalidDataStructureException('Отсутствуют данные о номере квартире пользователей');
        }
        if (false === is_string($userData['apartment'])) {
            throw new InvalidDataStructureException(
                'Данные о номере квартире пользователей имеют не верный формат'
            );
        }
        return new Address(
            $userData['street'],
            $userData['home'],
            $userData['apartment']
        );
    }


    private function loadItemsEntity(): array
    {
        if (null === $this->itemsIdToInfo){
            $items = $this->dataLoader->LoadDate($this->pathToItems);
            $itemsIdToInfo = [];
            foreach ($items as $item) {
                $itemsObj = ItemClass::createFromArray($item);
                $itemsIdToInfo[$itemsObj->getId()] = $itemsObj;
            }
            $this->itemsIdToInfo=$itemsIdToInfo;
        }
        return $this->itemsIdToInfo;
    }

}