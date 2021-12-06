<?php

class ClassClass implements JsonSerializable
{

    /**
     * @var int id класса
     */
    private int $id;

    /**
     * @var int Номер класса
     */
    private int $number;

    /**
     * @var string Буква класса
     */
    private string $letter;

    /**
     * @return int получить id класса
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id установить id класса
     */
    public function setId(int $id): ClassClass
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int получить Номер класса
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @param int $number установить Номер класса
     */
    public function setNumber(int $number): ClassClass
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return string получить Букву класса
     */
    public function getLetter(): string
    {
        return $this->letter;
    }

    /**
     * @param string $letter установить Букву класса
     */
    public function setLetter(string $letter): ClassClass
    {
        $this->letter = $letter;
        return $this;
    }


    public function jsonSerialize()
    {
        return[
            'id' => $this->id,
            'number' => $this->number,
            'letter' => $this->letter
        ];
    }
}