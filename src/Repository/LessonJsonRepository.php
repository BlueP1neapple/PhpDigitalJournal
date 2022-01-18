<?php

namespace JoJoBizzareCoders\DigitalJournal\Repository;

use JoJoBizzareCoders\DigitalJournal\Entity\ClassClass;
use JoJoBizzareCoders\DigitalJournal\Entity\ItemClass;
use JoJoBizzareCoders\DigitalJournal\Entity\LessonClass;
use JoJoBizzareCoders\DigitalJournal\Entity\LessonRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\TeacherUserClass;
use JoJoBizzareCoders\DigitalJournal\Exception\InvalidDataStructureException;
use JoJoBizzareCoders\DigitalJournal\Exception\RuntimeException;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DataLoader\DataLoaderInterface;
use JoJoBizzareCoders\DigitalJournal\ValueObject\Address;
use JoJoBizzareCoders\DigitalJournal\ValueObject\Fio;
use JsonException;

/**
 * Репризиторий для поиска занятий. В качестве хранилища используеться json файлы
 */
class LessonJsonRepository implements LessonRepositoryInterface
{

    /**
     * Путь до файла с данными об предметах
     *
     * @var string
     */
    private string $pathToItems;

    /**
     * Путь до файла с данными об Учителях
     *
     * @var string
     */
    private string $pathToTeachers;

    /**
     * Путь до файла с данными об Классах
     *
     * @var string
     */
    private string $pathToClasses;

    /**
     * Путь до файла с данными об Занятиях
     *
     * @var string
     */
    private string $pathToLesson;

    /**
     * Загрузчик данных
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
     * Данные об учениках
     *
     * @var array|null
     */
    private ?array $teachersIdToInfo = null;

    /**
     * Данные об классах
     *
     * @var array|null
     */
    private ?array $classesIdToInfo = null;

    /**
     * Данные об занятиях
     *
     * @var array|null
     */
    private ?array $lessonIdToInfo = null;

    /**
     * Сопоставляет id занятия с номером элемаента в $lessonIdToInfo
     *
     * @var array|null
     */
    private ?array $lessonIdToIndex = null;


    /**
     * Тееущий id
     *
     * @var int
     */
    private int $currentId;
    /**
     * Конструктор репризитория для поиска занятий. В качестве хранилища используеться json файлы
     *
     * @param string $pathToItems - Путь до файла с данными об предметах
     * @param string $pathToTeachers - Путь до файла с данными об Учителях
     * @param string $pathToClasses - Путь до файла с данными об Классах
     * @param string $pathToLesson - Путь до файла с данными об Занятиях
     * @param DataLoaderInterface $dataLoader - Загрузчик данных
     */
    public function __construct(
        string $pathToItems,
        string $pathToTeachers,
        string $pathToClasses,
        string $pathToLesson,
        DataLoaderInterface $dataLoader
    ) {
        $this->pathToItems = $pathToItems;
        $this->pathToTeachers = $pathToTeachers;
        $this->pathToClasses = $pathToClasses;
        $this->pathToLesson = $pathToLesson;
        $this->dataLoader = $dataLoader;
    }

    /**
     * Загрузка сущностей Предметы
     *
     * @return array
     * @throws JsonException
     */
    private function loadEntityItems(): array
    {
        if (null === $this->itemsIdToInfo) {
            $itemsIdToInfo = [];
            $items = $this->dataLoader->LoadDate($this->pathToItems);
            foreach ($items as $item) {
                $itemsObj = ItemClass::createFromArray($item);
                $itemsIdToInfo[$itemsObj->getId()] = $itemsObj;
            }
            $this->itemsIdToInfo = $itemsIdToInfo;
        }
        return $this->itemsIdToInfo;
    }

    /**
     * Загрузка сущностей Учителя
     *
     * @return array
     * @throws JsonException
     */
    private function loadEntityTeachers(): array
    {
        if (null === $this->teachersIdToInfo) {
            $teachersIdToInfo = [];
            $teachers = $this->dataLoader->LoadDate($this->pathToTeachers);
            $itemsIdToInfo = $this->loadEntityItems();
            foreach ($teachers as $teacher) {
                $teacher['idItem'] = $itemsIdToInfo[$teacher['idItem']];
                $teacher['fio'] = $this->createArrayFio($teacher);
                $teacher['address'] = $this->createArrayAddress($teacher);
                $teachersObj = TeacherUserClass::createFromArray($teacher);
                $teachersIdToInfo[$teachersObj->getId()] = $teachersObj;
            }
            $this->teachersIdToInfo = $teachersIdToInfo;
        }
        return $this->teachersIdToInfo;
    }

    /**
     * Загрузка сущностей Классы
     *
     * @return array
     * @throws JsonException
     */
    private function loadEntityClasses(): array
    {
        if (null === $this->classesIdToInfo) {
            $classesIdToInfo = [];
            $classes = $this->dataLoader->LoadDate($this->pathToClasses);
            foreach ($classes as $class) {
                $classesObj = ClassClass::createFromArray($class);
                $classesIdToInfo[$classesObj->getId()] = $classesObj;
            }
            $this->classesIdToInfo = $classesIdToInfo;
        }
        return $this->classesIdToInfo;
    }

    /**
     * Загрузка данных о Занятиях из Файла в массив
     *
     * @return array
     * @throws JsonException
     */
    private function LoadDataLesson(): array
    {
        if (null === $this->lessonIdToInfo) {
            $this->lessonIdToInfo = $this->dataLoader->LoadDate($this->pathToLesson);
            $this->lessonIdToIndex = array_combine(
                array_map(
                    static function (array $v) {
                        return $v['id'];
                    },
                    $this->lessonIdToInfo
                ),
                array_keys($this->lessonIdToInfo)
            );

            $this->currentId = max(
                array_map(
                    static function(array $v){ return $v['id'];},
                    $this->lessonIdToInfo
                )
            );

        }
        return $this->lessonIdToInfo;
    }

    /**
     * Создание массива фио пользователя
     *
     * @param $user - коллекция объектов пользователей
     * @return array
     */
    private function createArrayFio(array $user): array
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
     * Метод отвечающий за создание объекта Занятия
     *
     * @param array $lesson - отобранное по критериям занятие
     * @param array $itemsIdToInfo - мвссив сущностей Придметы
     * @param array $teachersIdToInfo - массив сущностей Учителя
     * @param array $classesIdToInfo - массив сущностей Классы
     * @return LessonClass - объект класса Lesson
     */
    private function lessonFactory(
        array $lesson,
        array $itemsIdToInfo,
        array $teachersIdToInfo,
        array $classesIdToInfo
    ): LessonClass {
        $lesson['item_id'] = $itemsIdToInfo[$lesson['item_id']];
        $lesson['teacher_id'] = $teachersIdToInfo[$lesson['teacher_id']];
        $lesson['class_id'] = $classesIdToInfo[$lesson['class_id']];
        return LessonClass::createFromArray($lesson);
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function findBy(array $criteria): array
    {
        $lessons = $this->LoadDataLesson();
        $itemsIdToInfo = $this->loadEntityItems();
        $teachersIdToInfo = $this->loadEntityTeachers();
        $classesIdToInfo = $this->loadEntityClasses();
        $foundLessons = [];
        $LessonMeetSearchCriteria = true;
        foreach ($lessons as $lesson) // Цикл по все занятиям. [начало]
        {
            if (array_key_exists('item_name', $criteria)) {
                $LessonMeetSearchCriteria = ($criteria['item_name'] === $itemsIdToInfo[$lesson['item_id']]
                        ->getName());
            }
            if (array_key_exists('item_description', $criteria)) {
                $LessonMeetSearchCriteria = ($criteria['item_description'] ===
                    $itemsIdToInfo[$lesson['item_id']]->getDescription());
            }
            if (array_key_exists('date', $criteria)) {
                $LessonMeetSearchCriteria = ($criteria['date'] === $lesson['date']);
            }
            if (array_key_exists('teacher_fio_surname', $criteria)) {
                $LessonMeetSearchCriteria = ($criteria['teacher_fio_surname'] ===
                    $teachersIdToInfo[$lesson['teacher_id']]->getFio()[0]->getSurname());
            }
            if (array_key_exists('teacher_fio_name', $criteria)) {
                $LessonMeetSearchCriteria = ($criteria['teacher_fio_name'] === $teachersIdToInfo[$lesson['teacher_id']]
                        ->getFio()[0]
                        ->getName());
            }
            if (array_key_exists('teacher_fio_patronymic', $criteria)) {
                $LessonMeetSearchCriteria = ($criteria['teacher_fio_patronymic'] ===
                    $teachersIdToInfo[$lesson['teacher_id']]->getFio()[0]->getPatronymic());
            }
            if (array_key_exists('teacher_cabinet', $criteria)) {
                $LessonMeetSearchCriteria = ($criteria['teacher_cabinet']
                    === $teachersIdToInfo[$lesson['teacher_id']]->getCabinet());
            }
            if (array_key_exists('class_number', $criteria)) {
                $LessonMeetSearchCriteria = ($criteria['class_number']
                    === $classesIdToInfo[$lesson['class_id']]->getNumber());
            }
            if (array_key_exists('class_letter', $criteria)) {
                $LessonMeetSearchCriteria = ($criteria['class_letter'] === $classesIdToInfo[$lesson['class_id']]
                        ->getLetter());
            }
            if (array_key_exists('id', $criteria)) {
                $LessonMeetSearchCriteria = (int)$criteria['id'] === (int)$lesson['id'];
            }

            if ($LessonMeetSearchCriteria) {
                $foundLessons[] = $this->lessonFactory(
                    $lesson,
                    $itemsIdToInfo,
                    $teachersIdToInfo,
                    $classesIdToInfo
                );
            }
        }
        return $foundLessons;
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function save(LessonClass $entity): LessonClass
    {
        $this->LoadDataLesson();
        $lesson = $this->lessonIdToInfo;
        $itemIndex = $this->getItemIndex($entity);
        $item = $this->buildJsonData($entity);
        $lesson[$itemIndex] = $item;
        $file = $this->pathToLesson;
        $jsonStr = json_encode($lesson, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($file, $jsonStr);
        return $entity;
    }

    /**
     * Получение индекса элемента с данными для занятия на основе id сущности
     *
     * @param LessonClass $entity - Сущность
     * @return int
     */
    private function getItemIndex(LessonClass $entity): int
    {
        $id = $entity->getId();
        $entityToIndex = $this->lessonIdToIndex;
        if (false === array_key_exists($id, $entityToIndex)) {
            throw new RuntimeException("Занятия с id = '$id', не найден в хранилище");
        }
        return $entityToIndex[$id];
    }

    /**
     * Логика сериализации данных о занятий
     *
     * @param LessonClass $entity - сущность занятий
     * @return array
     */
    private function buildJsonData(LessonClass $entity): array
    {
        return [
            'id' => $entity->getId(),
            'item_id' => $entity->getItem()->getId(),
            'date' => $entity->getDate(),
            'lessonDuration' => $entity->getLessonDuration(),
            'teacher_id' => $entity->getTeacher()->getId(),
            'class_id' => $entity->getClass()->getId()
        ];
    }

    /**
     * Извлекает данные из id
     *
     * @param $v
     * @return int
     */
    private function extractLessonId($v):int
    {
        if(false === is_array($v)){
            throw new InvalidDataStructureException('dannystmassiv');
        }
        if(false === array_key_exists('id', $v)){
            throw new InvalidDataStructureException('net id');
        }
        if(false === is_int($v['id'])){
            throw new InvalidDataStructureException(
                'id ne chislo'
            );
        }

        return $v['id'];
    }

    /**
     * Получить следующий id
     *
     * @return int
     * @throws JsonException
     */
    public function nextId(): int
    {
        $this->LoadDataLesson();
        ++$this->currentId;
        return $this->currentId;
    }

    /**
     * Добавление урока
     *
     * @param LessonClass $entity
     * @return LessonClass
     * @throws JsonException
     */
    public function add(LessonClass $entity): LessonClass{

        $this->LoadDataLesson();

        $item = $this->buildJsonData($entity);
        $this->lessonIdToInfo[] = $item;
        $data = $this->lessonIdToInfo;
        $this->lessonIdToIndex[$entity->getId()] = array_key_last($this->lessonIdToInfo);
        $file = $this->pathToLesson;

        $jsonSrt = json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        file_put_contents($file, $jsonSrt);

        return $entity;
    }


}