<?php
// Функции

/**
 * Функция рэдеренга
 * @param int $httpCode - код ответа
 * @param array $data - ответ
 */
function render(int $httpCode, array $data): void
{
    header('Content-Type: application/json');
    http_response_code($httpCode);
    echo json_encode($data);
    exit();
}

/**
 * Обработка ошибок
 * @param string $status - статус ответа
 * @param string $message - сообщение об ошибке
 * @param int $httpCode - код ошибки
 */
function errorHandLing(string $status, string $message, int $httpCode): void
{
    //logger($message);
    $result = [
        'status' => $status,
        'message' => $message
    ];
    render($httpCode, $result);
    exit();
}

/**
 * Функция валидации
 * @param string $paramName - Имя параметра
 * @param array $params - все параметры
 * @param string $errorMsg - сообщение об ошибке
 */
function paramTypeValidation(string $paramName, array $params, string $errorMsg): void
{
    if (array_key_exists($paramName, $params) && false === is_string($params[$paramName])) {
        errorHandLing('fail', $errorMsg, 500);
    }
}

/**
 * Загузка данных
 * @param string $sourcePath
 * @return array
 */
function loadData(string $sourcePath): array
{
    $pathToFile = $sourcePath;
    $content = file_get_contents($pathToFile);
    return json_decode($content, true);
}


// Перевод json в массив
$pathToItem = __DIR__ . '/JSON/item.json';
$ItemTxt = file_get_contents($pathToItem);
$Items = json_decode($ItemTxt, true); // Перевод item.json в php массив
$ItemsIdToInfo = [];

$pathToTeacher = __DIR__ . '/JSON/teacher.json';
$TeacherTxt = file_get_contents($pathToTeacher);
$Teachers = json_decode($TeacherTxt, true); // Перевод teacher.json в php массив
$TeachersIdToInfo = [];

$pathToClass = __DIR__ . '/JSON/class.json';
$ClassTxt = file_get_contents($pathToClass);
$Classes = json_decode($ClassTxt, true); // Перевод class.json в php массив
$ClassesIdToInfo = [];

$pathToReport = __DIR__ . '/JSON/assessmentReport.json';
$ReportTxt = file_get_contents($pathToReport);
$Report = json_decode($ReportTxt, true); // Перевод assessmentReport.json в php массив

$PathToLesson = __DIR__ . '/JSON/lesson.json';
$LessonTxt = file_get_contents($PathToLesson);
$Lessons = json_decode($LessonTxt, true);  // Перевод lesson.json в php массив
$LessonIdToInfo = [];

$pathToReport = __DIR__ . '/JSON/assessmentReport.json';
$ReportTxt = file_get_contents($pathToReport);
$Reports = json_decode($ReportTxt, true); // Перевод assessmentReport.json в php массив
$ReportIdToInfo = [];

$pathToStudent = __DIR__ . '/JSON/student.json';
$StudentTxt = file_get_contents($pathToStudent);
$Students = json_decode($StudentTxt, true);
$StudentIdToInfo = [];

$pathToParent = __DIR__ . '/JSON/parent.json';
$ParentTxt = file_get_contents($pathToParent);
$Parents = json_decode($ParentTxt, true);
$ParentIdToInfo = [];

$pathToLogFile = __DIR__ . '/app.log';
file_put_contents(
    $pathToLogFile,
    'Url request received: ' . $_SERVER['REQUEST_URI'] . "\n",
    FILE_APPEND
); // Логирование

$pathInfo = array_key_exists(
    'PATH_INFO',
    $_SERVER
) && $_SERVER['PATH_INFO'] ? $_SERVER['PATH_INFO'] : ''; // Создаётся переменная, пафИнфо для того, что запросы без PATH_INFO обрабатывались корректно

function searchToLesson(): array
{
    $items = loadData(__DIR__ . '/JSON/item.json');
    $teachers = loadData(__DIR__ . '/JSON/teacher.json');
    $classes = loadData(__DIR__ . '/JSON/class.json');
    $lessons = loadData(__DIR__ . '/JSON/lesson.json');
    foreach ($items as $Item)// Делаем ключ id по предмету
    {
        $ItemsIdToInfo[$Item['id']] = $Item;
    } // Сделали ключ id по предмету
    foreach ($teachers as $Teacher)// Делаем ключ id по преподавателю
    {
        $TeachersIdToInfo[$Teacher['id']] = $Teacher;
    } // Сделали ключ id по преподавателю
    foreach ($classes as $Class)// Делаем ключ id по классам
    {
        $ClassesIdToInfo[$Class['id']] = $Class;
    } // Сделали ключ id по классам
    foreach ($lessons as $lesson) {
        $LessonIdToInfo[$lesson['id']] = $lesson;
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
            $LessonMeetSearchCriteria = ($_GET['item_name'] === $ItemsIdToInfo[$lesson['item_id']]['name']);
        }// Поиск по присутвию item_name в GET запросе и совпадению item_name в запросе и массиве занятий. [конец]
        if (array_key_exists(
            'item_description',
            $_GET
        )) // Поиск по присутвию item_description в GET запросе и совпадению item_description в запросе и массиве занятий. [начало]
        {
            $LessonMeetSearchCriteria = ($_GET['item_description'] === $ItemsIdToInfo[$lesson['item_id']]['description']);
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
            $LessonMeetSearchCriteria = ($_GET['teacher_fio'] === $TeachersIdToInfo[$lesson['teacher_id']]['fio']);
        }// Поиск по присутвию teacher_fio в GET запросе и совпадению teacher_fio в запросе и массиве занятий. [конец]
        if (array_key_exists(
            'teacher_cabinet',
            $_GET
        )) // Поиск по присутвию teacher_cabinet в GET запросе и совпадению teacher_cabinet в запросе и массиве занятий. [начало]
        {
            $LessonMeetSearchCriteria = ((int)$_GET['teacher_cabinet'] === $TeachersIdToInfo[$lesson['teacher_id']]['cabinet']);
        }// Поиск по присутвию teacher_cabinet в GET запросе и совпадению teacher_cabinet в запросе и массиве занятий. [конец]
        if (array_key_exists(
            'class_number',
            $_GET
        )) // Поиск по присутвию class_number в GET запросе и совпадению class_number в запросе и массиве занятий. [начало]
        {
            $LessonMeetSearchCriteria = ((int)$_GET['class_number'] === $ClassesIdToInfo[$lesson['class_id']]['number']);
        }// Поиск по присутвию class_number в GET запросе и совпадению class_number в запросе и массиве занятий. [конец]
        if (array_key_exists(
            'class_letter',
            $_GET
        )) // Поиск по присутвию class_letter в GET запросе и совпадению class_letter в запросе и массиве занятий. [начало]
        {
            $LessonMeetSearchCriteria = ($_GET['class_letter'] === $ClassesIdToInfo[$lesson['class_id']]['letter']);
        }// Поиск по присутвию class_letter в GET запросе и совпадению class_letter в запросе и массиве занятий. [конец]
        $lesson['item'] = $ItemsIdToInfo[$lesson['item_id']];
        $lesson['teacher'] = $TeachersIdToInfo[$lesson['teacher_id']];
        $lesson['class'] = $ClassesIdToInfo[$lesson['class_id']];
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

function searchToReport(): array{
    $items = loadData(__DIR__ . '/JSON/item.json');
    $teachers = loadData(__DIR__ . '/JSON/teacher.json');
    $classes = loadData(__DIR__ . '/JSON/class.json');
    $lessons = loadData(__DIR__ . '/JSON/lesson.json');
    $reports = loadData( __DIR__ . '/JSON/assessmentReport.json');
    $students = loadData(__DIR__ . '/JSON/student.json');
    $parents = loadData(__DIR__ .'/JSON/parent.json');
    foreach ($items as $Item)// Делаем ключ id по предмету
    {
        $ItemsIdToInfo[$Item['id']] = $Item;
    } // Сделали ключ id по предмету
    foreach ($teachers as $Teacher)// Делаем ключ id по преподавателю
    {
        $TeachersIdToInfo[$Teacher['id']] = $Teacher;
    } // Сделали ключ id по преподавателю
    foreach ($classes as $Class)// Делаем ключ id по классам
    {
        $ClassesIdToInfo[$Class['id']] = $Class;
    } // Сделали ключ id по классам
    foreach ($lessons as $lesson) {
        $LessonIdToInfo[$lesson['id']] = $lesson;
    } // Ключи id по урокам
    foreach ($reports as $report) {
        $ReportIdToInfo[$report['id']] = $report;
    }
    foreach ($students as $student) {
        $StudentIdToInfo[$student['id']] = $student;
    }
    foreach ($parents as $parent) {
        $ParentIdToInfo[$parent['id']] = $parent;
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
            $ReportMeetSearchCriteria = ($_GET['item_name'] === $ItemsIdToInfo[$LessonIdToInfo[$report['lesson_id']]['item_id']]['name']);
        }// Поиск по присутвию item_name в GET запросе и совпадению item_name в запросе и массиве оценок. [конец]
        if (array_key_exists('item_description', $_GET)) {
            $ReportMeetSearchCriteria = ($_GET['item_description'] === $ItemsIdToInfo[$LessonIdToInfo[$report['lesson_id']]['item_id']]['description']);
        }
        if (array_key_exists('lesson_date', $_GET)) {
            $ReportMeetSearchCriteria = ($_GET['lesson_date'] === $LessonIdToInfo[$report['lesson_id']]['date']);
        }
        if (array_key_exists('student_fio', $_GET)) {
            $ReportMeetSearchCriteria = ($_GET['student_fio'] === $StudentIdToInfo[$report['student_id']]['fio']);
        }

        if ($ReportMeetSearchCriteria) {
            $report['student'] = $StudentIdToInfo[$report['student_id']];
            $report['lesson'] = $LessonIdToInfo[$report['lesson_id']];
            $report['lesson']['item'] = $ItemsIdToInfo[$report['lesson']['item_id']];
            $report['lesson']['teacher'] = $TeachersIdToInfo[$report['lesson']['teacher_id']];
            $report['lesson']['class'] = $ClassesIdToInfo[$report['student']['class_id']];

            $report['student']['parent'] = $ParentIdToInfo[$report['student']['parent_id']];
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

if ('/lesson' === $pathInfo)      // Поиск занятия. [начало]
{
    $result = searchToLesson();
} // Поиск занятия. [конец]
elseif ('/assessmentReport' === $_SERVER['PATH_INFO']) {      // Поиск оценок. [начало]
    $result = searchToReport();
} // Поиск оценок. [конец]
else {
    $result = incorrectRequest();
}
render($result['httpCode'], $result['result']);