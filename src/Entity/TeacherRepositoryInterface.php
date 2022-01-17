<?php

namespace JoJoBizzareCoders\DigitalJournal\Entity;

interface TeacherRepositoryInterface
{

    /**
     * Поиск сущностей по критериям
     *
     * @param array $criteria
     * @return TeacherUserClass[]
     */
    public function findBy(array $criteria):array;

}