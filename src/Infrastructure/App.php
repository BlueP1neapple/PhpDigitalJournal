<?php

namespace JoJoBizzareCoders\DigitalJournal\Infrastructure;


use JoJoBizzareCoders\DigitalJournal\Exception\UnexpectedValueException;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\HttpResponse;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerRequest;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerResponseFactory;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\LoggerInterface;
use JoJoBizzareCoders\DigitalJournal\Exception;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\View\RenderInterface;
use Throwable;
/**
 * Ядро приложения
 */
final class App
{
    //Свойства
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
     * Компонент отвечающий за рендеринг
     *
     * @var RenderInterface|null
     */
    private ?RenderInterface $render = null;

    /**
     * компонент отвечающий за создание рендеринга
     *
     * @var callable
     */
    private $renderFactory;

    //Методы

    /**
     * Конструктор ядра приложения
     *
     * @param array $handlers - Обработчик запросов
     * @param callable $loggerFactory - Фабика для создания Логгеров
     * @param callable $appConfigFactory - Фабрика для создания конфига приложения
     */
    public function __construct(
        array $handlers,
        callable $loggerFactory,
        callable $appConfigFactory,
        callable $renderFactory
    ) {
        $this->handlers = $handlers;
        $this->loggerFactory = $loggerFactory;
        $this->appConfigFactory = $appConfigFactory;
        $this->renderFactory=$renderFactory;
        $this->initiateErrorHandler();
    }

    /**
     * @return RenderInterface
     */
    public function getRender(): RenderInterface
    {
        if (null===$this->render){
            $renderFactory=$this->renderFactory;
            $render=$renderFactory();
            if(!$render instanceof RenderInterface){
                throw new UnexpectedValueException('Рендер не корректного типа');
            }
            $this->render=$render;
        }
        return $this->render;
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
     * обработчик запроса
     *
     * @param ServerRequest $serverRequest - объект серверного http запроса
     * @return HttpResponse - http ответ
     */
    public function dispatch(ServerRequest $serverRequest): HttpResponse
    {
        $appConfig=null;
        try {
            $appConfig = $this->getAppConfig();
            $logger = $this->getLogger();

            $urlPath = $serverRequest->getUri()->getPath();
            $logger->log('Url request received: ' . $urlPath);

            if (array_key_exists($urlPath, $this->handlers)) {
                $httpResponse = call_user_func($this->handlers[$urlPath], $serverRequest, $logger, $appConfig);
                if (!$httpResponse instanceof HttpResponse) {
                    throw new UnexpectedValueException('Контроллер вернул не корректный результат');
                }
            } else {
                $httpResponse=ServerResponseFactory::createJsonResponse(
                    404,
                    [
                        'status' => 'fail',
                        'message' => 'unsupported request'
                    ]
                );
            }
        } catch (Exception\InvalidDataStructureException $e) {
            $httpResponse=ServerResponseFactory::createJsonResponse(
                503,
                [
                    'status' => 'fail',
                    'message' => $e->getMessage()
                ]
            );
        } catch (Throwable $e) {
            $errMsg = ($appConfig instanceof AppConfig && !$appConfig->isHideErrorMessage())
            || $e instanceof Exception\ErrorCreateAppConfigException
                ? $e->getMessage()
                : 'system error';

            try{
                $this->getLogger()->log($e->getMessage());
            }catch (Throwable $e1){}
            $httpResponse=ServerResponseFactory::createJsonResponse(
                500,
                [
                    'status' => 'fail',
                    'message' => $errMsg
                ]
            );
        }
        $this->getRender()->render($httpResponse);
        return $httpResponse;
    }
}