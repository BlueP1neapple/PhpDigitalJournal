<?php

class TeacherUserClass extends AbstractUserClass
{

    /**
     *  Предмета
     */
    private ItemClass $item;

    /**
     * @var int Кабинет учителя
     */
    private int $cabinet;

    /**
     * @var string Email учителя
     */
    private string $email;

    /**
     *  Получить предмета учителя
     */
    public function getItem(): ItemClass
    {
        return $this->item;
    }

    /**
     * Установить предмета учителя
     */
    public function setItem(ItemClass $item): TeacherUserClass
    {
        $this->item = $item;
        return $this;
    }

    /**
     * @return int Получить номер кабинета учителя
     */
    public function getCabinet(): int
    {
        return $this->cabinet;
    }

    /**
     * @param int $cabinet Установить номер кабинета учителя
     */
    public function setCabinet(int $cabinet): TeacherUserClass
    {
        $this->cabinet = $cabinet;
        return $this;
    }

    /**
     * @return string Получить Email учителя
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email Установить Email учителя
     */
    public function setEmail(string $email): TeacherUserClass
    {
        $this->email = $email;
        return $this;
    }

    public function jsonSerialize()
    {
        $jsonData = parent::jsonSerialize();
        $jsonData['item'] = $this->item;
        $jsonData['cabinet'] = $this->cabinet;
        $jsonData['email'] = $this->email;


        return  $jsonData;
    }


}