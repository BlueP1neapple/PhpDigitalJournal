<?php

    namespace JoJoBizzareCoders\DigitalJournal\Controller;
    // Подключаемы функции
    use JoJoBizzareCoders\DigitalJournal\Entity\ClassClass;
    use JoJoBizzareCoders\DigitalJournal\Entity\ItemClass;
    use JoJoBizzareCoders\DigitalJournal\Entity\LessonClass;
    use JoJoBizzareCoders\DigitalJournal\Entity\TeacherUserClass;
    use JoJoBizzareCoders\DigitalJournal\Exception\InvalidDataStructureException;
    use JoJoBizzareCoders\DigitalJournal\Infrastructure\AppConfig;
    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\HttpResponse;
    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerRequest;
    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerResponseFactory;
    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\LoggerInterface;

//use function JoJoBizzareCoders\DigitalJournal\Infrastructure\getSearch;
    use function JoJoBizzareCoders\DigitalJournal\Infrastructure\loadData;
    use function JoJoBizzareCoders\DigitalJournal\Infrastructure\paramTypeValidation;

    require_once __DIR__ . '/../Infrastructure/application.php';
    require_once __DIR__ . '/../Infrastructure/antiIf.php';

    /**
     * Поиск по уроку
     * @param ServerRequest $request - http запрос
     * @param LoggerInterface $logger - название функции логирования
     * @param AppConfig $appConfig - Конфигурация приложения
     * @return HttpResponse - http ответ
     * @throws InvalidDataStructureException
     */
    return static function (ServerRequest $request, LoggerInterface $logger, AppConfig $appConfig): HttpResponse {
        // Загрузка данных из json
        $items = loadData($appConfig->getPathToItems());
        $teachers = loadData($appConfig->getPathToTeachers());
        $classes = loadData($appConfig->getPathToClasses());
        $lessons = loadData($appConfig->getPathToLesson());
        $logger->log('dispatch "lesson" url');
        $paramValidations = [
            'item_name' => 'Incorrect item name',
            'item_description' => 'Incorrect item description',
            'lesson_date' => 'Incorrect date',
            'teacher_fio' => 'Incorrect teacher fio',
            'teacher_cabinet' => 'Incorrect teacher cabinet',
            'class_number' => 'Incorrect class number',
            'class_letter' => 'Incorrect class letter',
        ];
        $requestParams = $request->getQueryParams();
        if (null === ($result = paramTypeValidation($paramValidations, $requestParams))) {
            // Хэшмапирование
            $foundLessons = [];
            $itemsIdToInfo = [];
            $teachersIdToInfo = [];
            $classesIdToInfo = [];

            foreach ($items as $item) {
                $itemsObj = ItemClass::createFromArray($item);
                $itemsIdToInfo[$itemsObj->getId()] = $itemsObj;
            }

            foreach ($teachers as $teacher) {
                $teacher['idItem'] = $itemsIdToInfo[$teacher['idItem']];
                $teachersObj = TeacherUserClass::createFromArray($teacher);
                $teachersIdToInfo[$teachersObj->getId()] = $teachersObj;
            }

            foreach ($classes as $class) {
                $classesObj = ClassClass::createFromArray($class);
                $classesIdToInfo[$classesObj->getId()] = $classesObj;
            }

            // Поиск нужного занятия
            foreach ($lessons as $lesson) // Цикл по все занятиям. [начало]
            {
                //$LessonMeetSearchCriteria = getSearch($request, $lesson, $appConfig);
                $LessonMeetSearchCriteria = null;
                if (array_key_exists(
                    'item_name',
                    $requestParams
                )) // Поиск по присутвию item_name в GET запросе и совпадению item_name в запросе и массиве занятий. [начало]
                {
                    $LessonMeetSearchCriteria = ($requestParams['item_name'] === $itemsIdToInfo[$lesson['item_id']]->getName(
                        ));
                }// Поиск по присутвию item_name в GET запросе и совпадению item_name в запросе и массиве занятий. [конец]
                if (array_key_exists(
                    'item_description',
                    $requestParams
                )) // Поиск по присутвию item_description в GET запросе и совпадению item_description в запросе и массиве занятий. [начало]
                {
                    $LessonMeetSearchCriteria = ($requestParams['item_description'] === $itemsIdToInfo[$lesson['item_id']]->getDescription(
                        ));
                }// Поиск по присутвию item_description в GET запросе и совпадению item_description в запросе и массиве занятий. [конец]
                if (array_key_exists(
                    'lesson_date',
                    $requestParams
                )) // Поиск по присутвию date в GET запросе и совпадению date в запросе и массиве занятий. [начало]
                {
                    $LessonMeetSearchCriteria = ($requestParams['lesson_date'] === $lesson['date']);
                }// Поиск по присутвию date в GET запросе и совпадению date в запросе и массиве занятий. [конец]
                if (array_key_exists(
                    'teacher_fio',
                    $requestParams
                )) // Поиск по присутвию teacher_fio в GET запросе и совпадению teacher_fio в запросе и массиве занятий. [начало]
                {
                    $LessonMeetSearchCriteria = ($requestParams['teacher_fio'] === $teachersIdToInfo[$lesson['teacher_id']]->getFio(
                        ));
                }// Поиск по присутвию teacher_fio в GET запросе и совпадению teacher_fio в запросе и массиве занятий. [конец]
                if (array_key_exists(
                    'teacher_cabinet',
                    $requestParams
                )) // Поиск по присутвию teacher_cabinet в GET запросе и совпадению teacher_cabinet в запросе и массиве занятий. [начало]
                {
                    $LessonMeetSearchCriteria = ((int)$requestParams['teacher_cabinet'] === $teachersIdToInfo[$lesson['teacher_id']]->getCabinet(
                        ));
                }// Поиск по присутвию teacher_cabinet в GET запросе и совпадению teacher_cabinet в запросе и массиве занятий. [конец]
                if (array_key_exists(
                    'class_number',
                    $requestParams
                )) // Поиск по присутвию class_number в GET запросе и совпадению class_number в запросе и массиве занятий. [начало]
                {
                    $LessonMeetSearchCriteria = ((int)$requestParams['class_number'] === $classesIdToInfo[$lesson['class_id']]->getNumber(
                        ));
                }// Поиск по присутвию class_number в GET запросе и совпадению class_number в запросе и массиве занятий. [конец]
                if (array_key_exists(
                    'class_letter',
                    $requestParams
                )) // Поиск по присутвию class_letter в GET запросе и совпадению class_letter в запросе и массиве занятий. [начало]
                {
                    $LessonMeetSearchCriteria = ($requestParams['class_letter'] === $classesIdToInfo[$lesson['class_id']]->getLetter(
                        ));
                }// Поиск по присутвию class_letter в GET запросе и совпадению class_letter в запросе и массиве занятий. [конец]


                if ($LessonMeetSearchCriteria) { // Отбор найденных занятий
                    $lesson['item_id'] = $itemsIdToInfo[$lesson['item_id']];
                    $lesson['teacher_id'] = $teachersIdToInfo[$lesson['teacher_id']];
                    $lesson['class_id'] = $classesIdToInfo[$lesson['class_id']];
                    $foundLessons[] = LessonClass::createFromArray($lesson);
                }
            }  //Цикл по все занятиям. [конец]
            $logger->log('found lessons' . count($foundLessons));
            $result = [
                'httpCode' => 200,
                'result' => $foundLessons
            ];
        }
        return ServerResponseFactory::createJsonResponse($result['httpCode'], $result['result']);
    };