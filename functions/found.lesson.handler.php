<?php

    require_once "functions/application.php";
    require_once __DIR__ . "/../Classes/ItemClass.php";
    require_once __DIR__ . "/../Classes/LessonClass.php";
    require_once __DIR__ . "/../Classes/ClassClass.php";
    require_once __DIR__ . "/../Classes/ReportClass.php";
    require_once __DIR__ . "/../Classes/StudentUserClass.php";
    require_once __DIR__ . "/../Classes/TeacherUserClass.php";
    require_once __DIR__ . "/../Classes/ParentUserClass.php";
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
            $itemsIdToInfo = [];
            $teachersIdToInfo = [];
            $classesIdToInfo = [];


            foreach ($items as $item){
                $itemsObj = new ItemClass();
                $itemsObj->setId($item['id']);
                $itemsObj->setName($item['name']);
                $itemsObj->setDescription($item['description']);
                $itemsIdToInfo[$item['id']] = $itemsObj;
            }

            foreach ($teachers as $teacher){
                $teachersObj = new TeacherUserClass();
                $teachersObj->setId($teacher['id']);
                $teachersObj->setFio($teacher['fio']);
                $teachersObj->setPhone($teacher['phone']);
                $teachersObj->setAddress($teacher['address']);
                $teachersObj->setCabinet($teacher['cabinet']);
                $teachersObj->setEmail($teacher['email']);
                $teachersObj->setItem($itemsIdToInfo[$teacher['idItem']]);
                $teachersIdToInfo[$teacher['id']] = $teachersObj;

            }

            foreach ($classes as $class){
                $classesObj = new ClassClass();
                $classesObj->setId($class['id']);
                $classesObj->setNumber($class['number']);
                $classesObj->setLetter($class['letter']);
                $classesIdToInfo[$class['id']] = $classesObj;
            }



            foreach ($lessons as $lesson) // Цикл по все занятиям. [начало]
            {
                $LessonMeetSearchCriteria = null;



                if (array_key_exists(
                    'item_name',
                    $request
                )) // Поиск по присутвию item_name в GET запросе и совпадению item_name в запросе и массиве занятий. [начало]
                {
                    $LessonMeetSearchCriteria = ($request['item_name'] === $itemsIdToInfo[$lesson['item_id']]->getName());
                }// Поиск по присутвию item_name в GET запросе и совпадению item_name в запросе и массиве занятий. [конец]


                if (array_key_exists(
                    'item_description',
                    $request
                )) // Поиск по присутвию item_description в GET запросе и совпадению item_description в запросе и массиве занятий. [начало]
                {
                    $LessonMeetSearchCriteria = ($request['item_description'] === $itemsIdToInfo[$lesson['item_id']]->getDescription());
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
                    $LessonMeetSearchCriteria = ($request['teacher_fio'] === $teachersIdToInfo[$lesson['teacher_id']]->getFio());
                }// Поиск по присутвию teacher_fio в GET запросе и совпадению teacher_fio в запросе и массиве занятий. [конец]
                if (array_key_exists(
                    'teacher_cabinet',
                    $request
                )) // Поиск по присутвию teacher_cabinet в GET запросе и совпадению teacher_cabinet в запросе и массиве занятий. [начало]
                {
                    $LessonMeetSearchCriteria = ((int)$request['teacher_cabinet'] === $teachersIdToInfo[$lesson['teacher_id']]->getCabinet());
                }// Поиск по присутвию teacher_cabinet в GET запросе и совпадению teacher_cabinet в запросе и массиве занятий. [конец]
                if (array_key_exists(
                    'class_number',
                    $request
                )) // Поиск по присутвию class_number в GET запросе и совпадению class_number в запросе и массиве занятий. [начало]
                {
                    $LessonMeetSearchCriteria = ((int)$request['class_number'] === $classesIdToInfo[$lesson['class_id']]->getNumber());
                }// Поиск по присутвию class_number в GET запросе и совпадению class_number в запросе и массиве занятий. [конец]
                if (array_key_exists(
                    'class_letter',
                    $request
                )) // Поиск по присутвию class_letter в GET запросе и совпадению class_letter в запросе и массиве занятий. [начало]
                {
                    $LessonMeetSearchCriteria = ($request['class_letter'] === $classesIdToInfo[$lesson['class_id']]->getLetter());
                }// Поиск по присутвию class_letter в GET запросе и совпадению class_letter в запросе и массиве занятий. [конец]
                if ($LessonMeetSearchCriteria) {
                    $lessonObj = new LessonClass();
                    $lessonObj->setId($lesson['id']);
                    $lessonObj->setItem($itemsIdToInfo[$lesson['item_id']]);
                    $lessonObj->setClass($classesIdToInfo[$lesson['class_id']]);
                    $lessonObj->setDate($lesson['date']);
                    $lessonObj->setLessonDuration($lesson['lessonDuration']);
                    $lessonObj->setTeacher($teachersIdToInfo[$lesson['teacher_id']]);

                    $foundLessons[] = $lessonObj;
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