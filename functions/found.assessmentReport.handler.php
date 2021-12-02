<?php
    require_once "functions/application.php";
    /**
     * Поиск по оценке
     * @return array
     * @param array $request - массив содержащий параметры поиска
     * @param callable $logger - название функции логирования
     * @return array - результат поиска оценок
     */
    return static function (array $request, callable $logger): array{
        $items = loadData(__DIR__ . '/../JSON/item.json');
        $teachers = loadData(__DIR__ . '/../JSON/teacher.json');
        $classes = loadData(__DIR__ . '/../JSON/class.json');
        $lessons = loadData(__DIR__ . '/../JSON/lesson.json');
        $reports = loadData( __DIR__ . '/../JSON/assessmentReport.json');
        $students = loadData(__DIR__ . '/../JSON/student.json');
        $parents = loadData(__DIR__ .'/../JSON/parent.json');
        $logger('dispatch "assessmentReport" url');
        $paramValidations=[
            'item_name'=>'Incorrect item name',
            'item_description'=>'Incorrect item description',
            'lesson_date'=>'Incorrect lesson date',
            'student_fio'=>'Incorrect student fio',
        ];
        if(null===($result=paramTypeValidation($paramValidations,$request)))
        {
            $itemsIdToInfo=[];
            $teachersIdToInfo=[];
            $classesIdToInfo=[];
            $lessonIdToInfo=[];
            $studentIdToInfo=[];
            $parentIdToInfo=[];
            $foundReport=[];
            foreach ($items as $Item)// Делаем ключ id по предмету
            {
                $itemsIdToInfo[$Item['id']] = $Item;
            } // Сделали ключ id по предмету
            foreach ($teachers as $Teacher)// Делаем ключ id по преподавателю
            {
                $teachersIdToInfo[$Teacher['id']] = $Teacher;
            } // Сделали ключ id по преподавателю
            foreach ($classes as $Class)// Делаем ключ id по классам
            {
                $classesIdToInfo[$Class['id']] = $Class;
            } // Сделали ключ id по классам
            foreach ($lessons as $lesson) {
                $lessonIdToInfo[$lesson['id']] = $lesson;
            } // Ключи id по урокам
            foreach ($students as $student) {
                $studentIdToInfo[$student['id']] = $student;
            }
            foreach ($parents as $parent) {
                $parentIdToInfo[$parent['id']] = $parent;
            }

            foreach ($reports as $report) {
                $ReportMeetSearchCriteria=null;
                if (array_key_exists(
                    'item_name',
                    $request
                )) // Поиск по присутвию item_name в GET запросе и совпадению item_name в запросе и массиве оценок. [начало]
                {
                    $ReportMeetSearchCriteria = ($request['item_name'] === $itemsIdToInfo [$lessonIdToInfo [$report['lesson_id']]['item_id']]['name']);
                }// Поиск по присутвию item_name в GET запросе и совпадению item_name в запросе и массиве оценок. [конец]
                if (array_key_exists('item_description', $request)) {
                    $ReportMeetSearchCriteria = ($request['item_description'] === $itemsIdToInfo[$lessonIdToInfo[$report['lesson_id']]['item_id']]['description']);
                }
                if (array_key_exists('lesson_date', $request)) {
                    $ReportMeetSearchCriteria = ($request['lesson_date'] === $lessonIdToInfo[$report['lesson_id']]['date']);
                }
                if (array_key_exists('student_fio', $request)) {
                    $ReportMeetSearchCriteria = ($request['student_fio'] === $studentIdToInfo[$report['student_id']]['fio']);
                }

                if ($ReportMeetSearchCriteria) {
                    $report['student'] = $studentIdToInfo[$report['student_id']];
                    $report['lesson'] = $lessonIdToInfo[$report['lesson_id']];
                    $report['lesson']['item'] = $itemsIdToInfo[$report['lesson']['item_id']];
                    $report['lesson']['teacher'] = $teachersIdToInfo[$report['lesson']['teacher_id']];
                    $report['lesson']['class'] = $classesIdToInfo[$report['student']['class_id']];

                    $report['student']['parent'] = $parentIdToInfo[$report['student']['parent_id']];
                    unset($report['id'], $report['lesson_id'], $report['student_id']);
                    $foundReport[] = $report;
                }
            }//Цикл по оценкам [конец]
            $logger('found Report'.count($foundReport));
            $result=[
                'httpCode'=>200,
                'result'=>$foundReport
            ];
        }
        return $result;
    };