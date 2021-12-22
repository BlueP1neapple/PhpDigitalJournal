<?php
namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger;
use Exception;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\AppConfig;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\NullLogger\Logger as NullLogger;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\EchoLogger\Logger as EchoLogger;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\FileLogger\Logger as FileLogger;


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
                $logger=new FileLogger($appConfig->getPathToLogFile());
            }elseif('echoLogger'===$loggerType){
                $logger=new EchoLogger();
            }elseif('nullLogger'===$loggerType){
                $logger=new NullLogger();
            }else{
                throw new Exception('Unknown Logger Type');
            }
            return $logger;
        }
    }