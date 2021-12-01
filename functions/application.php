<?php



/**
 * Логика основного приложения
 *
 * @param string $requestUri - Переменная содержащая полный путь запроса
 * @param array $request - массив содержащий параметры поиска
 * @param callable $logger - название функции логирования
 * @return array
 */
function app(string $requestUri, array $request, callable $logger): array{
    $urlPath = parse_url($requestUri, PHP_URL_PATH); // Создаётся переменная, урлПаф для того, что запросы без PATH_INFO обрабатывались корректно
    $logger('Url request received: ' . $requestUri . "\n");
    if ('/lesson' === $urlPath)      // Поиск занятия. [начало]
    {
        $result = searchToLesson($request, $logger);
    } // Поиск занятия. [конец]
    elseif ('/assessmentReport' === $urlPath) {      // Поиск оценок. [начало]
        $result = searchToReport($request, $logger);
    } // Поиск оценок. [конец]
    else {
        $result = [
            'httpCode'=>404,
            'result'=>[
                'status'=>'fail',
                'message'=>'unsupported request'
            ]
        ];
    }
    return $result;
}

/**
 * Поиск по уроку
 * @param array $request - массив содержащий параметры поиска
 * @param callable $logger - название функции логирования
 * @return array - результат поиска уроков
 */
function searchToLesson(array $request, callable $logger): array
{
    $items = loadData(__DIR__ . '/../JSON/item.json');
    $teachers = loadData(__DIR__ . '/../JSON/teacher.json');
    $classes = loadData(__DIR__ . '/../JSON/class.json');
    $lessons = loadData(__DIR__ . '/../JSON/lesson.json');
    $logger('dispatch "lesson" url');
    $paramValidations=[
        'item_name'=>'Incorrect item name',
        'item_description'=>'Incorrect item description',
        'date'=>'Incorrect date',
        'teacher_fio'=>'Incorrect teacher fio',
        'teacher_cabinet'=>'Incorrect teacher cabinet',
        'class_number'=>'Incorrect class number',
        'class_letter'=>'Incorrect class letter',
    ];
    if(null===($result=paramTypeValidation($paramValidations,$request)))
    {
        $itemsIdToInfo=[];
        $teachersIdToInfo=[];
        $classesIdToInfo=[];
        $foundLessons=[];
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


        foreach ($lessons as $lesson) // Цикл по все занятиям. [начало]
        {
            $LessonMeetSearchCriteria=null;
            if (array_key_exists(
                'item_name',
                $request
            )) // Поиск по присутвию item_name в GET запросе и совпадению item_name в запросе и массиве занятий. [начало]
            {
                $LessonMeetSearchCriteria = ($request['item_name'] === $itemsIdToInfo[$lesson['item_id']]['name']);
            }// Поиск по присутвию item_name в GET запросе и совпадению item_name в запросе и массиве занятий. [конец]
            if (array_key_exists(
                'item_description',
                $request
            )) // Поиск по присутвию item_description в GET запросе и совпадению item_description в запросе и массиве занятий. [начало]
            {
                $LessonMeetSearchCriteria = ($request['item_description'] === $itemsIdToInfo[$lesson['item_id']]['description']);
            }// Поиск по присутвию item_description в GET запросе и совпадению item_description в запросе и массиве занятий. [конец]
            if (array_key_exists(
                'date',
                $request
            )) // Поиск по присутвию date в GET запросе и совпадению date в запросе и массиве занятий. [начало]
            {
                $LessonMeetSearchCriteria = ($request['date'] === $lesson['date']);
            }// Поиск по присутвию date в GET запросе и совпадению date в запросе и массиве занятий. [конец]
            if (array_key_exists(
                'teacher_fio',
                $request
            )) // Поиск по присутвию teacher_fio в GET запросе и совпадению teacher_fio в запросе и массиве занятий. [начало]
            {
                $LessonMeetSearchCriteria = ($request['teacher_fio'] === $teachersIdToInfo[$lesson['teacher_id']]['fio']);
            }// Поиск по присутвию teacher_fio в GET запросе и совпадению teacher_fio в запросе и массиве занятий. [конец]
            if (array_key_exists(
                'teacher_cabinet',
                $request
            )) // Поиск по присутвию teacher_cabinet в GET запросе и совпадению teacher_cabinet в запросе и массиве занятий. [начало]
            {
                $LessonMeetSearchCriteria = ((int)$request['teacher_cabinet'] === $teachersIdToInfo[$lesson['teacher_id']]['cabinet']);
            }// Поиск по присутвию teacher_cabinet в GET запросе и совпадению teacher_cabinet в запросе и массиве занятий. [конец]
            if (array_key_exists(
                'class_number',
                $request
            )) // Поиск по присутвию class_number в GET запросе и совпадению class_number в запросе и массиве занятий. [начало]
            {
                $LessonMeetSearchCriteria = ((int)$request['class_number'] === $classesIdToInfo[$lesson['class_id']]['number']);
            }// Поиск по присутвию class_number в GET запросе и совпадению class_number в запросе и массиве занятий. [конец]
            if (array_key_exists(
                'class_letter',
                $request
            )) // Поиск по присутвию class_letter в GET запросе и совпадению class_letter в запросе и массиве занятий. [начало]
            {
                $LessonMeetSearchCriteria = ($request['class_letter'] === $classesIdToInfo[$lesson['class_id']]['letter']);
            }// Поиск по присутвию class_letter в GET запросе и совпадению class_letter в запросе и массиве занятий. [конец]
            if($LessonMeetSearchCriteria)
            {
                $lesson['item'] = $itemsIdToInfo[$lesson['item_id']];
                $lesson['teacher'] = $teachersIdToInfo[$lesson['teacher_id']];
                $lesson['class'] = $classesIdToInfo[$lesson['class_id']];
                unset($lesson['item_id']);
                unset($lesson['teacher_id']);
                unset($lesson['class_id']);
                $foundLessons[] = $lesson;
            }
        }  //Цикл по все занятиям. [конец]
        $logger('found lessons'.count($foundLessons));
        $result= [
            'httpCode'=>200,
            'result'=>$foundLessons
        ];
    }
    return $result;
}

/**
 * Поиск по оценке
 * @return array
 * @param array $request - массив содержащий параметры поиска
 * @param callable $logger - название функции логирования
 * @return array - результат поиска оценок
 */
function searchToReport(array $request, callable $logger): array{
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
                unset($report['id']);
                unset($report['lesson_id']);
                unset($report['student_id']);
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
}

/**
 * Обработка не корректного запроса
 * @return array
 */
/*
function incorrectRequest():array{
    $result = [
        'httpCode'=>404,
        'result'=>[
            'status'=>'fail',
            'message'=>'unsupported request'
        ]
    ];
    return $result;
}*/