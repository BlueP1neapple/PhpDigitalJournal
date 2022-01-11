<?php
namespace JoJoBizzareCoders\DigitalJournal\Entity;
    use JoJoBizzareCoders\DigitalJournal\Exception\InvalidDataStructureException;


    /**
     * Класс Учителя
     */
    final class TeacherUserClass extends AbstractUserClass
    {
        //Свойства
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




        //Методы

        /**
         * Конструктор класса учителя
         * @inheritdoc
         * @param ItemClass $item - Предмет который ведёт учитель
         * @param int $cabinet - Кабинет учителя
         * @param string $email - email учителя
         */
        public function __construct(
            int $id,
            string $fio,
            string $dateOfBirth,
            string $phone,
            string $address,
            ItemClass $item,
            int $cabinet,
            string $email
        ) {
            parent::__construct($id, $fio, $dateOfBirth, $phone, $address);
            $this->item = $item;
            $this->cabinet = $cabinet;
            $this->email = $email;
        }

        /**
         * @return int Получить номер кабинета учителя
         */
        public function getCabinet(): int
        {
            return $this->cabinet;
        }

        /**
         * Получить предмет учителя
         * @return ItemClass
         */
        public function getItem(): ItemClass
        {
            return $this->item;
        }

        /**
         * @return string
         */
        public function getEmail(): string
        {
            return $this->email;
        }


        /**
         * Метод получения массива для кодирование в json
         * @return array - массив для кодирования
         */
        public function jsonSerialize(): array
        {
            $jsonData = parent::jsonSerialize();
            $jsonData['item'] = $this->item;
            $jsonData['cabinet'] = $this->cabinet;
            $jsonData['email'] = $this->email;


            return $jsonData;
        }

        /**
         * Метод создания объекта класса Учитель из массива данных об Учителе
         * @param array $data -массив данных об Учителе
         * @return TeacherUserClass - Объект класса Учитель
         * @throws InvalidDataStructureException
         */
        public static function createFromArray(array $data): TeacherUserClass
        {
            $requiredFields=[
                'id',
                'fio',
                'dateOfBirth',
                'phone',
                'address',
                'idItem',
                'cabinet',
                'email'
            ];
            $missingFields=array_diff($requiredFields,array_keys($data));
            if(count($missingFields)>0){
                $errMsg=sprintf('Отсутвуют обязательные элементы: %s',implode(',',$missingFields));
                throw new InvalidDataStructureException($errMsg);
            }
            return new TeacherUserClass(
                $data['id'],
                $data['fio'],
                $data['dateOfBirth'],
                $data['phone'],
                $data['address'],
                $data['idItem'],
                $data['cabinet'],
                $data['email']
            );
        }
    }