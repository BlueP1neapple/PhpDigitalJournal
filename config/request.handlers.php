<?php

use JoJoBizzareCoders\DigitalJournal\Controller;



return [
    '/lesson'  => Controller\GetLessonCollectionController::class,

    '/assessmentReport' => Controller\GetAssessmentReportCollectionController::class,

    '/studentReport' => Controller\GetAssessmentReportCollectionController::class,

    '/journalAdministrationController' => Controller\JournalAdministrationController::class,

    '/login' =>Controller\LoginController::class
];