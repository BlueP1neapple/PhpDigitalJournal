<?php

namespace JoJoBizzareCoders\DigitalJournal\Infrastructure;


function getSearch(array $request, array $report, AppConfig $appConfig): bool
{
    $items = loadData(__DIR__ . '/../../data/item.json');
    $teachers = loadData(__DIR__ . '/../../data/teacher.json');
    $classes = loadData(__DIR__ . '/../../data/class.json');
    $lessons = loadData(__DIR__ . '/../../data/lesson.json');
    $reports = loadData(__DIR__ . '/../../data/assessmentReport.json');
    $students = loadData(__DIR__ . '/../../data/student.json');
    $parents = loadData(__DIR__ . '/../../data/parent.json');

    $graitHardcodeArray = [];

    $graitHardcodeArray['item'] = $items;
    $graitHardcodeArray['teacher'] = $teachers;
    $graitHardcodeArray['class'] = $classes;
    $graitHardcodeArray['lesson'] = $lessons;
    $graitHardcodeArray['report'] = $reports;
    $graitHardcodeArray['student'] = $students;
    $graitHardcodeArray['parent'] = $parents;

    $searchingKey = array_keys($request);

    $splitKey = explode('_', $searchingKey[0]);

    if (array_key_exists($splitKey[0], $graitHardcodeArray)) {
        foreach ($graitHardcodeArray[$splitKey[0]] as $currentValue) {
            if (array_key_exists($splitKey[1], $currentValue)) {
                    return true;
            }
        }
    }
    return false;
}


























