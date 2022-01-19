<?php

namespace JoJoBizzareCoders\DigitalJournal\Entity;

/**
 * Интерфейс репризитория студентов
 */
interface StudentRepositoryInterface extends UserRepositoryInterface
{
    /**
     * Поиск сущностей по заданным критериям
     *
     * @param array $criteria - заданные критерия
     * @return array
     */
    public function findBy(array $criteria):array;

    /**
     * Возвращает id создания следующего студента
     *
     * @return int
     */
    public function nexId():int;

    /**
     * Добавляет новую сущность
     *
     * @param StudentUserClass $entity - сущность
     * @return mixed
     */
    public function add(StudentUserClass $entity):StudentUserClass;
}
