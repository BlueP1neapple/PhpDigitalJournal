<?php

namespace JoJoBizzareCoders\DigitalJournal\ConsoleCommand;

use JoJoBizzareCoders\DigitalJournal\Infrastructure\Console\CommandInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Console\Output\OutputInterface;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\LessonDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\SearchLessonServiceCriteria;
use JsonException;

/**
 * Поиск занятий в консольном приложении
 */
final class FindLesson implements CommandInterface
{
    /**
     * Компонент отвечающий за вывод данных через консоль
     *
     * @var OutputInterface
     */
    private OutputInterface $output;

    /**
     * Сервис поиска занятий
     *
     * @var SearchLessonService
     */
    private SearchLessonService $searchLessonService;


    /**
     * Конструктор Поиска занятий в консольном приложении
     *
     * @param OutputInterface $output - Компонент отвечающий за вывод данных через консоль
     */
    public function __construct(OutputInterface $output, SearchLessonService $searchLessonService)
    {
        $this->output = $output;
        $this->searchLessonService = $searchLessonService;
    }

    /**
     * @inheritDoc
     * @param array $params
     * @throws JsonException
     */
    public function __invoke(array $params): void
    {
        $searchLessonServiceCriteria = new SearchLessonServiceCriteria();
        $searchLessonServiceCriteria->setId($params['id'] ?? null);
        $searchLessonServiceCriteria->setTeacherSurname($params['teacher_fio_surname'] ?? null);
        $searchLessonServiceCriteria->setTeacherName($params['teacher_fio_name'] ?? null);
        $searchLessonServiceCriteria->setTeacherPatronymic($params['teacher_fio_patronymic'] ?? null);
        $searchLessonServiceCriteria->setClassNumber($params['class_number'] ?? null);
        $searchLessonServiceCriteria->setClassLetter($params['class_letter'] ?? null);
        $searchLessonServiceCriteria->setTeacherCabinet($params['teacher_cabinet'] ?? null);
        $searchLessonServiceCriteria->setDate($params['lesson_date'] ?? null);
        $searchLessonServiceCriteria->setItemName($params['item_name'] ?? null);
        $searchLessonServiceCriteria->setItemDescription($params['item_description'] ?? null);
        $lessonsDto = $this->searchLessonService->search($searchLessonServiceCriteria);
        $jsonData = $this->buildJsonData($lessonsDto);
        $this->output->print(json_encode(
        $jsonData,
                JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        ));
    }

    /**
     * Создаём формат Json из найденных занятий
     *
     * @param array $foundLessons - найденные занятия
     * @return array
     */
    private function buildJsonData(array $foundLessons):array
    {
        $result=[];
        foreach ($foundLessons as $foundLesson){
            $result[]=$this->serializeLesson($foundLesson);
        }
        return $result;
    }

    /**
     * Подготовка формата json с информацией о наёденном занятии
     *
     * @param LessonDto $foundLesson - найденное занятие
     * @return array
     */
    private function serializeLesson(LessonDto $foundLesson):array
    {
        return [
            'id' =>$foundLesson->getId(),
            'item'=>[
                'id'=>$foundLesson->getItem()->getId(),
                'name'=>$foundLesson->getItem()->getName(),
                'description'=>$foundLesson->getItem()->getDescription()
            ],
            'date'=>$foundLesson->getDate(),
            'lessonDuration'=>$foundLesson->getDate(),
            'teacher'=>[
                'id'=>$foundLesson->getTeacher()->getId(),
                'fio'=>[
                    'surname'=>$foundLesson->getTeacher()->getFio()->getSurname(),
                    'name'=>$foundLesson->getTeacher()->getFio()->getName(),
                    'patronymic'=>$foundLesson->getTeacher()->getFio()->getPatronymic()
                ],
                'dateOfBirth'=>$foundLesson->getTeacher()->getDateOfBirth(),
                'phone'=>$foundLesson->getTeacher()->getPhone(),
                'address'=>[
                    'street'=>$foundLesson->getTeacher()->getAddress()->getStreet(),
                    'home'=>$foundLesson->getTeacher()->getAddress()->getHome(),
                    'apartment'=>$foundLesson->getTeacher()->getAddress()->getApartment()
                ],
                'item'=>[
                    'id'=>$foundLesson->getTeacher()->getItem()->getId(),
                    'name'=>$foundLesson->getTeacher()->getItem()->getName(),
                    'description'=>$foundLesson->getTeacher()->getItem()->getDescription()
                ],
                'cabinet'=>$foundLesson->getTeacher()->getCabinet(),
                'email'=>$foundLesson->getTeacher()->getEmail()
            ],
            'class'=>[
                'id'=>$foundLesson->getClass()->getId(),
                'number'=>$foundLesson->getClass()->getNumber(),
                'letter'=>$foundLesson->getClass()->getLetter()
            ]
        ];
    }

    public static function getShortOption(): string
    {
        return '';
    }

    public static function getLongOption(): array
    {
        return [
            'id:',
            'item_id:',
            'date:',
            'lessonDuration:',
            'teacher_id:',
            'class_id:'
        ];
    }
}