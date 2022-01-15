<?php
namespace JoJoBizzareCoders\DigitalJournal\Entity;
    use JoJoBizzareCoders\DigitalJournal\Exception\InvalidDataStructureException;

    /**
     * Класс Студента
     */
    final class StudentUserClass extends AbstractUserClass
    {
        /**
         *  класс ученика
         */
        private ClassClass $class;

        /**
         * Родитель ученика
         */
        private ParentUserClass $parent;


        /**
         * Конструктор класса студента
         * @inheritdoc
         * @param ClassClass $class - объект класса класса
         * @param ParentUserClass $parent - объект класса родетелец
         */
        public function __construct(
            int $id,
            array $fio,
            string $dateOfBirth,
            string $phone,
            array $address,
            ClassClass $class,
            ParentUserClass $parent
        ) {
            parent::__construct($id, $fio, $dateOfBirth, $phone, $address);
            $this->class = $class;
            $this->parent = $parent;
        }

        /**
         * Метод создания объекта класса студент из тмассива данных об студенте
         * @param array $data -массив данных об студенте
         * @return StudentUserClass - объект класса студент
         * @throws InvalidDataStructureException
         */
        public static function createFromArray(array $data): StudentUserClass
        {
            $requiredFields=[
                'id',
                'fio',
                'dateOfBirth',
                'phone',
                'address',
                'class_id',
                'parent_id'
            ];
            $missingFields=array_diff($requiredFields,array_keys($data));
            if(count($missingFields)>0){
                $errMsg=sprintf('Отсутвуют обязательные элементы: %s',implode(',',$missingFields));
                throw new InvalidDataStructureException($errMsg);
            }
            return new StudentUserClass(
                $data['id'],
                $data['fio'],
                $data['dateOfBirth'],
                $data['phone'],
                $data['address'],
                $data['class_id'],
                $data['parent_id']
            );
        }

        /**
         * Получить в каком классе ученик
         * @return ClassClass
         */
        public function getClass(): ClassClass
        {
            return $this->class;
        }

        /**
         * Получить Родителя
         * @return ParentUserClass
         */
        public function getParent(): ParentUserClass
        {
            return $this->parent;
        }


    }

