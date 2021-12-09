<?php





function getSearch(array $request, array $report, AppConfig $appConfig): bool{

    $items = loadData(__DIR__ . '/../JSON/item.json');
    $teachers = loadData(__DIR__ . '/../JSON/teacher.json');
    $classes = loadData(__DIR__ . '/../JSON/class.json');
    $lessons = loadData(__DIR__ . '/../JSON/lesson.json');
    $reports = loadData(__DIR__ . '/../JSON/assessmentReport.json');
    $students = loadData(__DIR__ . '/../JSON/student.json');
    $parents = loadData(__DIR__ . '/../JSON/parent.json');

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

    if(array_key_exists($splitKey[0], $graitHardcodeArray))
    {
        foreach ($graitHardcodeArray[$splitKey[0]] as $currentValue)
        {

            if(array_key_exists($splitKey[1],$currentValue)){
                if ($request[$searchingKey[0]] == $currentValue[$splitKey[1]]){
                    return true;
                }
            }
        }
    }
    return false;
}


























