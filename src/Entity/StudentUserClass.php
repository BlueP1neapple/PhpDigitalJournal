<?php
namespace JoJoBizzareCoders\DigitalJournal\Entity;
    use DateTimeImmutable;
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
        private array $parents;


        /**
         * Конструктор класса студента
         * @inheritdoc
         * @param ClassClass $class - объект класса класса
         * @param ParentUserClass[] $parents - объект класса родетелец
         */
        public function __construct(
            int $id,
            array $fio,
            DateTimeImmutable $dateOfBirth,
            string $phone,
            array $address,
            ClassClass $class,
            array $parents,
            string $login,
            string $password
        ) {
            parent::__construct($id, $fio, $dateOfBirth, $phone, $address, $login, $password);
            $this->class = $class;
            $this->parents = $parents;
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
                'parents',
                'login',
                'password'
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
                $data['parents'],
                $data['login'],
                $data['password']
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
         * @return ParentUserClass[]
         */
        public function getParent(): array
        {
            return $this->parents;
        }


    }

