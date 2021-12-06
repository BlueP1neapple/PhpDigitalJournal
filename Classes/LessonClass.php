<?php

class LessonClass implements JsonSerializable
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
     * @return int Получить id урока
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id установить id урока
     */
    public function setId(int $id): LessonClass
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return ItemClass получить предмет
     */
    public function getItem(): ItemClass
    {
        return $this->item;
    }

    /**
     * @param ItemClass $item установить предмет
     */
    public function setItem(ItemClass $item): LessonClass
    {
        $this->item = $item;
        return $this;
    }

    /**
     * @return string получить дату проведения урока
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @param string $date установить дату проведения урока
     */
    public function setDate(string $date): LessonClass
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return int получить длительность урока
     */
    public function getLessonDuration(): int
    {
        return $this->lessonDuration;
    }

    /**
     * @param int $lessonDuration установить длительность урока
     */
    public function setLessonDuration(int $lessonDuration): LessonClass
    {
        $this->lessonDuration = $lessonDuration;
        return $this;
    }

    /**
     * @return TeacherUserClass получить преподавателя
     */
    public function getTeacher(): TeacherUserClass
    {
        return $this->teacher;
    }

    /**
     * @param TeacherUserClass $teacher установить преподавателя
     */
    public function setTeacher(TeacherUserClass $teacher): LessonClass
    {
        $this->teacher = $teacher;
        return $this;
    }

    /**
     * @return ClassClass получить класс
     */
    public function getClass(): ClassClass
    {
        return $this->class;
    }

    /**
     * @param ClassClass $class установить класс
     */
    public function setClass(ClassClass $class): LessonClass
    {
        $this->class = $class;
        return $this;
    }



    public function jsonSerialize()
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
}