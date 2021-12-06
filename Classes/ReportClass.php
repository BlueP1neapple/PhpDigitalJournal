<?php

class ReportClass implements JsonSerializable
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
     * @return int получить id оценки
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id установить id оценки
     */
    public function setId(int $id): ReportClass
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return LessonClass получить урок
     */
    public function getLesson(): LessonClass
    {
        return $this->lesson;
    }

    /**
     * @param LessonClass $lesson установить урок
     */
    public function setLesson(LessonClass $lesson): ReportClass
    {
        $this->lesson = $lesson;
        return $this;
    }

    /**
     * @return StudentUserClass получить студента
     */
    public function getStudent(): StudentUserClass
    {
        return $this->student;
    }

    /**
     * @param StudentUserClass $student установить студента
     */
    public function setStudent(StudentUserClass $student): ReportClass
    {
        $this->student = $student;
        return $this;
    }

    /**
     * @return int получить оценку
     */
    public function getMark(): int
    {
        return $this->mark;
    }

    /**
     * @param int $mark установить оценку
     */
    public function setMark(int $mark): ReportClass
    {
        $this->mark = $mark;
        return $this;
    }



    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'lesson' => $this->lesson,
            'student' => $this->lesson,
            'mark' => $this->lesson
        ];
    }
}