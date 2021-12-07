<?php

    /**
     * Конфиг приложения
     */
    class AppConfig
    {
        // Свойства
        /**
         * Путь до файла с данными об занятиях
         *
         * @var string
         */
        private string $pathToLesson=__DIR__ . '/../JSON/lesson.json';

        /**
         * Путь до файла с данными об оценках
         *
         * @var string
         */
        private string $pathToAssessmentReport=__DIR__  .'/../JSON/assessmentReport.json';

        /**
         * Путь до файла с данными об Предмете
         *
         * @var string
         */
        private string $pathToItems=__DIR__ . '/../JSON/item.json';

        /**
         * Путь до файла с данными об Учителе
         *
         * @var string
         */
        private string $pathToTeachers=__DIR__ . '/../JSON/teacher.json';

        /**
         * Путь до файла с данными об Классах
         *
         * @var string
         */
        private string $pathToClasses=__DIR__ . '/../JSON/class.json';

        /**
         * Путь до файла с данными об Студентах
         *
         * @var string
         */
        private string $pathToStudents=__DIR__ . '/../JSON/student.json';

        /**
         * Путь до файла с данными об Родителях
         *
         * @var string
         */
        private string $pathToParents=__DIR__ . '/../JSON/parent.json';



        // Методы
        /**
         * Возвращаю путь до файла с данными об Занятиях
         *
         * @return string - путь до файла с данными об Занятиях
         */
        public function getPathToLesson(): string
        {
            return $this->pathToLesson;
        }

        /**
         * Устанавливаю путь до файла с данными об Занятиях
         *
         * @param string $pathToLesson - путь до файла с данными об Занятиях
         * @return AppConfig - объект с путём до файла с данными об Занятиях
         */
        public function setPathToLesson(string $pathToLesson): AppConfig
        {
            $this->pathToLesson = $pathToLesson;
            return $this;
        }

        /**
         * Возвращаю путь до файла с данными об Оценках
         *
         * @return string - путь до файла с данными об Оценках
         */
        public function getPathToAssessmentReport(): string
        {
            return $this->pathToAssessmentReport;
        }

        /**
         * Устанавливаю путь до файла с данными об Оценках
         *
         * @param string $pathToAssessmentReport - путь до файла с данными об Оценках
         * @return AppConfig - объект с путём до файла с данными об Оценках
         */
        public function setPathToAssessmentReport(string $pathToAssessmentReport): AppConfig
        {
            $this->pathToAssessmentReport = $pathToAssessmentReport;
            return $this;
        }

        /**
         * Возвращаю путь до файла с данными об Предмете
         *
         * @return string - путь до файла с данными об Предмете
         */
        public function getPathToItems(): string
        {
            return $this->pathToItems;
        }

        /**
         * Устанавливаю путь до файла с данными об Предмете
         *
         * @param string $pathToItems - путь до файла с данными об Предмете
         * @return AppConfig - объект с путём до файла с данными об Предмете
         */
        public function setPathToItems(string $pathToItems): AppConfig
        {
            $this->pathToItems = $pathToItems;
            return $this;
        }

        /**
         * Возвращаю путь до файла с данными об Учителе
         *
         * @return string - путь до файла с данными об Учителе
         */
        public function getPathToTeachers(): string
        {
            return $this->pathToTeachers;
        }

        /**
         * Устанавливаю путь до файла с данными об Учителе
         *
         * @param string $pathToTeachers - путь до файла с данными об Учителе
         * @return AppConfig - объект с путём до файла с данными об Учителе
         */
        public function setPathToTeachers(string $pathToTeachers): AppConfig
        {
            $this->pathToTeachers = $pathToTeachers;
            return $this;
        }

        /**
         * Возвращаю путь до файла с данными об Классах
         *
         * @return string - путь до файла с данными об Классах
         */
        public function getPathToClasses(): string
        {
            return $this->pathToClasses;
        }

        /**
         * Устанавливаю путь до файла с данными об Классах
         *
         * @param string $pathToClasses - путь до файла с данными об Классах
         * @return AppConfig - объект с путём до файла с данными об Классах
         */
        public function setPathToClasses(string $pathToClasses): AppConfig
        {
            $this->pathToClasses = $pathToClasses;
            return $this;
        }

        /**
         * Возвращаю путь до файла с данными об Студентах
         *
         * @return string - путь до файла с данными об Студентах
         */
        public function getPathToStudents(): string
        {
            return $this->pathToStudents;
        }

        /**
         * Устанавливаю путь до файла с данными об Студентах
         *
         * @param string $pathToStudents - путь до файла с данными об Студентах
         * @return AppConfig - объект с путём до файла с данными об Студентах
         */
        public function setPathToStudents(string $pathToStudents): AppConfig
        {
            $this->pathToStudents = $pathToStudents;
            return $this;
        }

        /**
         * Возвращаю путь до файла с данными об Родителях
         *
         * @return string - путь до файла с данными об Родителях
         */
        public function getPathToParents(): string
        {
            return $this->pathToParents;
        }

        /**
         * Устанавливаю путь до файла с данными об Родителях
         *
         * @param string $pathToParents - путь до файла с данными об Родителях
         * @return AppConfig - объект с путём до файла с данными об Родителях
         */
        public function setPathToParents(string $pathToParents): AppConfig
        {
            $this->pathToParents = $pathToParents;
            return $this;
        }

    }