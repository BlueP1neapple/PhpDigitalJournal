<?php

use JoJoBizzareCoders\DigitalJournal\ConsoleCommand;

return [
    '/lesson'  => ConsoleCommand\GetLesson::class,

    '/assessmentReport' => ConsoleCommand\GetReport::class,

    '/studentReport' => ConsoleCommand\GetReport::class
];