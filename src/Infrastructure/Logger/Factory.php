<?php
namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger;
use Exception;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\AppConfig;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\NullLogger\Logger;
    require_once __DIR__.'/LoggerInterface.php';
    require_once __DIR__.'/../AppConfig.php';

    /**
     * Фабрика по созданию логгеров
     */
    class Factory
    {
        /**
         * Реализация логики создания логеров
         *
         * @param AppConfig $appConfig - конфиг приложения
         * @throws Exception
         */
        public static function create(AppConfig $appConfig):LoggerInterface
        {
            $loggerType=$appConfig->getLoggerType();
            if('fileLogger'===$loggerType){
                require_once __DIR__.'/FileLogger/Logger.php';
                $logger=new \JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\FileLogger\Logger($appConfig->getPathToLogFile());
            }elseif('echoLogger'===$loggerType){
                require_once __DIR__.'/EchoLogger/Logger.php';
                $logger=new \JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\EchoLogger\Logger();
            }elseif('nullLogger'===$loggerType){
                require_once __DIR__.'/NullLogger/Logger.php';
                $logger=new Logger();
            }else{
                throw new Exception('Unknown Logger Type');
            }
            return $logger;
        }
    }