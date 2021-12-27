<?php

use JoJoBizzareCoders\DigitalJournal\Controller;



return [
    '/lesson'  => Controller\GetLessonCollectionController::class,

    '/assessmentReport' => Controller\GetReportCollectionController::class,

    '/studentReport' => Controller\GetReportCollectionController::class
];