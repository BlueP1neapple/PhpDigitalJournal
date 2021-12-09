<?php


    require_once __DIR__ . "/AbstractUserClass.php";
    require_once __DIR__ . '/../Infrastructure/InvalidDataStructureException.php';
    /**
     * Класс Родителей
     */
    final class ParentUserClass extends AbstractUserClass
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
         * Конструктор класса Родетелей
         * @inheritdoc
         * @param string $placeOfWork - место работы родителей
         * @param string $email - email родителей
         */
        public function __construct(
            int $id,
            string $fio,
            string $dateOfBirth,
            string $phone,
            string $address,
            string $placeOfWork,
            string $email
        ) {
            parent::__construct($id, $fio, $dateOfBirth, $phone, $address);
            $this->placeOfWork = $placeOfWork;
            $this->email = $email;
        }


        /**
         * Метод получения массива для кодирование в json
         * @return array - массив для кодирования
         */
        public function jsonSerialize(): array
        {
            $jsonData = parent::jsonSerialize();
            $jsonData['placeOfWork'] = $this->placeOfWork;
            $jsonData['email'] = $this->email;
            return $jsonData;
        }

        /**
         * Метод создания объекта класса Родителей из массива данных об Родетелях
         * @inheritdoc
         * @param array $data - Массив данных данных об родителях
         * @return ParentUserClass - Объект класса Родителей
         */
        public static function createFromArray(array $data): ParentUserClass
        {
            $requiredFields=[
                'id',
                'fio',
                'dateOfBirth',
                'phone',
                'address',
                'placeOfWork',
                'email'
            ];
            $missingFields=array_diff($requiredFields,array_keys($data));
            if(count($missingFields)>0){
                $errMsg=sprintf('Отсутвуют обязательные элементы: %s',implode(',',$missingFields));
                throw new InvalidDataStructureException($errMsg);
            }
            return new ParentUserClass(
                $data['id'],
                $data['fio'],
                $data['dateOfBirth'],
                $data['phone'],
                $data['address'],
                $data['placeOfWork'],
                $data['email']
            );
        }

        //    /**
//     * @return string Получить Место работы родителя
//     */
//    public function getPlaceOfWork(): string
//    {
//        return $this->placeOfWork;
//    }


//    /**
//     * @return string получить email родителя
//     */
//    public function getEmail(): string
//    {
//        return $this->email;
//    }
    }