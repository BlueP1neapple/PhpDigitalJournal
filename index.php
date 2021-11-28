<?php
$PathToLesson = __DIR__ .'\JSON/lesson.json';
$LessonTXT=file_get_contents($PathToLesson);
$Lessons=json_decode($LessonTXT,true);  // Перевод lesson.json в php массив

if('/lesson'===$_SERVER['PATH_INFO'])      // Поиск занятия. [начало]
{
    $httpCode=200;
    $result=[];
    foreach($Lessons as $lesson) // Цикл по все занятиям. [начало]
    {
        if(array_key_exists('item_id',$_GET)&&(int)$_GET['item_id']===$lesson['item_id']) // Поиск по присутвию item_id в GET запросе и совпадению id предмета в запросе и массиве занятий. [начало]
        {
            $result[]=$lesson;
        } // Поиск по присутвию item_id в GET запросе и совпадению id предмета в запросе и массиве занятий. [конец]
        if(array_key_exists('lesson_id',$_GET)&&(int)$_GET['lesson_id']===$lesson['id'])// Поиск по присутвию lesson_id в GET запросе и совпадению id занятия в запросе и массиве занятий. [начало]
        {
            $result[]=$lesson;
        }// Поиск по присутвию lesson_id в GET запросе и совпадению id занятия в запросе и массиве занятий. [начало]
        if(array_key_exists('date',$_GET)&&$_GET['date']===$lesson['date'])// Поиск по присутвию date в GET запросе и совпадению date занятия в запросе и массиве занятий. [начало]
        {
            $result[]=$lesson;
        }// Поиск по присутвию date в GET запросе и совпадению date занятия в запросе и массиве занятий. [начало]
        if(array_key_exists('teacher_id',$_GET)&&(int)$_GET['teacher_id']===$lesson['teacher_id'])// Поиск по присутвию teacher_id в GET запросе и совпадению teacher_id занятия в запросе и массиве занятий. [начало]
        {
            $result[]=$lesson;
        }// Поиск по присутвию teacher_id в GET запросе и совпадению teacher_id занятия в запросе и массиве занятий. [начало]
    }  //Цикл по все занятиям. [конец]
} // Поиск занятия. [конец]

header('Content-type: application/json');
http_response_code($httpCode);
echo json_encode($result);
