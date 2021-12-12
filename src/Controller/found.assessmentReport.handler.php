<?php

    require_once __DIR__ . '/../Infrastructure/application.php';
    require_once __DIR__ . '/../Infrastructure/antiIf.php';
    require_once __DIR__ . "/../Entity/ItemClass.php";
    require_once __DIR__ . "/../Entity/LessonClass.php";
    require_once __DIR__ . "/../Entity/ClassClass.php";
    require_once __DIR__ . "/../Entity/ReportClass.php";
    require_once __DIR__ . "/../Entity/StudentUserClass.php";
    require_once __DIR__ . "/../Entity/TeacherUserClass.php";
    require_once __DIR__ . "/../Entity/ParentUserClass.php";
    require_once __DIR__ . '/../../src/Infrastructure/AppConfig.php';
    /**
     * Поиск по оценке
     * @param array $request - массив содержащий параметры поиска
     * @param LoggerInterface $logger - название функции логирования
     * @param AppConfig $appConfig - конфигурация приложения
     * @return array - результат поиска оценок
     */
    return static function (array $request, LoggerInterface $logger, AppConfig $appConfig): array {
        $items = loadData($appConfig->getPathToItems());
        $teachers = loadData($appConfig->getPathToTeachers());
        $classes = loadData($appConfig->getPathToClasses());
        $lessons = loadData($appConfig->getPathToLesson());
        $reports = loadData($appConfig->getPathToAssessmentReport());
        $students = loadData($appConfig->getPathToStudents());
        $parents = loadData($appConfig->getPathToParents());
        $logger->log('assessmentReport" url');
        $paramValidations = [
            'item_name' => 'Incorrect item name',
            'item_description' => 'Incorrect item description',
            'lesson_date' => 'Incorrect lesson date',
            'student_fio' => 'Incorrect student fio',
        ];
        if (null === ($result = paramTypeValidation($paramValidations, $request))) {
            //Хэшмапирование
            $foundReport = [];
            $itemsIdToInfo = [];
            $teachersIdToInfo = [];
            $classesIdToInfo = [];
            $lessonIdToInfo = [];
            $studentIdToInfo = [];
            $parentIdToInfo = [];

            foreach ($items as $item) {
                $itemsObj = ItemClass::createFromArray($item);
                $itemsIdToInfo[$itemsObj->getId()] = $itemsObj;
            }

            foreach ($teachers as $teacher) {
                $teacher['idItem'] = $itemsIdToInfo[$teacher['idItem']];
                $teachersObj = TeacherUserClass::createFromArray($teacher);
                $teachersIdToInfo[$teachersObj->getId()] = $teachersObj;
            }

            foreach ($classes as $class) {
                $classesObj = ClassClass::createFromArray($class);
                $classesIdToInfo[$classesObj->getId()] = $classesObj;
            }

            foreach ($lessons as $lesson) {
                $lesson['item_id'] = $itemsIdToInfo[$lesson['item_id']];
                $lesson['teacher_id'] = $teachersIdToInfo[$lesson['teacher_id']];
                $lesson['class_id'] = $classesIdToInfo[$lesson['class_id']];
                $lessonsObj = LessonClass::createFromArray($lesson);
                $lessonIdToInfo[$lessonsObj->getId()] = $lessonsObj;
            }

            foreach ($parents as $parent) {
                $parentsObj = ParentUserClass::createFromArray($parent);
                $parentIdToInfo[$parentsObj->getId()] = $parentsObj;
            }

            foreach ($students as $student) {
                $student['class_id'] = $classesIdToInfo[$student['class_id']];
                $student['parent_id'] = $parentIdToInfo[$student['parent_id']];
                $studentsObj = StudentUserClass::createFromArray($student);
                $studentIdToInfo[$studentsObj->getId()] = $studentsObj;
            }
            // Поиск оценок
            foreach ($reports as $report) {
                $ReportMeetSearchCriteria = getSearch($request, $report, $appConfig);
                if ($ReportMeetSearchCriteria) { // Отбор наёденных оценок
                    $report['lesson_id'] = $lessonIdToInfo[$report['lesson_id']];
                    $report['student_id'] = $studentIdToInfo[$report['student_id']];
                    $foundReport[] = ReportClass::createFromArray($report);
                }
            }//Цикл по оценкам [конец]
            $logger->log('found Report'.count($foundReport));
            $result = [
                'httpCode' => 200,
                'result' => $foundReport
            ];
        }
        return $result;
    };