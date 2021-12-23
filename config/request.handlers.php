<?php

use JoJoBizzareCoders\DigitalJournal\Controller;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\AppConfig;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerRequest;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\LoggerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\HttpResponse;


return [
    '/lesson'  => static function (
        ServerRequest $serverRequest,
        LoggerInterface $logger,
        AppConfig $appConfig
    ): HttpResponse {
        return (new Controller\FoundLesson($logger, $appConfig))($serverRequest);
    },
    '/assessmentReport' => static function (
        ServerRequest $serverRequest,
        LoggerInterface $logger,
        AppConfig $appConfig
    ): HttpResponse {
        return (new Controller\FoundAssessmentReport($logger, $appConfig))($serverRequest);
    },

    '/studentReport' => static function (
        ServerRequest $serverRequest,
        LoggerInterface $logger,
        AppConfig $appConfig
    ): HttpResponse {
        return (new Controller\FoundAssessmentReport($logger, $appConfig))($serverRequest);
    },
];