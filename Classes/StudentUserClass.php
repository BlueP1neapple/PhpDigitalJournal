<?php
    require_once __DIR__.'/../Classes/InvalidDataStructureException.php';
    require_once __DIR__ . "/AbstractUserClass.php";

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
            string $fio,
            string $dateOfBirth,
            string $phone,
            string $address,
            ClassClass $class,
            ParentUserClass $parent
        ) {
            parent::__construct($id, $fio, $dateOfBirth, $phone, $address);
            $this->class = $class;
            $this->parent = $parent;
        }

        /**
         * Метод получения массива для кодирование в json
         * @return array - массив для кодирования
         */
        public function jsonSerialize(): array
        {
            $jsonData = parent::jsonSerialize();
            $jsonData['class'] = $this->class;
            $jsonData['parent'] = $this->parent;
            return $jsonData;
        }

        /**
         * Метод создания объекта класса студент из тмассива данных об студенте
         * @param array $data -массив данных об студенте
         * @return StudentUserClass - объект класса студент
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

        //Неиспользуемые методы
        //    /**
//     * @return ClassClass получить класс ученика
//     */
//    public function getClass(): ClassClass
//    {
//        return $this->class;
//    }


//    /**
//     * @return ParentUserClass получить родителя ученика
//     */
//    public function getParent(): ParentUserClass
//    {
//        return $this->parent;
//    }
    }