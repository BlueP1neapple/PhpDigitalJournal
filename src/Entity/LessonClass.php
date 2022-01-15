<?php
namespace JoJoBizzareCoders\DigitalJournal\Entity;
    use JoJoBizzareCoders\DigitalJournal\Exception\InvalidDataStructureException;



    /**
     *Класс занятий
     */
    final class LessonClass
    {

        /**
         * @var int id урока
         */
        private int $id;

        /**
         * @var ItemClass Предмет
         */
        private ItemClass $item;

        /**
         * @var string Дата проведения урока
         */
        private string $date;

        /**
         * @var int Длительность урока
         */
        private int $lessonDuration;

        /**
         * @var TeacherUserClass Преподаватель
         */
        private TeacherUserClass $teacher;

        /**
         * @var ClassClass Класс
         */
        private ClassClass $class;


        /**
         * Конструктор класса занятий
         * @param int $id - id занятия
         * @param ItemClass $item - Предмет занятия
         * @param string $date - дата проведения занятия
         * @param int $lessonDuration - Продолжительность проведения занятия
         * @param TeacherUserClass $teacher - Преподователь проводящий занятие
         * @param ClassClass $class -Класс в котором проводиться занятие
         */
        public function __construct(
            int $id,
            ItemClass $item,
            string $date,
            int $lessonDuration,
            TeacherUserClass $teacher,
            ClassClass $class
        ) {
            $this->id = $id;
            $this->item = $item;
            $this->date = $date;
            $this->lessonDuration = $lessonDuration;
            $this->teacher = $teacher;
            $this->class = $class;
        }


        /**
         * @return int Получить id урока
         */
        public function getId(): int
        {
            return $this->id;
        }


        /**
         * @return ItemClass получить предмет
         */
        public function getItem(): ItemClass
        {
            return $this->item;
        }


        /**
         * @return string получить дату проведения урока
         */
        public function getDate(): string
        {
            return $this->date;
        }

        /**
         * Метод создания объекта класса занятия из массива данных об занятии
         * @param array $data - массив данных об занятии
         * @return LessonClass - объект класса занятий
         * @throws InvalidDataStructureException
         */
        public static function createFromArray(array $data): LessonClass
        {
            $requiredFields=[
              'id',
              'item_id',
              'date',
              'lessonDuration',
              'teacher_id',
              'class_id',
            ];
            $missingFields=array_diff($requiredFields,array_keys($data));
            if(count($missingFields)>0){
                $errMsg=sprintf('Отсутвуют обязательные элементы: %s',implode(',',$missingFields));
                throw new InvalidDataStructureException($errMsg);
            }
            return new LessonClass(
                $data['id'],
                $data['item_id'],
                $data['date'],
                $data['lessonDuration'],
                $data['teacher_id'],
                $data['class_id'],
            );
        }

        /**
         * Возвращает время проведения занятий
         *
         * @return int
         */
        public function getLessonDuration(): int
        {
            return $this->lessonDuration;
        }

        /**
         * Возвращает информацию о преподователе
         *
         * @return TeacherUserClass
         */
        public function getTeacher(): TeacherUserClass
        {
            return $this->teacher;
        }

        /**
         * Возвращает информацию о классе
         *
         * @return ClassClass
         */
        public function getClass(): ClassClass
        {
            return $this->class;
        }
    }