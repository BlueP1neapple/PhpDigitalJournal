<?php

use JoJoBizzareCoders\DigitalJournal\Controller;



return [
    '/lesson'  => Controller\GetLessonCollectionController::class,

    '/assessmentReport' => Controller\GetAssessmentReportCollectionController::class,

    '/studentReport' => Controller\GetAssessmentReportCollectionController::class
];