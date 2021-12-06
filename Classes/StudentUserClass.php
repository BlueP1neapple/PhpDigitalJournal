<?php

require_once __DIR__ . "/AbstractUserClass.php";

class StudentUserClass extends AbstractUserClass
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
     * @return ClassClass получить класс ученика
     */
    public function getClass(): ClassClass
    {
        return $this->class;
    }

    /**
     * @param ClassClass $class установить класс ученика
     */
    public function setClass(ClassClass $class): void
    {
        $this->class = $class;
    }

    /**
     * @return ParentUserClass получить родителя ученика
     */
    public function getParent(): ParentUserClass
    {
        return $this->parent;
    }

    /**
     * @param ParentUserClass $parent установить родителя ученика
     */
    public function setParent(ParentUserClass $parent): void
    {
        $this->parent = $parent;
    }



    public function jsonSerialize()
    {
        $jsonData = parent::jsonSerialize();
        $jsonData['class'] = $this->class;
        $jsonData['parent'] = $this->parent;
        return $jsonData;
    }


}