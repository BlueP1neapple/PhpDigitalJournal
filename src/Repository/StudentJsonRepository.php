<?php

namespace JoJoBizzareCoders\DigitalJournal\Repository;

use JoJoBizzareCoders\DigitalJournal\Entity\ClassClass;
use JoJoBizzareCoders\DigitalJournal\Entity\ParentUserClass;
use JoJoBizzareCoders\DigitalJournal\Entity\StudentRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\StudentUserClass;
use JoJoBizzareCoders\DigitalJournal\Exception\InvalidDataStructureException;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DataLoader\DataLoaderInterface;
use JoJoBizzareCoders\DigitalJournal\ValueObject\Address;
use JoJoBizzareCoders\DigitalJournal\ValueObject\Fio;
use JsonException;

/**
 * Репризиторий для поиска студента
 */
final class StudentJsonRepository implements StudentRepositoryInterface
{
    /**
     * Путь до файла с данными об студентах
     *
     * @var string
     */
    private string $pathToStudent;

    /**
     * Путь до файла с данными об Классах
     *
     * @var string
     */
    private string $pathToClasses;

    /**
     * Путь до файла с данными об Родителях
     *
     * @var string
     */
    private string $pathToParents;

    /**
     * Загрузчик данных
     *
     * @var DataLoaderInterface
     */
    private DataLoaderInterface $dataLoader;

    /**
     * Данные о классах
     *
     * @var array|null
     */
    private ?array $classesIdToInfo = null;

    /**
     * Данные о родителях
     *
     * @var array|null
     */
    private ?array $parentsIdToInfo = null;

    /**
     * Данные об студентах
     *
     * @var array|null
     */
    private ?array $studentData = null;

    /**
     * Текущее значение id оценки
     *
     * @var int
     */
    private int $currentId;


    /**
     * @param string $pathToStudent - Путь до файла с данными об студентах
     * @param string $pathToClasses - Путь до файла с данными об Классах
     * @param string $pathToParents - Путь до файла с данными об Родителях
     * @param DataLoaderInterface $dataLoader - Загрузчик данных
     */
    public function __construct(
        string $pathToStudent,
        string $pathToClasses,
        string $pathToParents,
        DataLoaderInterface $dataLoader
    ) {
        $this->pathToStudent = $pathToStudent;
        $this->pathToClasses = $pathToClasses;
        $this->pathToParents = $pathToParents;
        $this->dataLoader = $dataLoader;
    }

    /**
     * Загрузка сущностей Классы
     *
     * @return array
     * @throws JsonException
     */
    private function loadEntityClass(): array
    {
        if (null === $this->classesIdToInfo) {
            $classesIdToInfo = [];
            $classes = $this->dataLoader->LoadDate($this->pathToClasses);
            foreach ($classes as $class) {
                $classObj = ClassClass::createFromArray($class);
                $classesIdToInfo[$classObj->getId()] = $classObj;
            }
            $this->classesIdToInfo = $classesIdToInfo;
        }
        return $this->classesIdToInfo;
    }

    /**
     * Загрузка сущностей Родителя
     *
     * @return array
     * @throws JsonException
     */
    private function loadEntityParents(): array
    {
        if (null === $this->parentsIdToInfo) {
            $parentsIdToInfo = [];
            $parents = $this->dataLoader->LoadDate($this->pathToParents);
            foreach ($parents as $parent) {
                $parent['fio'] = $this->createArrayFio($parent);
                $parent['address'] = $this->createArrayAddress($parent);
                $parentObj = ParentUserClass::createFromArray($parent);
                $parentsIdToInfo[$parentObj->getId()] = $parentObj;
            }
            $this->parentsIdToInfo = $parentsIdToInfo;
        }
        return $this->parentsIdToInfo;
    }

    /**
     * Загрузка данных о Занятиях из Файла в массив
     *
     * @return array
     * @throws JsonException
     */
    private function loadDataStudent(): array
    {
        if (null === $this->studentData) {
            $this->studentData = $this->dataLoader->LoadDate($this->pathToStudent);
            $this->currentId = max(
                array_map(
                    [$this, 'extractStudentId'],
                    $this->studentData
                )
            );
        }
        return $this->studentData;
    }

    /**
     * Извлекает данные о id студента
     *
     * @param $student
     * @return int
     */
    private function extractStudentId($student): int
    {
        if (false === is_array($student)) {
            throw new InvalidDataStructureException(
                'Данные о занятии должны быть массиом'
            );
        }
        if (false === array_key_exists('id', $student)) {
            throw new InvalidDataStructureException(
                'Отсутсвуют данные о id занятия'
            );
        }
        if (false === is_int($student['id'])) {
            throw new InvalidDataStructureException(
                'id занятия должен быть целым числом'
            );
        }
        return $student['id'];
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function findBy(array $criteria): array
    {
        $students = $this->loadDataStudent();
        $classIdToInfo = $this->loadEntityClass();
        $parentIdToInfo = $this->loadEntityParents();
        $foundStudents = [];
        $studentMeetSearchCriteria = true;
        foreach ($students as $student) {
            if (array_key_exists('id', $criteria)) {
                $studentMeetSearchCriteria = ($student['id'] === $criteria['id']);
            }
            if ($studentMeetSearchCriteria) {
                $foundStudents[] = $this->studentFactory(
                    $student,
                    $classIdToInfo,
                    $parentIdToInfo
                );
            }
        }
        return $foundStudents;
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function nexId(): int
    {
        $this->loadDataStudent();
        ++$this->currentId;
        return $this->currentId;
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function add(StudentUserClass $entity): StudentUserClass
    {
        $object = $this->buildJsonData($entity);
        $this->studentData[] = $object;
        $data = $this->studentData;
        $file = $this->pathToStudent;
        $jsonStr = json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($file, $jsonStr);
        return $entity;
    }

    /**
     * Создание массива фио пользователя
     *
     * @param $user - коллекция объектов пользователей
     * @return array
     */
    private function createArrayFio($user): array
    {
        if (false === array_key_exists('fio', $user)) {
            throw new InvalidDataStructureException('Нет данных о фио');
        }
        if (false === is_array($user['fio'])) {
            throw new InvalidDataStructureException('Данные о фио имеют неверный формат');
        }
        $fio = [];
        foreach ($user['fio'] as $userData) {
            $fio[] = $this->createFio($userData);
        }
        return $fio;
    }

    /**
     * Создание фио пользователя
     *
     * @param  $userData - иформация об фио пользователя
     * @return Fio
     */
    private function createFio($userData): Fio
    {
        if (false === is_array($userData)) {
            throw new InvalidDataStructureException('Данные о фио имеют неверный формат');
        }
        if (false === array_key_exists('surname', $userData)) {
            throw new InvalidDataStructureException('Отсутствуют данные о фамилии пользователей');
        }
        if (false === is_string($userData['surname'])) {
            throw new InvalidDataStructureException('Данные о фамилии пользователей имеют не верный формат');
        }
        if (false === array_key_exists('name', $userData)) {
            throw new InvalidDataStructureException('Отсутствуют данные о имени пользователей');
        }
        if (false === is_string($userData['name'])) {
            throw new InvalidDataStructureException('Данные о имени пользователей имеют не верный формат');
        }
        if (false === array_key_exists('patronymic', $userData)) {
            throw new InvalidDataStructureException('Отсутствуют данные о отчестве пользователей');
        }
        if (false === is_string($userData['patronymic'])) {
            throw new InvalidDataStructureException('Данные о отчестве пользователей имеют не верный формат');
        }
        return new Fio(
            $userData['surname'],
            $userData['name'],
            $userData['patronymic']
        );
    }

    /**
     * Создаём коллекцию адрессов пользователя
     *
     * @param $user - коллекция объектов пользователей
     * @return array
     */
    private function createArrayAddress($user): array
    {
        if (false === array_key_exists('address', $user)) {
            throw new InvalidDataStructureException('Нет данных о аддрессе');
        }
        if (false === is_array($user['address'])) {
            throw new InvalidDataStructureException('Данные о аддрессе имеют неверный формат');
        }
        $address = [];
        foreach ($user['address'] as $userData) {
            $address[] = $this->createAddress($userData);
        }
        return $address;
    }

    /**
     * Создание аддресс пользователя
     *
     * @param $userData - иформация о адрессе пользователей
     * @return Address
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

    /**
     * Метод отвечающий за создание объекта студента
     *
     * @param $student
     * @param array $classIdToInfo
     * @param array $parentIdToInfo
     * @return StudentUserClass
     */
    private function studentFactory(
        $student,
        array $classIdToInfo,
        array $parentIdToInfo
    ): StudentUserClass {
        $student['class_id'] = $classIdToInfo[$student['class_id']];
        $student['parent_id'] = $parentIdToInfo[$student['parent_id']];
        return StudentUserClass::createFromArray($student);
    }

    /**
     * Логика сериализации данных о студенте
     *
     * @param StudentUserClass $entity
     * @return array
     */
    private function buildJsonData(StudentUserClass $entity): array
    {
        return [
            'id' => $entity->getId(),
            'fio' => [
                'surname' => $entity->getFio()[0]->getSurname(),
                'name' => $entity->getFio()[0]->getName(),
                'patronymic' => $entity->getFio()[0]->getPatronymic()
            ],
            'dateOfBirth'=>$entity->getDateOfBirth(),
            'phone'=>$entity->getPhone(),
            'address'=>[
                'street'=>$entity->getAddress()[0]->getStreet(),
                'home'=>$entity->getAddress()[0]->getHome(),
                'apartment'=>$entity->getAddress()[0]->getApartment()
            ],
            'class_id'=>$entity->getClass()->getId(),
            'parent_id'=>$entity->getParent()->getId()
        ];
    }
}
