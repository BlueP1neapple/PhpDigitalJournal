<?php

namespace JoJoBizzareCoders\DigitalJournal\ConsoleCommand;

use JoJoBizzareCoders\DigitalJournal\Infrastructure\Console\CommandInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Console\Output\OutputInterface;
use JoJoBizzareCoders\DigitalJournal\Service\SearchAssessmentReportService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\AssessmentReportDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\ParentDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\SearchReportAssessmentCriteria;
use JsonException;

/**
 * Поиск оценок в консольном приложении
 */
class FindAssessmentReport implements CommandInterface
{
    /**
     * Компонент отвечающий за вывод данных через консоль
     *
     * @var OutputInterface
     */
    private OutputInterface $output;

    /**
     * Сервис поиска оценок
     *
     * @var SearchAssessmentReportService
     */
    private SearchAssessmentReportService $searchAssessmentReportService;


    /**
     * Конструктор Поиска оценок в консольном приложении
     *
     * @param OutputInterface $output - Компонент отвечающий за вывод данных через консоль
     * @param SearchAssessmentReportService $searchAssessmentReportService - сервис поиска оценок
     */
    public function __construct(OutputInterface $output, SearchAssessmentReportService $searchAssessmentReportService)
    {
        $this->output = $output;
        $this->searchAssessmentReportService = $searchAssessmentReportService;
    }


    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function __invoke(array $params): void
    {
        $SearchAssessmentReportCollectionCriteria = new SearchReportAssessmentCriteria();
        $SearchAssessmentReportCollectionCriteria->setItemName($params['item_name'] ?? null);
        $SearchAssessmentReportCollectionCriteria->setItemDescription($params['item_description'] ?? null);
        $SearchAssessmentReportCollectionCriteria->setId($params['id'] ?? null);
        $SearchAssessmentReportCollectionCriteria->setLessonDate($params['lesson_date'] ?? null);
        $SearchAssessmentReportCollectionCriteria->setStudentSurname($params['student_fio_surname'] ?? null);
        $SearchAssessmentReportCollectionCriteria->setStudentName($params['student_fio_name'] ?? null);
        $SearchAssessmentReportCollectionCriteria->setStudentPatronymic($params['student_fio_patronymic'] ?? null);
        $assessmentReportCollectionDto = $this->searchAssessmentReportService->search(
            $SearchAssessmentReportCollectionCriteria
        );
        $jsonData = $this->buildJsonData($assessmentReportCollectionDto);
        $this->output->print(
            json_encode(
                $jsonData,
                JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            )
        );
    }

    /**
     * Создание формата json из найденных оценок
     *
     * @param array $assessmentReportCollection - коллекция найденныз оценок
     * @return array
     */
    private function buildJsonData(array $assessmentReportCollection): array
    {
        $result = [];
        foreach ($assessmentReportCollection as $assessmentReport) {
            $result[] = $this->serializeAssessmentReport($assessmentReport);
        }
        return $result;
    }

    /**
     * Подготовка данных в формате Json
     *
     * @param AssessmentReportDto $reportDto - экземпляр наденной оценки
     * @return array
     */
    private function serializeAssessmentReport(AssessmentReportDto $reportDto): array
    {
        return [
            'id' => $reportDto->getId(),
            'lesson' => [
                'id' => $reportDto->getLesson()->getId(),
                'item' => [
                    'id' => $reportDto->getLesson()->getItem()->getId(),
                    'name' => $reportDto->getLesson()->getItem()->getName(),
                    'description' => $reportDto->getLesson()->getItem()->getDescription()
                ],
                'date' => $reportDto->getLesson()->getDate(),
                'lessonDuration' => $reportDto->getLesson()->getLessonDuration(),
                'teacher' => [
                    'id' => $reportDto->getLesson()->getTeacher()->getId(),
                    'fio' => [
                        'surname' => $reportDto->getLesson()->getTeacher()->getFio()[0]->getSurname(),
                        'name' => $reportDto->getLesson()->getTeacher()->getFio()[0]->getName(),
                        'patronymic' => $reportDto->getLesson()->getTeacher()->getFio()[0]->getPatronymic(),
                    ],
                    'dateOfBirth' => $reportDto->getLesson()->getTeacher()->getDateOfBirth(),
                    'phone' => $reportDto->getLesson()->getTeacher()->getPhone(),
                    'address' => [
                        'street' => $reportDto->getLesson()->getTeacher()->getAddress()[0]->getStreet(),
                        'home' => $reportDto->getLesson()->getTeacher()->getAddress()[0]->getHome(),
                        'apartment' => $reportDto->getLesson()->getTeacher()->getAddress()[0]->getApartment(),
                    ],
                    'item' => [
                        'id' => $reportDto->getLesson()->getItem()->getId(),
                        'name' => $reportDto->getLesson()->getItem()->getName(),
                        'description' => $reportDto->getLesson()->getItem()->getDescription()
                    ],
                    'cabinet' => $reportDto->getLesson()->getTeacher()->getCabinet(),
                    'email' => $reportDto->getLesson()->getTeacher()->getEmail()
                ],
                'class' => [
                    'id' => $reportDto->getLesson()->getClass()->getId(),
                    'number' => $reportDto->getLesson()->getClass()->getNumber(),
                    'letter' => $reportDto->getLesson()->getClass()->getLetter()
                ]
            ],
            'student' => [
                'id' => $reportDto->getStudent()->getId(),
                'fio' => [
                    'surname' => $reportDto->getStudent()->getFio()[0]->getSurname(),
                    'name' => $reportDto->getStudent()->getFio()[0]->getName(),
                    'patronymic' => $reportDto->getStudent()->getFio()[0]->getPatronymic()
                ],
                'dateOfBirth' => $reportDto->getStudent()->getDateOfBirth(),
                'phone' => $reportDto->getStudent()->getPhone(),
                'address' => [
                    'street' => $reportDto->getStudent()->getAddress()[0]->getStreet(),
                    'home' => $reportDto->getStudent()->getAddress()[0]->getHome(),
                    'apartment' => $reportDto->getStudent()->getAddress()[0]->getApartment()
                ],
                'class' => [
                    'id' => $reportDto->getStudent()->getClass()->getId(),
                    'number' => $reportDto->getStudent()->getClass()->getNumber(),
                    'letter' => $reportDto->getStudent()->getClass()->getLetter()
                ],
                'parents' => $this->loadParents($reportDto->getStudent()->getParents()),
            ],
            'mark' => $reportDto->getMark()
        ];
    }

    private function loadParents(array $parentsList): array
    {
        if (0 === count($parentsList)) {
            return [];
        }

        $jsonData[] = array_values(array_map(static function (ParentDto $dto) {
            return[
              'id' => $dto->getId(),
              'email' => $dto->getEmail(),
              'placeOfWork' => $dto->getPlaceOfWork(),
              'phone' => $dto->getPhone(),
              'dateOfBirth' => $dto->getDateOfBirth(),
              'fio' => [
                  'street' => $dto->getFio()[0]->getSurname(),
                  'home' => $dto->getFio()[0]->getName(),
                  'apartment' => $dto->getFio()[0]->getPatronymic()
              ],
              'address' => [
                  'street' => $dto->getAddress()[0]->getStreet(),
                  'home' => $dto->getAddress()[0]->getHome(),
                  'apartment' => $dto->getAddress()[0]->getApartment()
              ]
            ];
        }, $parentsList));
        return $jsonData;
    }

    public static function getShortOptions(): string
    {
        return '';
    }

    public static function getLongOptions(): array
    {
        return [
            'id:',
            'lesson_id:',
            'student_id:',
            'mark:'
        ];
    }
}
