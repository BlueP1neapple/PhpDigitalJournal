<?php

    return[
        /**
         * Путь до файла c данными об Занятиях
         */
        'pathToLesson'=> __DIR__ . '/../../data/lesson.json',
        /**
         * Путь до файла c данными об Журналах
         */
        'pathToAssessmentReport'=> __DIR__ . '/../../data/assessmentReport.json',
        /**
         * Путь до файла c данными об Предметах
         */
        'pathToItems'=> __DIR__ . '/../../data/item.json',
        /**
         * Путь до файла c данными об Учителях
         */
        'pathToTeachers'=> __DIR__ . '/../../data/teacher.json',
        /**
         * Путь до файла c данными об Классах
         */
        'pathToClasses'=> __DIR__ . '/../../data/class.json',
        /**
         * Путь до файла c данными об Студентах
         */
        'pathToStudents'=> __DIR__ . '/../../data/student.json',
        /**
         * Путь до файла c данными об Родителях
         */
        'pathToParents'=> __DIR__ . '/../../data/parent.json',
        /**
         * Путь до файла куда пишем логи
         */
        'pathToLogFile' => __DIR__ . '/../../var/log/app.log',
        /**
         * Тип используемого логгера
         */
        'loggerType' => 'fileLogger',

        /**
         * Скрывать сообщения об ошибках
         */
        'hideErrorMessage'=> false,
    ];