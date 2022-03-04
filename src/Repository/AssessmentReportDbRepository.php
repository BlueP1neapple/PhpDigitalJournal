<?php

namespace JoJoBizzareCoders\DigitalJournal\Repository;

use JoJoBizzareCoders\DigitalJournal\Entity\AssessmentReportRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\ClassClass;
use JoJoBizzareCoders\DigitalJournal\Entity\ItemClass;
use JoJoBizzareCoders\DigitalJournal\Entity\LessonClass;
use JoJoBizzareCoders\DigitalJournal\Entity\ParentUserClass;
use JoJoBizzareCoders\DigitalJournal\Entity\ReportClass;
use JoJoBizzareCoders\DigitalJournal\Entity\StudentUserClass;
use JoJoBizzareCoders\DigitalJournal\Entity\TeacherUserClass;
use JoJoBizzareCoders\DigitalJournal\Exception\InvalidDataStructureException;
use JoJoBizzareCoders\DigitalJournal\Exception\RuntimeException;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DataLoader\DataLoaderInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DataLoader\JsonDataLoader;
use JoJoBizzareCoders\DigitalJournal\ValueObject\Address;
use JoJoBizzareCoders\DigitalJournal\ValueObject\Fio;
use JsonException;

/**
 * Репризиторий для поиска оценок. В качестве хранилища используеться json файлы
 */
class AssessmentReportDbRepository implements AssessmentReportRepositoryInterface
{

    /**
     * Реализация логики создания электронного журнала
     *
     * @param array $report - массив оценок
     * @param array $lessonIdToInfo - Массив сущностей Занятий
     * @param array $studentIdToInfo - Массив сущностей Студентов
     * @return ReportClass
     */
    private function ReportFactory(array $report, array $lessonIdToInfo, array $studentIdToInfo): ReportClass
    {
        $report['lesson_id'] = $lessonIdToInfo[$report['lesson_id']];
        $report['student_id'] = $studentIdToInfo[$report['student_id']];
        return ReportClass::createFromArray($report);
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
     * @inheritDoc
     * @throws JsonException
     */
    public function findBy(array $criteria): array
    {

    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function save(ReportClass $entity): ReportClass
    {

    }




    /**
     * Получить сделуйщий айди
     *
     * @return int
     * @throws JsonException
     */
    public function nextId(): int
    {
        $this->LoadDataReport();
        ++$this->currentId;
        return $this->currentId;
    }


    /**
     *  Добавление
     *
     * @param ReportClass $entity
     * @return ReportClass
     * @throws JsonException
     */
    public function add(ReportClass $entity): ReportClass
    {


    }


}