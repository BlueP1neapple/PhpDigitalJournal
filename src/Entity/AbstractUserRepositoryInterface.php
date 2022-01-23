<?php

namespace JoJoBizzareCoders\DigitalJournal\Entity;

interface AbstractUserRepositoryInterface
{
    public function findBy(array $criteria):array;


}
