<?php
$PathToLesson = __DIR__ .'\JSON/lesson.json';
$LessonTXT=file_get_contents($PathToLesson);
$Lessons=json_decode($LessonTXT,true);  // Перевод lesson.json в php массив

if('/lesson'===$_SERVER['PATH_INFO'])      //Поиск занятия по id предмета. [начало]
{
    $httpCode=200;
    $result=[];
    foreach($Lessons as $lesson)// //Цикл по все занятиям. [начало]
    {
        if(array_key_exists('item_id',$_GET)&&(int)$_GET['item_id']===$lesson['item_id']) //Поиск по присутвию item_id в GET запросе и совпадению id предмета в запросе и массиве занятий. [начало]
        {
            $result[]=$lesson;
            break;
        } //Поиск по присутвию item_id в GET запросе и совпадению id предмета в запросе и массиве занятий. [конец]
    }  // //Цикл по все занятиям. [конец]
} //Поиск занятия по id предмета. [конец]

header('Content-type: application/json');
http_response_code($httpCode);
echo json_encode($result);