#!/usr/bin/env php
<?php

$dsn="pgsql:host=localhost;port=5432;dbname=digital_journal_db";
$dbConn = new PDO($dsn, 'postgres', '');


/**
 * Импорт родителей из json в бд
 *
 */
$dbConn->query('DELETE FROM users_parents');

$parentData = json_decode(file_get_contents(__DIR__ . '/../data/parent.json'), true, 512, JSON_THROW_ON_ERROR);

foreach ($parentData as $parent){
    $dob = date_create_from_format("Y.m.d", $parent['dateOfBirth']);
    $sql = "INSERT INTO users_parents(id, date_of_birth, phone, place_of_work, email, login, password)
   VALUES ({$parent['id']}, '{$dob->format('Y-m-d')}', '{$parent['phone']}', '{$parent['placeOfWork']}', '{$parent['email']}', '{$parent['login']}', '{$parent['password']}')";
    $dbConn->query($sql);
}

$userFromDb = $dbConn->query('SELECT * FROM users_parents')->fetchAll(PDO::FETCH_ASSOC);
//var_dump($userFromDb);


/**
 * Импорт учителей из json в бд
 *
 */
$dbConn->query('DELETE FROM users_teachers');

$teachersData = json_decode(file_get_contents(__DIR__ . '/../data/teacher.json'), true, 512, JSON_THROW_ON_ERROR);

foreach ($teachersData as $teacher){
    $dob = date_create_from_format("Y.m.d", $teacher['dateOfBirth']);
    $sql = "INSERT INTO users_teachers(id, date_of_birth, phone, item_id, cabinet, email, login, password)
   VALUES ({$teacher['id']}, '{$dob->format('Y-m-d')}', '{$teacher['phone']}', '{$teacher['idItem']}', {$teacher['cabinet']}, '{$teacher['email']}', '{$teacher['login']}', '{$teacher['password']}')";
    $dbConn->query($sql);
}

$userFromDb = $dbConn->query('SELECT * FROM users_teachers')->fetchAll(PDO::FETCH_ASSOC);
//var_dump($userFromDb);

/**
 * Импорт учеников из json в бд
 *
 */
$dbConn->query('DELETE FROM users_students');

$studentData = json_decode(file_get_contents(__DIR__ . '/../data/student.json'), true, 512, JSON_THROW_ON_ERROR);

foreach ($studentData as $student){
    $dob = date_create_from_format("Y.m.d", $student['dateOfBirth']);
    $sql = "INSERT INTO users_students(id, date_of_birth, phone, class_id, parent_id, login, password)
   VALUES ({$student['id']}, '{$dob->format('Y-m-d')}', '{$student['phone']}', {$student['class_id']}, {$student['parent_id']}, '{$student['login']}', '{$student['password']}')";
    $dbConn->query($sql);
}

$userFromDb = $dbConn->query('SELECT * FROM users_students')->fetchAll(PDO::FETCH_ASSOC);
//var_dump($userFromDb);

/**
 * Импорт предметов из json в бд
 *
 */
$dbConn->query('DELETE FROM item');

$itemData = json_decode(file_get_contents(__DIR__ . '/../data/item.json'), true, 512, JSON_THROW_ON_ERROR);

foreach ($itemData as $item){
    $sql = "INSERT INTO item(id, name, description)
   VALUES ({$item['id']},'{$item['name']}', '{$item['description']}')";
    $dbConn->query($sql);
}

$userFromDb = $dbConn->query('SELECT * FROM item')->fetchAll(PDO::FETCH_ASSOC);
//var_dump($userFromDb);

/**
 * Импорт предметов из json в бд
 *
 */
$dbConn->query('DELETE FROM lesson');

$lessonData = json_decode(file_get_contents(__DIR__ . '/../data/lesson.json'), true, 512, JSON_THROW_ON_ERROR);

foreach ($lessonData as $lesson){
    $dob = date_create_from_format("Y.m.d H:i", $lesson['date']);
    $sql = "INSERT INTO lesson(id, item_id, date, lesson_duration, teacher_id, class_id)
   VALUES ({$lesson['id']},{$lesson['item_id']}, '{$dob->format('Y-m-d H:i')}', {$lesson['lessonDuration']}, {$lesson['teacher_id']}, {$lesson['class_id']})";
    $dbConn->query($sql);
}

$userFromDb = $dbConn->query('SELECT * FROM lesson')->fetchAll(PDO::FETCH_ASSOC);
//var_dump($userFromDb);

/**
 * Импорт класс из json в бд
 *
 */
$dbConn->query('DELETE FROM class');

$classData = json_decode(file_get_contents(__DIR__ . '/../data/class.json'), true, 512, JSON_THROW_ON_ERROR);

foreach ($classData as $class){
    $sql = "INSERT INTO class(id, number, letter)
   VALUES ({$class['id']},{$class['number']}, '{$class['letter']}')";
    $dbConn->query($sql);
}

$userFromDb = $dbConn->query('SELECT * FROM class')->fetchAll(PDO::FETCH_ASSOC);
//var_dump($userFromDb);


/**
 * Импорт оценок из json в бд
 *
 */
$dbConn->query('DELETE FROM assessment_report');

$reportData = json_decode(file_get_contents(__DIR__ . '/../data/assessmentReport.json'), true, 512, JSON_THROW_ON_ERROR);

foreach ($reportData as $report){
    $sql = "INSERT INTO assessment_report(id, lesson_id, student_id, mark)
   VALUES ({$report['id']},{$report['lesson_id']}, {$report['student_id']}, {$report['mark']})";
    $dbConn->query($sql);
}

$userFromDb = $dbConn->query('SELECT * FROM assessment_report')->fetchAll(PDO::FETCH_ASSOC);
//var_dump($userFromDb);


/**
 * Импорт фио
 *
 */
$dbConn->query('DELETE FROM fio');
$users = array_merge( $parentData, $studentData, $teachersData);
foreach ($users as $user){
    $id = $user['id'];
    foreach ($user['fio'] as $value){
        $sql = "INSERT INTO fio(user_id, name, surname, patronymic)
        VALUES ({$id}, '{$value['name']}','{$value['surname']}', '{$value['patronymic']}')";
        $dbConn->query($sql);
    }
}
$userFromDb = $dbConn->query('SELECT * FROM fio')->fetchAll(PDO::FETCH_ASSOC);
//var_dump($userFromDb);

/**
 * Импорт фио
 *
 */
$dbConn->query('DELETE FROM address');
foreach ($users as $user){
    $id = $user['id'];
    foreach ($user['address'] as $value){
        $sql = "INSERT INTO address(user_id, street, home, apartment)
        VALUES ({$id}, '{$value['street']}','{$value['home']}', '{$value['apartment']}')";
        $dbConn->query($sql);
    }
}
$userFromDb = $dbConn->query('SELECT * FROM address')->fetchAll(PDO::FETCH_ASSOC);
//var_dump($userFromDb);

