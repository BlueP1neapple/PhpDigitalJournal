<?php

    /**
     *Класс занятий
     */
    final class LessonClass implements JsonSerializable
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
         * Метод получения массива для кодирование в json
         * @return array - массив для кодирования
         */
        public function jsonSerialize(): array
        {
            return [
                'id' => $this->id,
                'item' => $this->item,
                'date' => $this->date,
                'lessonDuration' => $this->lessonDuration,
                'teacher' => $this->teacher,
                'class' => $this->class
            ];
        }

        /**
         * Метод создания объекта класса занятия из массива данных об занятии
         * @param array $data - массив данных об занятии
         * @return LessonClass - объект класса занятий
         */
        public static function createFromArray(array $data): LessonClass
        {
            return new LessonClass(
                $data['id'],
                $data['item_id'],
                $data['date'],
                $data['lessonDuration'],
                $data['teacher_id'],
                $data['class_id'],
            );
        }

        // Неиспользуемые Методы
        //    /**
//     * @return int получить длительность урока
//     */
//    public function getLessonDuration(): int
//    {
//        return $this->lessonDuration;
//    }


//    /**
//     * @return TeacherUserClass получить преподавателя
//     */
//    public function getTeacher(): TeacherUserClass
//    {
//        return $this->teacher;
//    }


//    /**
//     * @return ClassClass получить класс
//     */
//    public function getClass(): ClassClass
//    {
//        return $this->class;
//    }
    }