<?php
namespace JoJoBizzareCoders\DigitalJournal\Entity;
    use JoJoBizzareCoders\DigitalJournal\Exception\InvalidDataStructureException;
    use JsonSerializable;


    /**
     * Класс оценок
     */
    final class ReportClass implements JsonSerializable
    {
        /**
         * @var int id оценки
         */
        private int $id;

        /**
         * @var LessonClass Урок на котором получена оценка
         */
        private LessonClass $lesson;

        /**
         * @var StudentUserClass Ученик
         */
        private StudentUserClass $student;

        /**
         * @var int Оценка
         */
        private int $mark;

        /**
         * Конструкор класса оценок
         * @param int $id - id оценок
         * @param LessonClass $lesson - Занятие в котором поставили оценку
         * @param StudentUserClass $student - студент которому поставили оценку
         * @param int $mark - значение оценки
         */
        public function __construct(int $id, LessonClass $lesson, StudentUserClass $student, int $mark)
        {
            $this->id = $id;
            $this->lesson = $lesson;
            $this->student = $student;
            $this->mark = $mark;
        }

        /**
         * Метод получения массива для кодирование в json
         * @return array - массив для кодирования
         */
        public function jsonSerialize(): array
        {
            return [
                'id' => $this->id,
                'lesson' => $this->lesson,
                'student' => $this->student,
                'mark' => $this->mark
            ];
        }

        /**
         * Метод создания объекта класса Оценок из массива данных об оценках
         * @param array $data - массив данных об оценках
         * @return ReportClass - объект класса оценок
         * @throws InvalidDataStructureException
         */
        public static function createFromArray(array $data): ReportClass
        {
            $requiredFields=[
                'id',
                'lesson_id',
                'student_id',
                'mark'
            ];
            $missingFields=array_diff($requiredFields,array_keys($data));
            if(count($missingFields)>0){
                $errMsg=sprintf('Отсутвуют обязательные элементы: %s',implode(',',$missingFields));
                throw new InvalidDataStructureException($errMsg);
            }
            return new ReportClass(
                $data['id'],
                $data['lesson_id'],
                $data['student_id'],
                $data['mark']
            );
        }

        // Неиспользуемые методы
        //    /**
//     * @return int получить id оценки
//     */
//    public function getId(): int
//    {
//        return $this->id;
//    }


//    /**
//     * @return LessonClass получить урок
//     */
//    public function getLesson(): LessonClass
//    {
//        return $this->lesson;
//    }


//    /**
//     * @return StudentUserClass получить студента
//     */
//    public function getStudent(): StudentUserClass
//    {
//        return $this->student;
//    }


//    /**
//     * @return int получить оценку
//     */
//    public function getMark(): int
//    {
//        return $this->mark;
//    }
    }