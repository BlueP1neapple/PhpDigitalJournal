<?php
namespace JoJoBizzareCoders\DigitalJournal\Controller;
    use JoJoBizzareCoders\DigitalJournal\Entity\ClassClass;
    use JoJoBizzareCoders\DigitalJournal\Entity\ItemClass;
    use JoJoBizzareCoders\DigitalJournal\Entity\LessonClass;
    use JoJoBizzareCoders\DigitalJournal\Entity\ParentUserClass;
    use JoJoBizzareCoders\DigitalJournal\Entity\ReportClass;
    use JoJoBizzareCoders\DigitalJournal\Entity\StudentUserClass;
    use JoJoBizzareCoders\DigitalJournal\Entity\TeacherUserClass;
    use JoJoBizzareCoders\DigitalJournal\Exception\InvalidDataStructureException;
    use JoJoBizzareCoders\DigitalJournal\Infrastructure\AppConfig;
    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\LoggerInterface;


    //use function JoJoBizzareCoders\DigitalJournal\Infrastructure\getSearch;
    use function JoJoBizzareCoders\DigitalJournal\Infrastructure\loadData;
    use function JoJoBizzareCoders\DigitalJournal\Infrastructure\paramTypeValidation;

    require_once __DIR__ . '/../Infrastructure/application.php';
    require_once __DIR__ . '/../Infrastructure/antiIf.php';

    /**
     * Поиск по оценке
     * @param array $request - массив содержащий параметры поиска
     * @param LoggerInterface $logger - название функции логирования
     * @param AppConfig $appConfig - конфигурация приложения
     * @return array - результат поиска оценок
     * @throws InvalidDataStructureException
     */
    return static function (array $request, LoggerInterface $logger, AppConfig $appConfig): array {
        $items = loadData($appConfig->getPathToItems());
        $teachers = loadData($appConfig->getPathToTeachers());
        $classes = loadData($appConfig->getPathToClasses());
        $lessons = loadData($appConfig->getPathToLesson());
        $reports = loadData($appConfig->getPathToAssessmentReport());
        $students = loadData($appConfig->getPathToStudents());
        $parents = loadData($appConfig->getPathToParents());
        $logger->log('assessmentReport" url');
        $paramValidations = [
            'item_name' => 'Incorrect item name',
            'item_description' => 'Incorrect item description',
            'lesson_date' => 'Incorrect lesson date',
            'student_fio' => 'Incorrect student fio',
        ];
        if (null === ($result = paramTypeValidation($paramValidations, $request))) {
            //Хэшмапирование
            $foundReport = [];
            $itemsIdToInfo = [];
            $teachersIdToInfo = [];
            $classesIdToInfo = [];
            $lessonIdToInfo = [];
            $studentIdToInfo = [];
            $parentIdToInfo = [];

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

            foreach ($lessons as $lesson) {
                $lesson['item_id'] = $itemsIdToInfo[$lesson['item_id']];
                $lesson['teacher_id'] = $teachersIdToInfo[$lesson['teacher_id']];
                $lesson['class_id'] = $classesIdToInfo[$lesson['class_id']];
                $lessonsObj = LessonClass::createFromArray($lesson);
                $lessonIdToInfo[$lessonsObj->getId()] = $lessonsObj;
            }

            foreach ($parents as $parent) {
                $parentsObj = ParentUserClass::createFromArray($parent);
                $parentIdToInfo[$parentsObj->getId()] = $parentsObj;
            }

            foreach ($students as $student) {
                $student['class_id'] = $classesIdToInfo[$student['class_id']];
                $student['parent_id'] = $parentIdToInfo[$student['parent_id']];
                $studentsObj = StudentUserClass::createFromArray($student);
                $studentIdToInfo[$studentsObj->getId()] = $studentsObj;
            }
            // Поиск оценок
            foreach ($reports as $report) {
                //$ReportMeetSearchCriteria = getSearch($request, $report, $appConfig);
                $ReportMeetSearchCriteria=null;
                if (array_key_exists(
                    'item_name',
                    $request
                )) // Поиск по присутвию item_name в GET запросе и совпадению item_name в запросе и массиве оценок. [начало]
                {
                    $ReportMeetSearchCriteria = ($request['item_name'] === $itemsIdToInfo[$lessonIdToInfo[$report['lesson_id']]->getItem(
                        )->getId()]->getName());
                }// Поиск по присутвию item_name в GET запросе и совпадению item_name в запросе и массиве оценок. [конец]
                if (array_key_exists('item_description', $request)) {
                    $ReportMeetSearchCriteria = ($request['item_description'] === $itemsIdToInfo[$lessonIdToInfo[$report['lesson_id']]->getItem(
                        )->getId()]->getDescription());
                }
                if (array_key_exists('lesson_date', $request)) {
                    $ReportMeetSearchCriteria = ($request['lesson_date'] === $lessonIdToInfo[$report['lesson_id']]->getDate(
                        ));
                }
                if (array_key_exists('student_fio', $request)) {
                    $ReportMeetSearchCriteria = ($request['student_fio'] === $studentIdToInfo[$report['student_id']]->getFio(
                        ));
                }


                if ($ReportMeetSearchCriteria) { // Отбор наёденных оценок
                    $report['lesson_id'] = $lessonIdToInfo[$report['lesson_id']];
                    $report['student_id'] = $studentIdToInfo[$report['student_id']];
                    $foundReport[] = ReportClass::createFromArray($report);
                }
            }//Цикл по оценкам [конец]
            $logger->log('found Report'.count($foundReport));
            $result = [
                'httpCode' => 200,
                'result' => $foundReport
            ];
        }
        return $result;
    };