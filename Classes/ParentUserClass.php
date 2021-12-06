<?php

class ParentUserClass extends AbstractUserClass
{
    /**
     * @string Место работы родителя
     */
    private string $placeOfWork;

    /**
     * @var string email родителя
     */
    private string $email;

    /**
     * @return string Получить Место работы родителя
     */
    public function getPlaceOfWork(): string
    {
        return $this->placeOfWork;
    }

    /**
     * @param string $placeOfWork установить Место работы родителя
     */
    public function setPlaceOfWork(string $placeOfWork): void
    {
        $this->placeOfWork = $placeOfWork;
    }

    /**
     * @return string получить email родителя
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email установить email родителя
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function jsonSerialize()
    {
        $jsonData = parent::jsonSerialize();
        $jsonData['placeOfWork'] = $this->placeOfWork;
        $jsonData['email'] = $this->email;
        return $jsonData;
    }
}