<?php

    use JoJoBizzareCoders\DigitalJournal\Controller\FoundAssessmentReport;
    use JoJoBizzareCoders\DigitalJournal\Controller\FoundLesson;
    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\FileLogger\Logger;
    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\LoggerInterface;
    use JoJoBizzareCoders\DigitalJournal\Infrastructure\View\DefaultRender;
    use JoJoBizzareCoders\DigitalJournal\Infrastructure\View\RenderInterface;

    return[
    'instances'=>[
        'handlers' => require __DIR__ . '/../request.handlers.php',
        'appConfig' => include __DIR__ . '/config.php',
    ],
    'services'=>[
        FoundAssessmentReport::class=>[
            'args'=>[
                'logger'=>LoggerInterface::class,
                'pathToItems'=>'pathToItems',
                'pathToTeachers'=>'pathToTeachers',
                'pathToClasses'=>'pathToClasses',
                'pathToStudents'=>'pathToStudents',
                'pathToParents'=>'pathToParents',
                'pathToLesson'=>'pathToLesson',
                'pathToAssessmentReport'=>'pathToAssessmentReport'
            ]
        ],
        FoundLesson::class=>[
            'args'=>[
                'logger'=>LoggerInterface::class,
                'pathToItems'=>'pathToItems',
                'pathToTeachers'=>'pathToTeachers',
                'pathToClasses'=>'pathToClasses',
                'pathToLesson'=>'pathToLesson'
            ]
        ],
        LoggerInterface::class=>[
            'class' => Logger::class,
            'args' => [
                'pathToFile' => 'pathToLogFile'
            ]
        ],
        RenderInterface::class => [
            'class' => DefaultRender::class
        ],//TODO дописать конфиг, описать сервис Роутер Интерфейс
    ]
]