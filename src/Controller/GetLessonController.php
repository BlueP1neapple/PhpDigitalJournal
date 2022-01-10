<?php

namespace JoJoBizzareCoders\DigitalJournal\Controller;

class GetLessonController extends GetLessonCollectionController
{
    /**
     * Создаёт http ответ
     * @param array $foundLesson
     * @return int
     */
    protected function buildHttpCode(array $foundLesson): int
    {
        return 0 === count($foundLesson) ? 404 : 200;
    }

    /**
     * Создаёт результат
     * @param array $foundLesson
     * @return array|false|mixed|string[]
     */
    protected function buildResult(array $foundLesson): array
    {
        return 1 === count($foundLesson) ? current($foundLesson) : [    'status' => 'fail',
            'message' => 'entity not found',];
    }

}