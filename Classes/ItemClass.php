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
    public function setId(int $id): ItemClass
    {
        $this->id = $id;
        return $this;
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
    public function setName(string $name): ItemClass
    {
        $this->name = $name;
        return $this;
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
    public function setDescription(string $description): ItemClass
    {
        $this->description = $description;
        return $this;
    }


    public function jsonSerialize():array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'description'=>$this->description
        ];
    }
}