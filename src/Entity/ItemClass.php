<?php
    require_once __DIR__ . '/../Infrastructure/InvalidDataStructureException.php';
    /**
     * Класс Предметов
     */
    final class ItemClass implements JsonSerializable
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
         * Конструктор класса предметов
         * @param int $id - id Предмета
         * @param string $name - Название Предмета
         * @param string $description - Расщифровка названия предмета
         */
        public function __construct(int $id, string $name, string $description)
        {
            $this->id = $id;
            $this->name = $name;
            $this->description = $description;
        }


        /**
         * @return int получить id
         */
        public function getId(): int
        {
            return $this->id;
        }


        /**
         * @return string получить имя предмета
         */
        public function getName(): string
        {
            return $this->name;
        }


        /**
         * @return string получить Полное название предмета
         */
        public function getDescription(): string
        {
            return $this->description;
        }


        /**
         * Метод получения массива для кодирования в json
         * @return array - массив данных для кодирования в json
         */
        public function jsonSerialize(): array
        {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'description' => $this->description
            ];
        }

        /**
         * Метод создания объекта класса Предмет из массива данных об предмете
         * @param array $data - массив данных о предмете
         * @return ItemClass - Объект класса предмет
         */
        public static function createFromArray(array $data): ItemClass
        {
            $requiredFields=[
                'id',
                'name',
                'description'
            ];
            $missingFields=array_diff($requiredFields,array_keys($data));
            if(count($missingFields)>0){
                $errMsg=sprintf('Отсутвуют обязательные элементы: %s',implode(',',$missingFields));
                throw new InvalidDataStructureException($errMsg);
            }
            return new ItemClass(
                $data['id'],
                $data['name'],
                $data['description']
            );
        }

    }