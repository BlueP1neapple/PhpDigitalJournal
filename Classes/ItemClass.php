<?php

class ItemClass implements JsonSerializable
{
    /**
     * @int id предмета
     */
    private int $id;

    /**
     * @string имя предмета
     */
    private string $name;

    /**
     * @var string Полное название предмета
     */
    private string $description;

    /**
     * @return int получить id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id установить id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string получить имя предмета
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name установить имя предмета
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string получить Полное название предмета
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description установить Полное название предмета
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }


    public function jsonSerialize()
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'description'=>$this
        ];
    }
}