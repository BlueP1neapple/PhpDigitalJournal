<?php

namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger;

use DateTimeImmutable;
use JoJoBizzareCoders\DigitalJournal\Exception\RuntimeException;
use Throwable;

class Logger implements LoggerInterface
{
    /**
     * Адаптер для записи в определённое хранилище
     *
     * @var AdapterInterface
     */
    private AdapterInterface $adapter;

    /**
     * Резрешённые уровни логгирования
     */
    private const ALLOWED_LEVEL = [
        LogLevel::EMERGENCY=> LogLevel::EMERGENCY,
        LogLevel::ALERT=> LogLevel::ALERT,
        LogLevel::CRITICAL=> LogLevel::CRITICAL,
        LogLevel::ERROR=> LogLevel::ERROR,
        LogLevel::WARNING=> LogLevel::WARNING,
        LogLevel::NOTICE=> LogLevel::NOTICE,
        LogLevel::INFO=> LogLevel::INFO,
        LogLevel::DEBUG=> LogLevel::DEBUG,
    ];
    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @inheritDoc
     */
    public function emergency(string $massage, array $context = []): void
    {
        $this->log(LogLevel::EMERGENCY, $massage, $context);
    }

    /**
     * @inheritDoc
     */
    public function alert(string $massage, array $context = []): void
    {
        $this->log(LogLevel::ALERT, $massage, $context);
    }

    /**
     * @inheritDoc
     */
    public function critical(string $massage, array $context = []): void
    {
        $this->log(LogLevel::CRITICAL, $massage, $context);
    }

    /**
     * @inheritDoc
     */
    public function error(string $massage, array $context = []): void
    {
        $this->log(LogLevel::ERROR, $massage, $context);
    }

    /**
     * @inheritDoc
     */
    public function warning(string $massage, array $context = []): void
    {
        $this->log(LogLevel::WARNING, $massage, $context);
    }

    /**
     * @inheritDoc
     */
    public function notice(string $massage, array $context = []): void
    {
        $this->log(LogLevel::NOTICE, $massage, $context);
    }

    /**
     * @inheritDoc
     */
    public function info(string $massage, array $context = []): void
    {
        $this->log(LogLevel::INFO, $massage, $context);
    }

    /**
     * @inheritDoc
     */
    public function debug(string $massage, array $context = []): void
    {
        $this->log(LogLevel::DEBUG, $massage, $context);
    }

    /**
     * @inheritDoc
     */
    public function log(string $level, string $message, array $context = []): void
    {
        try{
            $this->validateLevel($level);
            $formatMsg = $this->formatMsg($message, $context);
            $this->adapter->write($level, $formatMsg);
        } catch (Throwable $e){

        }


    }

    /**
     * @param string $message
     * @param array $context
     * @return string
     */
    private function formatMsg(string $message, array $context):string
    {
        $date = $this->formatData();
        $ip = $this->formatIp();
        $contextStr = $this->formatContext($context);



        return $ip . ' - ' . '[' . $date . '] '. $message . ' ' . $contextStr;
    }

    /**
     * Валидация корректности уровня логгирования
     *
     * @param string $level
     */
    private function validateLevel(string $level):void
    {
        if(false === array_key_exists($level, self::ALLOWED_LEVEL)){
            throw new RuntimeException('Не поддерживаемый уровень логгирования');
        }
    }

    /**
     * Дата и время события
     *
     * @return string
     */
    private function formatData():string
    {
        return (new DateTimeImmutable())->format('d/M/Y:H:i:s O');
    }

    /**
     * Возвращает строку с инфо о клиенте
     *
     * @return string
     */
    private function formatIp():string
    {
        if(isset($_SERVER['REMOTE_ADDR'])){
            $ip = $_SERVER['REMOTE_ADDR'];
        }elseif ('cli' === PHP_SAPI){
            $ip = 'console';
        }else{
            $ip = 'unknown';
        }
        return $ip;
    }

    private function formatContext(array $context):string
    {
        if(count($context) > 0){
            $contextStr = print_r($context, true);
        }else{
            $contextStr = '';
        }

        return $contextStr;
    }


}