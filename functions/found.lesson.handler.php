<?php

    require_once "functions/application.php";
    /**
     * Поиск по уроку
     * @param array $request - массив содержащий параметры поиска
     * @param callable $logger - название функции логирования
     * @return array - результат поиска уроков
     */
    return static function (array $request, callable $logger): array {
        $items = loadData(__DIR__ . '/../JSON/item.json');
        $teachers = loadData(__DIR__ . '/../JSON/teacher.json');
        $classes = loadData(__DIR__ . '/../JSON/class.json');
        $lessons = loadData(__DIR__ . '/../JSON/lesson.json');
        $logger('dispatch "lesson" url');
        $paramValidations = [
            'item_name' => 'Incorrect item name',
            'item_description' => 'Incorrect item description',
            'date' => 'Incorrect date',
            'teacher_fio' => 'Incorrect teacher fio',
            'teacher_cabinet' => 'Incorrect teacher cabinet',
            'class_number' => 'Incorrect class number',
            'class_letter' => 'Incorrect class letter',
        ];
        if (null === ($result = paramTypeValidation($paramValidations, $request))) {
            $foundLessons = [];
            $itemsIdToInfo = HashMap($items);
            $teachersIdToInfo = HashMap($teachers);
            $classesIdToInfo = HashMap($classes);
            foreach ($lessons as $lesson) // Цикл по все занятиям. [начало]
            {
                $LessonMeetSearchCriteria = null;



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
                if ($LessonMeetSearchCriteria) {
                    $lesson['item'] = $itemsIdToInfo[$lesson['item_id']];
                    $lesson['teacher'] = $teachersIdToInfo[$lesson['teacher_id']];
                    $lesson['class'] = $classesIdToInfo[$lesson['class_id']];
                    unset($lesson['item_id'], $lesson['teacher_id'], $lesson['class_id']);
                    $foundLessons[] = $lesson;
                }
            }  //Цикл по все занятиям. [конец]
            $logger('found lessons' . count($foundLessons));
            $result = [
                'httpCode' => 200,
                'result' => $foundLessons
            ];
        }
        return $result;
    };