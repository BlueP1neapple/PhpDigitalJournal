<?php

    use JoJoBizzareCoders\DigitalJournal\ConsoleCommand\FindAssessmentReport;
    use JoJoBizzareCoders\DigitalJournal\ConsoleCommand\FindLesson;
use JoJoBizzareCoders\DigitalJournal\ConsoleCommand\HashStr;

return[
        'find-lesson'=>FindLesson::class,
        'find-assessmentReport'=>FindAssessmentReport::class,
        'hash'=> HashStr::class
    ];