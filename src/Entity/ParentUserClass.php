<?php

namespace JoJoBizzareCoders\DigitalJournal\Entity;
    use JoJoBizzareCoders\DigitalJournal\Exception\InvalidDataStructureException;


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

        //Методы
        /**
         * Конструктор класса Родетелей
         * @inheritdoc
         * @param string $placeOfWork - место работы родителей
         * @param string $email - email родителей
         */
        public function __construct(
            int $id,
            array $fio,
            string $dateOfBirth,
            string $phone,
            array $address,
            string $placeOfWork,
            string $email
        ) {
            parent::__construct($id, $fio, $dateOfBirth, $phone, $address);
            $this->placeOfWork = $placeOfWork;
            $this->email = $email;
        }

        /**
         * Метод создания объекта класса Родителей из массива данных об Родетелях
         * @inheritdoc
         * @param array $data - Массив данных данных об родителях
         * @return ParentUserClass - Объект класса Родителей
         * @throws InvalidDataStructureException
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

        /**
         * @return string
         */
        public function getPlaceOfWork(): string
        {
            return $this->placeOfWork;
        }

        /**
         * @return string
         */
        public function getEmail(): string
        {
            return $this->email;
        }



    }