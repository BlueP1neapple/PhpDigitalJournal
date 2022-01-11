<?php
namespace JoJoBizzareCoders\DigitalJournal\Entity;
    use JoJoBizzareCoders\DigitalJournal\Exception\InvalidDataStructureException;
    use JoJoBizzareCoders\DigitalJournal\ValueObject\AdditionalInfo;
    use JsonSerializable;


    /**
     * Класс оценок
     */
    final class ReportClass implements JsonSerializable
    {
        //Свойства
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
         * @var AdditionalInfo Дополнительный коментарий к оценке
         */
        private AdditionalInfo $additional_info;

        //Методы
        /**
         * Конструкор класса оценок
         * @param int $id - id оценок
         * @param LessonClass $lesson - Занятие в котором поставили оценку
         * @param StudentUserClass $student - студент которому поставили оценку
         * @param int $mark - значение оценки
         */
        public function __construct(int $id, LessonClass $lesson, StudentUserClass $student, int $mark, AdditionalInfo $additional_info)
        {
            $this->id = $id;
            $this->lesson = $lesson;
            $this->student = $student;
            $this->mark = $mark;
            $this->additional_info = $additional_info;
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
                'mark' => $this->mark,
                'additional_info' => $this->additional_info
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
                'mark',
                'additional_info'
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
                $data['mark'],
                $data['additional_info']
            );
        }

        /**
         * @return int
         */
        public function getId(): int
        {
            return $this->id;
        }

        /**
         * @return LessonClass
         */
        public function getLesson(): LessonClass
        {
            return $this->lesson;
        }

        /**
         * @return StudentUserClass
         */
        public function getStudent(): StudentUserClass
        {
            return $this->student;
        }

        /**
         * @return int
         */
        public function getMark(): int
        {
            return $this->mark;
        }

        /**
         * @return AdditionalInfo
         */
        public function getAdditionalInfo(): AdditionalInfo
        {
            return $this->additional_info;
        }



    }