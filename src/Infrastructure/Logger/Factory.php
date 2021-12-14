<?php
namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger;
use Exception;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\AppConfig;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\NullLogger\Logger;


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
                $logger=new FileLogger\Logger($appConfig->getPathToLogFile());
            }elseif('echoLogger'===$loggerType){
                $logger=new EchoLogger\Logger();
            }elseif('nullLogger'===$loggerType){
                $logger=new Logger();
            }else{
                throw new Exception('Unknown Logger Type');
            }
            return $logger;
        }
    }