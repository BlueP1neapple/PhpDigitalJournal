<?php

namespace JoJoBizzareCoders\DigitalJournal\Infrastructure;

use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\LoggerInterface;
use JoJoBizzareCoders\DigitalJournal\Exception;
use Throwable;
use UnexpectedValueException;
/**
 * Ядро приложения
 */
final class App
{
    /**
     * Обработчик запросов
     *
     * @var array
     */
    private array $handlers;

    /**
     * Фабика для создания Логгеров
     *
     * @var callable
     */
    private $loggerFactory;

    /**
     * Фабрика для создания конфига приложения
     *
     * @var callable
     */
    private $appConfigFactory;

    /**
     * Конфиг приложения
     *
     * @var AppConfig | null
     */
    private ?AppConfig $appConfig = null;

    /**
     * компонент отвечающий за логгирование
     *
     * @var LoggerInterface|null
     */
    private ?LoggerInterface $logger = null;

    /**
     * Конструктор ядра приложения
     *
     * @param array $handlers - Обработчик запросов
     * @param callable $loggerFactory - Фабика для создания Логгеров
     * @param callable $appConfigFactory - Фабрика для создания конфига приложения
     */
    public function __construct(array $handlers, callable $loggerFactory, callable $appConfigFactory)
    {
        $this->handlers = $handlers;
        $this->loggerFactory = $loggerFactory;
        $this->appConfigFactory = $appConfigFactory;
        $this->initiateErrorHandler();
    }

    /**
     * Иициалирую обработку ошибок
     *
     * @return void
     */
    private function initiateErrorHandler(): void
    {
        set_error_handler(static function (int $errNo, string $errStr) {
            throw new Exception\RuntimeException($errStr);
        });
    }

    /**
     * Возвращает конфиг приложения
     *
     * @return AppConfig
     */
    private function getAppConfig(): AppConfig
    {
        if (null === $this->appConfig) {

            try{
                $appConfig = call_user_func($this->appConfigFactory);
            }catch (Throwable $e){
                throw new Exception\ErrorCreateAppConfigException($e->getMessage(),$e->getCode(),$e);
            }

            if (!($appConfig instanceof AppConfig)) {
                throw new Exception\ErrorCreateAppConfigException('incorrect application config');
            }
            $this->appConfig = $appConfig;
        }
        return $this->appConfig;
    }

    /**
     * Возвращает компонент отвечающий за логгирование
     *
     * @return LoggerInterface
     */
    private function getLogger(): LoggerInterface
    {
        if (null === $this->logger) {
            $logger = call_user_func($this->loggerFactory, $this->getAppConfig());
            if (!($logger instanceof LoggerInterface)) {
                throw new UnexpectedValueException('incorrect logger');
            }
            $this->logger = $logger;
        }
        return $this->logger;
    }

    /**
     * Извлекает параметры из url
     *
     * @param string $requestUri - данные запроса uri
     * @return array - параметры запроса
     */
    private function extractQueryParams(string $requestUri): array
    {
        $requestParams = [];
        $requestQuery = parse_url($requestUri, PHP_URL_QUERY);
        parse_str($requestQuery, $requestParams);
        return $requestParams;
    }

    /**
     *
     *
     * @param string $requestUri
     * @return array
     */
    public function dispatch(string $requestUri): array
    {
        $appConfig=null;
        try {
            $appConfig = $this->getAppConfig();
            $logger = $this->getLogger();

            $urlPath = parse_url($requestUri, PHP_URL_PATH);
            $logger->log('Url request received: ' . $requestUri);

            if (array_key_exists($urlPath, $this->handlers)) {
                $requestParams = $this->extractQueryParams($requestUri);
                $result = call_user_func($this->handlers[$urlPath], $requestParams, $logger, $appConfig);
            } else {
                $result = [
                    'httpCode' => 404,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'unsupported request'
                    ]
                ];
            }
        } catch (Exception\InvalidDataStructureException $e) {
            $result = [
                'httpCode' => 503,
                'result' => [
                    'status' => 'fail',
                    'message' => $e->getMessage(),
                ]
            ];
        } catch (Throwable $e) {
            $errMsg = ($appConfig instanceof AppConfig && !$appConfig->isHideErrorMessage())
            || $e instanceof Exception\ErrorCreateAppConfigException
                ? $e->getMessage()
                : 'system error';

            try{
                $this->getLogger()->log($e->getMessage());
            }catch (Throwable $e1){}

            $result = [
                'httpCode' => 500,
                'result' => [
                    'status' => 'fail',
                    'message' => $errMsg,
                ]
            ];
        }
        return $result;
    }
}