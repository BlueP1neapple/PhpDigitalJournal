<?php
namespace JoJoBizzareCoders\DigitalJournal\Infrastructure;
    use JoJoBizzareCoders\DigitalJournal\Exception;

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
        private string $pathToLesson= __DIR__ . '/../../data/lesson.json';

        /**
         * Путь до файла с данными об оценках
         *
         * @var string
         */
        private string $pathToAssessmentReport= __DIR__ . '/../../data/assessmentReport.json';

        /**
         * Путь до файла с данными об Предмете
         *
         * @var string
         */
        private string $pathToItems= __DIR__ . '/../../data/item.json';

        /**
         * Путь до файла с данными об Учителе
         *
         * @var string
         */
        private string $pathToTeachers= __DIR__ . '/../../data/teacher.json';

        /**
         * Путь до файла с данными об Классах
         *
         * @var string
         */
        private string $pathToClasses= __DIR__ . '/../../data/class.json';

        /**
         * Путь до файла с данными об Студентах
         *
         * @var string
         */
        private string $pathToStudents= __DIR__ . '/../../data/student.json';

        /**
         * Путь до файла с данными об Родителях
         *
         * @var string
         */
        private string $pathToParents= __DIR__ . '/../../data/parent.json';

        /**
         * Путь с файлами с логами
         *
         * @var string
         */
        private string $pathToLogFile;

        /**
         * Тип используемого логгера
         *
         * @var string
         */
        private string $loggerType;

        /**
         * Скрывать сообщения по ошибкам
         *
         * @var bool
         */
        private bool $hideErrorMessage;




        // Методы
        /**
         * Возвращает флаг указывающий, что нужно скрыватиь сообщения по ошибкам
         *
         * @return bool
         */
        public function isHideErrorMessage(): bool
        {
            return $this->hideErrorMessage;
        }

        /**
         * Устанавливает флаг указывающий, что нужно скрыватиь сообщения по ошибкам
         *
         * @param bool $hideErrorMessage
         */
        private function setHideErrorMessage(bool $hideErrorMessage): void
        {
            $this->hideErrorMessage = $hideErrorMessage;
        }

        /**
         * Возвращает тип используемого логгера
         *
         * @return string
         */
        public function getLoggerType(): string
        {
            return $this->loggerType;
        }

        /**
         * Устанавливает тип используемого логгера
         *
         * @param string $loggerType
         */
        private function setLoggerType(string $loggerType): void
        {
            $this->loggerType = $loggerType;
        }

        /**
         * Возвращает путь до файла с логерами
         *
         * @return string
         */
        public function getPathToLogFile(): string
        {
            return $this->pathToLogFile;
        }

        /**
         * Устанавливает путь до файла с логами
         *
         * @param string $pathToLogFile -  путь до файла с логами
         * @throws Exception\RuntimeException
         */
        private function setPathToLogFile(string $pathToLogFile): void
        {
            $this->validateFilePath($pathToLogFile);
            $this->pathToLogFile = $pathToLogFile;
        }

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
         * @throws Exception\RuntimeException
         */
        private function setPathToLesson(string $pathToLesson): AppConfig
        {
            $this->validateFilePath($pathToLesson);
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
         * @throws Exception\RuntimeException
         */
        private function setPathToAssessmentReport(string $pathToAssessmentReport): AppConfig
        {
            $this->validateFilePath($pathToAssessmentReport);
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
         * @throws Exception\RuntimeException
         */
        private function setPathToItems(string $pathToItems): AppConfig
        {
            $this->validateFilePath($pathToItems);
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
         * @throws Exception\RuntimeException
         */
        private function setPathToTeachers(string $pathToTeachers): AppConfig
        {
            $this->validateFilePath($pathToTeachers);
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
         * @throws Exception\RuntimeException
         */
        private function setPathToClasses(string $pathToClasses): AppConfig
        {
            $this->validateFilePath($pathToClasses);
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
         * @throws Exception\RuntimeException
         */
        private function setPathToStudents(string $pathToStudents): AppConfig
        {
            $this->validateFilePath($pathToStudents);
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
         * @throws Exception\RuntimeException
         */
        private function setPathToParents(string $pathToParents): AppConfig
        {
            $this->validateFilePath($pathToParents);
            $this->pathToParents = $pathToParents;
            return $this;
        }


        /**
         * Валидация пути до файла
         *
         * @param string $path
         *
         * @return void
         * @throws Exception\RuntimeException
         */
        private function validateFilePath(string $path):void
        {
            if(false===file_exists($path)){
                throw new Exception\RuntimeException('Неккоректный путь до файла с данными');
            }
        }

        /**
         * Создаёт конфиг приложения из массива
         *
         * @param array $config
         * @return static
         * @uses AppConfig::setPathToParents()
         * @uses AppConfig::setPathToStudents()
         * @uses AppConfig::setPathToClasses()
         * @uses AppConfig::setPathToTeachers()
         * @uses AppConfig::setPathToItems()
         * @uses AppConfig::setPathToAssessmentReport()
         * @uses AppConfig::setPathToLesson()
         * @uses AppConfig::setPathToLogFile()
         * @uses AppConfig::setLoggerType()
         * @uses AppConfig::setHideErrorMessage()
         */
        public static function createFromArray(array $config): self
        {
            $appConfig=new self();
            foreach ($config as $key=>$value){
                if(property_exists($appConfig,$key)){
                    $setter='set'.ucfirst($key);
                    $appConfig->{$setter}($value);
                }
            }
            return $appConfig;
        }

    }