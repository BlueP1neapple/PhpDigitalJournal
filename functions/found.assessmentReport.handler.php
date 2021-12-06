<?php

    require_once "functions/application.php";

    require_once __DIR__ . "/../Classes/ItemClass.php";
    require_once __DIR__ . "/../Classes/LessonClass.php";
    require_once __DIR__ . "/../Classes/ClassClass.php";
    require_once __DIR__ . "/../Classes/ReportClass.php";
    require_once __DIR__ . "/../Classes/StudentUserClass.php";
    require_once __DIR__ . "/../Classes/TeacherUserClass.php";
    require_once __DIR__ . "/../Classes/ParentUserClass.php";
    /**
     * Поиск по оценке
     * @param array $request - массив содержащий параметры поиска
     * @param callable $logger - название функции логирования
     * @return array
     * @return array - результат поиска оценок
     */
    return static function (array $request, callable $logger): array {
        $items = loadData(__DIR__ . '/../JSON/item.json');
        $teachers = loadData(__DIR__ . '/../JSON/teacher.json');
        $classes = loadData(__DIR__ . '/../JSON/class.json');
        $lessons = loadData(__DIR__ . '/../JSON/lesson.json');
        $reports = loadData(__DIR__ . '/../JSON/assessmentReport.json');
        $students = loadData(__DIR__ . '/../JSON/student.json');
        $parents = loadData(__DIR__ . '/../JSON/parent.json');
        $logger('dispatch "assessmentReport" url');
        $paramValidations = [
            'item_name' => 'Incorrect item name',
            'item_description' => 'Incorrect item description',
            'lesson_date' => 'Incorrect lesson date',
            'student_fio' => 'Incorrect student fio',
        ];
        if (null === ($result = paramTypeValidation($paramValidations, $request))) {
            $foundReport = [];
            $itemsIdToInfo = [];
            $teachersIdToInfo = [];
            $classesIdToInfo = [];
            $lessonIdToInfo = [];
            $studentIdToInfo = [];
            $parentIdToInfo = [];


            foreach ($items as $item) {
                $itemsObj = new ItemClass();
                $itemsObj->setId($item['id']);
                $itemsObj->setName($item['name']);
                $itemsObj->setDescription($item['description']);
                $itemsIdToInfo[$item['id']] = $itemsObj;
            }

            foreach ($teachers as $teacher) {
                $teachersObj = new TeacherUserClass();
                $teachersObj->setId($teacher['id'])
                    ->setFio($teacher['fio'])
                    ->setPhone($teacher['phone'])
                    ->setAddress($teacher['address'])
                    ->setCabinet($teacher['cabinet'])
                    ->setEmail($teacher['email'])
                    ->setItem($itemsIdToInfo[$teacher['idItem']])
                    ->setDateOfBirth($teacher['dateOfBirth']);
                $teachersIdToInfo[$teacher['id']] = $teachersObj;
            }

            foreach ($classes as $class) {
                $classesObj = new ClassClass();
                $classesObj->setId($class['id']);
                $classesObj->setNumber($class['number']);
                $classesObj->setLetter($class['letter']);
                $classesIdToInfo[$class['id']] = $classesObj;
            }

            foreach ($lessons as $lesson) {
                $lessonsObj = new LessonClass();
                $lessonsObj->setId($lesson['id']);
                $lessonsObj->setTeacher($teachersIdToInfo[$lesson['teacher_id']]);
                $lessonsObj->setLessonDuration($lesson['lessonDuration']);
                $lessonsObj->setDate($lesson['date']);
                $lessonsObj->setItem($itemsIdToInfo[$lesson['item_id']]);
                $lessonsObj->setClass($classesIdToInfo[$lesson['class_id']]);
                $lessonIdToInfo[$lesson['id']] = $lessonsObj;
            }
            foreach ($parents as $parent) {
                $parentsObj = new ParentUserClass();
                $parentsObj->setId($parent['id']);
                $parentsObj->setFio($parent['fio']);
                $parentsObj->setDateOfBirth($parent['dateOfBirth']);
                $parentsObj->setPhone($parent['phone']);
                $parentsObj->setAddress($parent['address']);
                $parentsObj->setPlaceOfWork($parent['placeOfWork']);
                $parentsObj->setEmail($parent['email']);
                $parentsObj->setEmail($parent['email']);
                $parentIdToInfo[$parent['id']] = $parentsObj;
            }
            foreach ($students as $student) {
                $studentsObj = new StudentUserClass();
                $studentsObj->setId($student['id']);
                $studentsObj->setFio($student['fio']);
                $studentsObj->setDateOfBirth($student['dateOfBirth']);
                $studentsObj->setPhone($student['phone']);
                $studentsObj->setAddress($student['address']);
                $studentsObj->setClass($classesIdToInfo[$student['class_id']]);
                $studentsObj->setParent($parentIdToInfo[$student['parent_id']]);
                $studentIdToInfo[$student['id']] = $studentsObj;
            }


            foreach ($reports as $report) {
                $ReportMeetSearchCriteria = null;
                if (array_key_exists(
                    'item_name',
                    $request
                )) // Поиск по присутвию item_name в GET запросе и совпадению item_name в запросе и массиве оценок. [начало]
                {
                    $ReportMeetSearchCriteria = ($request['item_name'] === $itemsIdToInfo[$lessonIdToInfo[$report['lesson_id']]->getItem()->getId()]->getName());
                }// Поиск по присутвию item_name в GET запросе и совпадению item_name в запросе и массиве оценок. [конец]
                if (array_key_exists('item_description', $request)) {
                    $ReportMeetSearchCriteria = ($request['item_description'] === $itemsIdToInfo[$lessonIdToInfo[$report['lesson_id']]->getItem()->getId()]->getDescription());
                }
                if (array_key_exists('lesson_date', $request)) {
                    $ReportMeetSearchCriteria = ($request['lesson_date'] === $lessonIdToInfo[$report['lesson_id']]->getDate());
                }
                if (array_key_exists('student_fio', $request)) {
                    $ReportMeetSearchCriteria = ($request['student_fio'] === $studentIdToInfo[$report['student_id']]->getFio());
                }

                if ($ReportMeetSearchCriteria) {
                    $reportObj=new ReportClass();
                    $reportObj->setId($report['id'])
                    ->setLesson($lessonIdToInfo[$report['lesson_id']])
                    ->setStudent($studentIdToInfo[$report['student_id']])
                    ->setMark($report['mark']);
                    /*$report['student'] = $studentIdToInfo[$report['student_id']];
                    $report['lesson'] = $lessonIdToInfo[$report['lesson_id']];
                    $report['lesson']['item'] = $itemsIdToInfo[$report['lesson']['item_id']];
                    $report['lesson']['teacher'] = $teachersIdToInfo[$report['lesson']['teacher_id']];
                    $report['lesson']['class'] = $classesIdToInfo[$report['student']['class_id']];
                    $report['student']['parent'] = $parentIdToInfo[$report['student']['parent_id']];
                    unset($report['id'], $report['lesson_id'], $report['student_id']);*/
                    $foundReport[] = $reportObj;
                }
            }//Цикл по оценкам [конец]
            $logger('found Report' . count($foundReport));
            $result = [
                'httpCode' => 200,
                'result' => $foundReport
            ];
        }
        return $result;
    };