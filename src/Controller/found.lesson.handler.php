<?php
namespace JoJoBizzareCoders\DigitalJournal\Controller;

// Подключаемы функции
use JoJoBizzareCoders\DigitalJournal\Entity\ClassClass;
use JoJoBizzareCoders\DigitalJournal\Entity\ItemClass;
use JoJoBizzareCoders\DigitalJournal\Entity\LessonClass;
use JoJoBizzareCoders\DigitalJournal\Entity\TeacherUserClass;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\AppConfig;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\LoggerInterface;
use  JoJoBizzareCoders\DigitalJournal\Infrastructure\InvalidDataStructureException;

use function JoJoBizzareCoders\DigitalJournal\Infrastructure\getSearch;
use function JoJoBizzareCoders\DigitalJournal\Infrastructure\loadData;
use function JoJoBizzareCoders\DigitalJournal\Infrastructure\paramTypeValidation;
require_once __DIR__ . '/../Infrastructure/application.php';
require_once __DIR__ . '/../Infrastructure/antiIf.php';

/**
 * Поиск по уроку
 * @param array $request - массив содержащий параметры поиска
 * @param LoggerInterface $logger - название функции логирования
 * @param AppConfig $appConfig - Конфигурация приложения
 * @return array - результат поиска уроков
 * @throws InvalidDataStructureException
 */
    return static function (array $request, LoggerInterface $logger, AppConfig $appConfig): array {
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
        if (null === ($result = paramTypeValidation($paramValidations, $request))) {
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
                $LessonMeetSearchCriteria = getSearch($request, $lesson, $appConfig);
                if ($LessonMeetSearchCriteria) { // Отбор найденных занятий
                    $lesson['item_id'] = $itemsIdToInfo[$lesson['item_id']];
                    $lesson['teacher_id'] = $teachersIdToInfo[$lesson['teacher_id']];
                    $lesson['class_id'] = $classesIdToInfo[$lesson['class_id']];
                    $foundLessons[] = LessonClass::createFromArray($lesson);
                }
            }  //Цикл по все занятиям. [конец]
            $logger->log('found lessons'.count($foundLessons));
            $result = [
                'httpCode' => 200,
                'result' => $foundLessons
            ];
        }
        return $result;
    };