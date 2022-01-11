<?php



return [
   '/^.*?\/lesson\/(?<___ID___>[0-9]+).*$/'      => JoJoBizzareCoders\DigitalJournal\Controller\GetLessonController::class,
   '/^.*?\/assessmentReport\/(?<___ID___>[0-9]+).*$/'     => JoJoBizzareCoders\DigitalJournal\Controller\GetReportController::class,

];
