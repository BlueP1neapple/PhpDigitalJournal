<?php
$pathToItem=__DIR__ . '\JSON/item.json';
$ItemTxt=file_get_contents($pathToItem);
$Items=json_decode($ItemTxt, true); // Перевод item.json в php массив
$ItemsIdToInfo = [];
$pathToTeacher=__DIR__ . '\JSON/teacher.json';
$TeacherTxt=file_get_contents($pathToTeacher);
$Teachers=json_decode($TeacherTxt, true); // Перевод teacher.json в php массив
$TeachersIdToInfo = [];
$PathToLesson = __DIR__ .'\JSON/lesson.json';
$LessonTXT=file_get_contents($PathToLesson);
$Lessons=json_decode($LessonTXT,true);  // Перевод lesson.json в php массив

if('/lesson'===$_SERVER['PATH_INFO'])      // Поиск занятия. [начало]
{
    foreach ($Items as $Item)// Делаем ключ id по предмету
    {
        $ItemsIdToInfo[$Item['id']]=$Item;
    } // Сделали ключ id по предмету
    foreach ($Teachers as $Teacher)// Делаем ключ id по преподавателю
    {
        $TeachersIdToInfo[$Teacher['id']]=$Teacher;
    } // Сделали ключ id по преподавателю
    $httpCode=200;
    $result=[];
    foreach($Lessons as $lesson) // Цикл по все занятиям. [начало]
    {
        if(array_key_exists('lesson_id',$_GET)) // Поиск по присутвию lesson_id в GET запросе и совпадению lesson_id в запросе и массиве занятий. [начало]
        {
            $LessonMeetSearchCriteria=((int)$_GET['lesson_id']===$lesson['id']);
        }// Поиск по присутвию lesson_id в GET запросе и совпадению lesson_id в запросе и массиве занятий. [конец]

        if(array_key_exists('item_id',$_GET)) // Поиск по присутвию item_id в GET запросе и совпадению item_id в запросе и массиве занятий. [начало]
        {
            $LessonMeetSearchCriteria=((int)$_GET['item_id']===$ItemsIdToInfo[$lesson['item_id']]['id']);
        }// Поиск по присутвию item_id в GET запросе и совпадению item_id в запросе и массиве занятий. [конец]

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

        if(array_key_exists('teacher_id',$_GET)) // Поиск по присутвию teacher_id в GET запросе и совпадению teacher_id в запросе и массиве занятий. [начало]
        {
            $LessonMeetSearchCriteria=((int)$_GET['teacher_id']===$TeachersIdToInfo[$lesson['teacher_id']]['id']);
        }// Поиск по присутвию teacher_id в GET запросе и совпадению teacher_id в запросе и массиве занятий. [конец]

        if(array_key_exists('teacher_fio',$_GET)) // Поиск по присутвию teacher_fio в GET запросе и совпадению teacher_fio в запросе и массиве занятий. [начало]
        {
            $LessonMeetSearchCriteria=($_GET['teacher_fio']===$TeachersIdToInfo[$lesson['teacher_id']]['fio']);
        }// Поиск по присутвию teacher_fio в GET запросе и совпадению teacher_fio в запросе и массиве занятий. [конец]

        if(array_key_exists('teacher_dateOfBirth',$_GET)) // Поиск по присутвию teacher_dateOfBirth в GET запросе и совпадению teacher_dateOfBirth в запросе и массиве занятий. [начало]
        {
            $LessonMeetSearchCriteria=($_GET['teacher_dateOfBirth']===$TeachersIdToInfo[$lesson['teacher_id']]['dateOfBirth']);
        }// Поиск по присутвию teacher_dateOfBirth в GET запросе и совпадению teacher_dateOfBirth в запросе и массиве занятий. [конец]

        if(array_key_exists('teacher_phone',$_GET)) // Поиск по присутвию teacher_phone в GET запросе и совпадению teacher_phone в запросе и массиве занятий. [начало]
        {
            $LessonMeetSearchCriteria=($_GET['teacher_phone']===$TeachersIdToInfo[$lesson['teacher_id']]['phone']);
        }// Поиск по присутвию teacher_phone в GET запросе и совпадению teacher_phone в запросе и массиве занятий. [конец]

        if(array_key_exists('class_id',$_GET)&&(int)$_GET['class_id']===$lesson['class_id'])// Поиск по присутвию class_id в GET запросе и совпадению class_id занятия в запросе и массиве занятий. [начало]
        {
            $result[]=$lesson;
        }// Поиск по присутвию class_id в GET запросе и совпадению class_id занятия в запросе и массиве занятий. [начало]
        if ($LessonMeetSearchCriteria)
        {
            $lesson['item']=$ItemsIdToInfo[$lesson['item_id']];
            $lesson['teacher']=$TeachersIdToInfo[$lesson['teacher_id']];
            unset($lesson['item_id']);
            unset($lesson['teacher_id']);
            $result[]=$lesson;
        }
    }  //Цикл по все занятиям. [конец]
} // Поиск занятия. [конец]

header('Content-type: application/json');
http_response_code($httpCode);
echo json_encode($result);
