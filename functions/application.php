<?php



/**
 * Логика основного приложения
 * @return array
 */
function app(): array{
    $pathInfo = array_key_exists(
        'PATH_INFO',
        $_SERVER
    ) && $_SERVER['PATH_INFO'] ? $_SERVER['PATH_INFO'] : ''; // Создаётся переменная, пафИнфо для того, что запросы без PATH_INFO обрабатывались корректно

    if ('/lesson' === $pathInfo)      // Поиск занятия. [начало]
    {
        $result = searchToLesson();
    } // Поиск занятия. [конец]
    elseif ('/assessmentReport' === $pathInfo) {      // Поиск оценок. [начало]
        $result = searchToReport();
    } // Поиск оценок. [конец]
    else {
        $result = incorrectRequest();
    }
    return $result;
}

/**
 * Поиск по уроку
 * @return array
 */
function searchToLesson(): array
{
    $items = loadData(__DIR__ . '/../JSON/item.json');
    $teachers = loadData(__DIR__ . '/../JSON/teacher.json');
    $classes = loadData(__DIR__ . '/../JSON/class.json');
    $lessons = loadData(__DIR__ . '/../JSON/lesson.json');
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
    $httpCode = 200;
    $result = [];
    //file_put_contents($pathToLogFile, 'dispatch "lesson" url' . "\n", FILE_APPEND);
    paramTypeValidation('item_name', $_GET, 'Incorrect item name');
    paramTypeValidation('item_description', $_GET, 'Incorrect item description');
    paramTypeValidation('date', $_GET, 'Incorrect date');
    paramTypeValidation('teacher_fio', $_GET, 'Incorrect teacher fio');
    paramTypeValidation('teacher_cabinet', $_GET, 'Incorrect teacher cabinet');
    paramTypeValidation('class_number', $_GET, 'Incorrect class number');
    paramTypeValidation('class_letter', $_GET, 'Incorrect class letter');

    foreach ($lessons as $lesson) // Цикл по все занятиям. [начало]
    {
        if (array_key_exists(
            'item_name',
            $_GET
        )) // Поиск по присутвию item_name в GET запросе и совпадению item_name в запросе и массиве занятий. [начало]
        {
            $LessonMeetSearchCriteria = ($_GET['item_name'] === $itemsIdToInfo[$lesson['item_id']]['name']);
        }// Поиск по присутвию item_name в GET запросе и совпадению item_name в запросе и массиве занятий. [конец]
        if (array_key_exists(
            'item_description',
            $_GET
        )) // Поиск по присутвию item_description в GET запросе и совпадению item_description в запросе и массиве занятий. [начало]
        {
            $LessonMeetSearchCriteria = ($_GET['item_description'] === $itemsIdToInfo[$lesson['item_id']]['description']);
        }// Поиск по присутвию item_description в GET запросе и совпадению item_description в запросе и массиве занятий. [конец]
        if (array_key_exists(
            'date',
            $_GET
        )) // Поиск по присутвию date в GET запросе и совпадению date в запросе и массиве занятий. [начало]
        {
            $LessonMeetSearchCriteria = ($_GET['date'] === $lesson['date']);
        }// Поиск по присутвию date в GET запросе и совпадению date в запросе и массиве занятий. [конец]
        if (array_key_exists(
            'teacher_fio',
            $_GET
        )) // Поиск по присутвию teacher_fio в GET запросе и совпадению teacher_fio в запросе и массиве занятий. [начало]
        {
            $LessonMeetSearchCriteria = ($_GET['teacher_fio'] === $teachersIdToInfo[$lesson['teacher_id']]['fio']);
        }// Поиск по присутвию teacher_fio в GET запросе и совпадению teacher_fio в запросе и массиве занятий. [конец]
        if (array_key_exists(
            'teacher_cabinet',
            $_GET
        )) // Поиск по присутвию teacher_cabinet в GET запросе и совпадению teacher_cabinet в запросе и массиве занятий. [начало]
        {
            $LessonMeetSearchCriteria = ((int)$_GET['teacher_cabinet'] === $teachersIdToInfo[$lesson['teacher_id']]['cabinet']);
        }// Поиск по присутвию teacher_cabinet в GET запросе и совпадению teacher_cabinet в запросе и массиве занятий. [конец]
        if (array_key_exists(
            'class_number',
            $_GET
        )) // Поиск по присутвию class_number в GET запросе и совпадению class_number в запросе и массиве занятий. [начало]
        {
            $LessonMeetSearchCriteria = ((int)$_GET['class_number'] === $classesIdToInfo[$lesson['class_id']]['number']);
        }// Поиск по присутвию class_number в GET запросе и совпадению class_number в запросе и массиве занятий. [конец]
        if (array_key_exists(
            'class_letter',
            $_GET
        )) // Поиск по присутвию class_letter в GET запросе и совпадению class_letter в запросе и массиве занятий. [начало]
        {
            $LessonMeetSearchCriteria = ($_GET['class_letter'] === $classesIdToInfo[$lesson['class_id']]['letter']);
        }// Поиск по присутвию class_letter в GET запросе и совпадению class_letter в запросе и массиве занятий. [конец]
        $lesson['item'] = $itemsIdToInfo[$lesson['item_id']];
        $lesson['teacher'] = $teachersIdToInfo[$lesson['teacher_id']];
        $lesson['class'] = $classesIdToInfo[$lesson['class_id']];
        unset($lesson['item_id']);
        unset($lesson['teacher_id']);
        unset($lesson['class_id']);
        $result[] = $lesson;
    }  //Цикл по все занятиям. [конец]
    return [
        'httpCode' => $httpCode,
        'result' => $result
    ];
    //file_put_contents($pathToLogFile, 'found lesson"' . "\n", FILE_APPEND);
}

/**
 * Поиск по оценке
 * @return array
 */
function searchToReport(): array{
    $items = loadData(__DIR__ . '/../JSON/item.json');
    $teachers = loadData(__DIR__ . '/../JSON/teacher.json');
    $classes = loadData(__DIR__ . '/../JSON/class.json');
    $lessons = loadData(__DIR__ . '/../JSON/lesson.json');
    $reports = loadData( __DIR__ . '/../JSON/assessmentReport.json');
    $students = loadData(__DIR__ . '/../JSON/student.json');
    $parents = loadData(__DIR__ .'/../JSON/parent.json');
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
    foreach ($reports as $report) {
        $ReportIdToInfo[$report['id']] = $report;
    }
    foreach ($students as $student) {
        $studentIdToInfo[$student['id']] = $student;
    }
    foreach ($parents as $parent) {
        $parentIdToInfo[$parent['id']] = $parent;
    }
    //file_put_contents($pathToLogFile, 'select search by Report' . "\n", FILE_APPEND);
    $httpCode = 200;
    $result = [];
    foreach ($reports as $report) {
        if (array_key_exists(
            'item_name',
            $_GET
        )) // Поиск по присутвию item_name в GET запросе и совпадению item_name в запросе и массиве оценок. [начало]
        {
            $ReportMeetSearchCriteria = ($_GET['item_name'] === $itemsIdToInfo[$lessonIdToInfo[$report['lesson_id']]['item_id']]['name']);
        }// Поиск по присутвию item_name в GET запросе и совпадению item_name в запросе и массиве оценок. [конец]
        if (array_key_exists('item_description', $_GET)) {
            $ReportMeetSearchCriteria = ($_GET['item_description'] === $itemsIdToInfo[$lessonIdToInfo[$report['lesson_id']]['item_id']]['description']);
        }
        if (array_key_exists('lesson_date', $_GET)) {
            $ReportMeetSearchCriteria = ($_GET['lesson_date'] === $lessonIdToInfo[$report['lesson_id']]['date']);
        }
        if (array_key_exists('student_fio', $_GET)) {
            $ReportMeetSearchCriteria = ($_GET['student_fio'] === $studentIdToInfo[$report['student_id']]['fio']);
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
            $result[] = $report;
            //file_put_contents($pathToLogFile, 'result search = ' . $result . "\n", FILE_APPEND);
        }
    }//Цикл по оценкам [конец]
    return [
        'httpCode' => $httpCode,
        'result' => $result
    ];
}

/**
 * Обработка не корректного запроса
 * @return array
 */
function incorrectRequest():array{
    $httpCode = 404;
    $result = [
        'status' => 'fail',
        'message' => 'unsupported request',
    ];
    return [
        'httpCode' => $httpCode,
        'result' => $result
    ];
}