<?php
$pathToItem=__DIR__ . '/JSON/item.json';
$ItemTxt=file_get_contents($pathToItem);
$Items=json_decode($ItemTxt, true); // Перевод item.json в php массив
$ItemsIdToInfo = [];
$pathToTeacher=__DIR__ . '/JSON/teacher.json';
$TeacherTxt=file_get_contents($pathToTeacher);
$Teachers=json_decode($TeacherTxt, true); // Перевод teacher.json в php массив
$TeachersIdToInfo = [];
$pathToClass=__DIR__ . '/JSON/class.json';
$ClassTxt=file_get_contents($pathToClass);
$Classes=json_decode($ClassTxt, true); // Перевод class.json в php массив
$ClassesIdToInfo = [];
$PathToLesson = __DIR__ .'/JSON/lesson.json';
$LessonTxt=file_get_contents($PathToLesson);
$Lessons=json_decode($LessonTxt,true);  // Перевод lesson.json в php массив
$pathToReport=__DIR__ . '/JSON/assessmentReport.json';
$ReportTxt=file_get_contents($pathToReport);
$Report=json_decode($ReportTxt, true); // Перевод assessmentReport.json в php массив

foreach ($Items as $Item)// Делаем ключ id по предмету
{
    $ItemsIdToInfo[$Item['id']]=$Item;
} // Сделали ключ id по предмету
foreach ($Teachers as $Teacher)// Делаем ключ id по преподавателю
{
    $TeachersIdToInfo[$Teacher['id']]=$Teacher;
} // Сделали ключ id по преподавателю
foreach ($Classes as $Class)// Делаем ключ id по классам
{
    $ClassesIdToInfo[$Class['id']]=$Class;
} // Сделали ключ id по классам

if('/lesson'===$_SERVER['PATH_INFO'])      // Поиск занятия. [начало]
{
    $httpCode=200;
    $result=[];
    foreach($Lessons as $lesson) // Цикл по все занятиям. [начало]
    {
        if(array_key_exists('item_name',$_GET)) // Поиск по присутвию item_name в GET запросе и совпадению item_name в запросе и массиве занятий. [начало]
        {
            $LessonMeetSearchCriteria=($_GET['item_name']===$ItemsIdToInfo[$lesson['item_id']]['name']);
        }// Поиск по присутвию item_name в GET запросе и совпадению item_name в запросе и массиве занятий. [конец]

        if(array_key_exists('item_description',$_GET)) // Поиск по присутвию item_description в GET запросе и совпадению item_description в запросе и массиве занятий. [начало]
        {
            $LessonMeetSearchCriteria=($_GET['item_description']===$ItemsIdToInfo[$lesson['item_id']]['description']);
        }// Поиск по присутвию item_description в GET запросе и совпадению item_description в запросе и массиве занятий. [конец]

        if(array_key_exists('date',$_GET)) // Поиск по присутвию date в GET запросе и совпадению date в запросе и массиве занятий. [начало]
        {
            $LessonMeetSearchCriteria=($_GET['date']===$lesson['date']);
        }// Поиск по присутвию date в GET запросе и совпадению date в запросе и массиве занятий. [конец]

        if(array_key_exists('teacher_fio',$_GET)) // Поиск по присутвию teacher_fio в GET запросе и совпадению teacher_fio в запросе и массиве занятий. [начало]
        {
            $LessonMeetSearchCriteria=($_GET['teacher_fio']===$TeachersIdToInfo[$lesson['teacher_id']]['fio']);
        }// Поиск по присутвию teacher_fio в GET запросе и совпадению teacher_fio в запросе и массиве занятий. [конец]

        if(array_key_exists('teacher_cabinet',$_GET)) // Поиск по присутвию teacher_cabinet в GET запросе и совпадению teacher_cabinet в запросе и массиве занятий. [начало]
        {
            $LessonMeetSearchCriteria=((int)$_GET['teacher_cabinet']===$TeachersIdToInfo[$lesson['teacher_id']]['cabinet']);
        }// Поиск по присутвию teacher_cabinet в GET запросе и совпадению teacher_cabinet в запросе и массиве занятий. [конец]

        if(array_key_exists('class_number',$_GET)) // Поиск по присутвию class_number в GET запросе и совпадению class_number в запросе и массиве занятий. [начало]
        {
            $LessonMeetSearchCriteria=((int)$_GET['class_number']===$ClassesIdToInfo[$lesson['class_id']]['number']);
        }// Поиск по присутвию class_number в GET запросе и совпадению class_number в запросе и массиве занятий. [конец]

        if(array_key_exists('class_letter',$_GET)) // Поиск по присутвию class_letter в GET запросе и совпадению class_letter в запросе и массиве занятий. [начало]
        {
            $LessonMeetSearchCriteria=($_GET['class_letter']===$ClassesIdToInfo[$lesson['class_id']]['letter']);
        }// Поиск по присутвию class_letter в GET запросе и совпадению class_letter в запросе и массиве занятий. [конец]

        if ($LessonMeetSearchCriteria)
        {
            $lesson['item']=$ItemsIdToInfo[$lesson['item_id']];
            $lesson['teacher']=$TeachersIdToInfo[$lesson['teacher_id']];
            $lesson['class']=$ClassesIdToInfo[$lesson['class_id']];
            unset($lesson['item_id']);
            unset($lesson['teacher_id']);
            unset($lesson['class_id']);
            $result[]=$lesson;
        }
    }  //Цикл по все занятиям. [конец]
} // Поиск занятия. [конец]
elseif('/assessmentReport'===$_SERVER['PATH_INFO']){      // Поиск оценок. [начало]
    $httpCode=200;
    $result=[];

    foreach ($Report as $report){
        if(array_key_exists('item_name',$_GET)) // Поиск по присутвию item_name в GET запросе и совпадению item_name в запросе и массиве оценок. [начало]
        {
            $ReportMeetSearchCriteria=($_GET['item_name']===$ItemsIdToInfo[$report['item_id']]['name']);
        }// Поиск по присутвию item_name в GET запросе и совпадению item_name в запросе и массиве оценок. [конец]
        if ($ReportMeetSearchCriteria)
        {
            $report['item']=$ItemsIdToInfo[$report['item_id']];
            $report['teacher']=$TeachersIdToInfo[$report['teacher_id']];
            $report['class']=$ClassesIdToInfo[$report['class_id']];
            unset($report['item_id']);
            unset($report['teacher_id']);
            unset($report['class_id']);
            $result[]=$report;
        }
    }//Цикл по оценкам [конец]
} // Поиск оценок. [конец]

header('Content-type: application/json');
http_response_code($httpCode);
echo json_encode($result);