<?php

namespace JoJoBizzareCoders\DigitalJournal\Entity;

interface ItemRepositoryInterface
{
    /**
     * Поиск сущностей по критериям
     *
     * @param array $criteria
     * @return TeacherUserClass[]
     */
    public function findBy(array $criteria):array;
}