<?php
namespace JoJoBizzareCoders\DigitalJournal\Entity;
    use JoJoBizzareCoders\DigitalJournal\Exception\InvalidDataStructureException;
    use JsonSerializable;


    /**
     * Класс классов
     */
    final class ClassClass implements JsonSerializable
    {
        // Свойства
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

        // Методы
        /**
         * Конструтор классов
         * @param int $id
         * @param int $number
         * @param string $letter
         */
        public function __construct(int $id, int $number, string $letter)
        {
            $this->id = $id;
            $this->number = $number;
            $this->letter = $letter;
        }

        /**
         * @return int получить id класса
         */
        public function getId(): int
        {
            return $this->id;
        }

        /**
         * @return int получить Номер класса
         */
        public function getNumber(): int
        {
            return $this->number;
        }

        /**
         * @return string получить Букву класса
         */
        public function getLetter(): string
        {
            return $this->letter;
        }

        /**
         * @return array - массив для кодирования в json
         */
        public function jsonSerialize(): array
        {
            return [
                'id' => $this->id,
                'number' => $this->number,
                'letter' => $this->letter
            ];
        }

        /**
         * Метод создания объекта класса классов из масиива данных о классах
         * @param array $data - массив данных о классах
         * @return ClassClass - Объект класса классов
         * @throws InvalidDataStructureException
         */
        public static function createFromArray(array $data): ClassClass
        {
            $requiredFields=[
                'id',
                'number',
                'letter'
            ];
            $missingFields=array_diff($requiredFields,array_keys($data));
            if(count($missingFields)>0){
                $errMsg=sprintf('Отсутвуют обязательные элементы: %s',implode(',',$missingFields));
                throw new InvalidDataStructureException($errMsg);
            }
            return new ClassClass(
                $data['id'],
                $data['number'],
                $data['letter'],
            );
        }
    }