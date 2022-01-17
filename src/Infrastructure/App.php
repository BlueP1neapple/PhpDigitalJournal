<?php

namespace JoJoBizzareCoders\DigitalJournal\Infrastructure;


use JoJoBizzareCoders\DigitalJournal\Infrastructure\DI\ContainerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\HttpResponse;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerRequest;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerResponseFactory;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger\LoggerInterface;
use JoJoBizzareCoders\DigitalJournal\Exception;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Router\RouterInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\View\RenderInterface;
use Throwable;
/**
 * Ядро приложения
 */
final class App
{
    //Свойства
    /**
     * Конфиг приложения
     */
    private ?AppConfig $appConfig = null;

    /**
     * Логгирование
     * @var LoggerInterface|null
     */
    private ?LoggerInterface $logger = null ;
    /**
     * Рендеринг
     * @var RenderInterface|null
     */
    private ?RenderInterface $render = null;
    /**
     * @var ContainerInterface|null
     */
    private ?ContainerInterface $container = null;

    private ?RouterInterface $router = null;
    /**
     * Фабрика роутинга
     * @var callable
     */
    private $routerFactory;
    /**
     * Фабрика логов
     * @var callable
     */
    private $loggerFactory;
    /**
     * Фабрика конфига
     * @var callable
     */
    private $appConfigFactory;
    /**
     * Фабрика рендера
     * @var callable
     */
    private $renderFactory;
    /**
     * Фабрика di
     * @var callable
     */
    private $diContainerFactory;


    private function initiateErrorHandling(): void
    {
        set_error_handler(static function (int $errNo, string $errStr) {
            throw new Exception\UnexpectedValueException($errStr);
        });
    }

    /**
     *
     * @param callable $routerFactory Фабрика роутинга
     * @param callable $loggerFactory Фабрика логгера
     * @param callable $appConfigFactory Фабрика конфига приложения
     * @param callable $renderFactory Фабрика рендера
     * @param callable $diContainerFactory Фабрикадля контейнеров
     */
    public function __construct(
        callable $routerFactory,
        callable $loggerFactory,
        callable $appConfigFactory,
        callable $renderFactory,
        callable $diContainerFactory
    ) {
        $this->routerFactory = $routerFactory;
        $this->loggerFactory = $loggerFactory;
        $this->appConfigFactory = $appConfigFactory;
        $this->renderFactory = $renderFactory;
        $this->diContainerFactory = $diContainerFactory;
        $this->initiateErrorHandling();
    }

    /**
     * @return ContainerInterface
     */
    private function getContainer(): ContainerInterface
    {
        if(null === $this->container){
            $this->container = ($this->diContainerFactory)();
        }
        return $this->container;
    }

    /**
     * Возвращает роутер
     * @return mixed
     */
    private function getRouter(): RouterInterface
    {
        if(null === $this->router){
            $this->router = ($this->routerFactory)($this->getContainer());
        }
        return $this->router;
    }

    /**
     * @return AppConfig
     */
    private function getAppConfig(): AppConfig
    {
        if(null === $this->appConfig){
            $this->appConfig = ($this->appConfigFactory)($this->getContainer());
        }
        return $this->appConfig;
    }

    /**
     * @return LoggerInterface
     */
    private function getLogger(): LoggerInterface
    {
        if(null === $this->logger){
            $this->logger = ($this->loggerFactory)($this->getContainer());
        }
        return $this->logger;
    }

    /**
     * @return RenderInterface
     */
    private function getRender(): RenderInterface
    {
        if(null === $this->render){
            $this->render = ($this->renderFactory)($this->getContainer());
        }
        return $this->render;
    }


    /**
     * Обработчик запроса
     *
     * @param ServerRequest $serverRequest
     * @return HttpResponse
     */
    public function dispatch(ServerRequest $serverRequest): HttpResponse
    {
        $hasAppConfig = false;
        try {
            $hasAppConfig = $this->getAppConfig() instanceof AppConfig;
            $logger = $this->getLogger();

            $urlPath = $serverRequest->getUri()->getPath();
            $logger->log("Переход на " . $urlPath);

            $dispatcher = $this->getRouter()->getDispatcher($serverRequest);
            if (is_callable($dispatcher)) {
                $httpResponse = $dispatcher($serverRequest);
                if (!$httpResponse instanceof HttpResponse) {
                    throw new Exception\UnexpectedValueException('Контроллер вернул некорректный результат');
                }
            } else {
                $httpResponse = ServerResponseFactory::createJsonResponse(404, [
                    'status'  => 'fail',
                    'message' => 'unsupported request'
                ]);
                $this->logger->log('unsupported request');
            }
            $this->getRender()->render($httpResponse);
        } catch (Exception\InvalidDataStructureException $e) {
            $httpResponse = ServerResponseFactory::createJsonResponse(503, [
                'status'  => 'fail',
                'message' => $e->getMessage()
            ]);

            $this->silentRender($httpResponse);
            $this->logger->log($e->getMessage());
        } catch (Throwable $e) {
            $errMsg = ($hasAppConfig && !$this->getAppConfig()->isHideErrorMessage())
            || $e instanceof Exception\ErrorCreateAppConfigException ? $e->getMessage() : 'system error';

            $this->silentLog($e->getMessage());
            $httpResponse = ServerResponseFactory::createJsonResponse(500, [
                'status' => 'fail', 'message' => $errMsg
            ]);
            $this->silentRender($httpResponse);

        }

        return $httpResponse;

    }

    /**
     * Тихий рендер
     * @param HttpResponse $httpResponse
     */
    private function silentRender(HttpResponse $httpResponse):void
    {
        try {
            $this->getRender()->render($httpResponse);
        }catch (Throwable $e){
            $this->silentLog($e);
        }
    }

    /**
     * Тихое логирование
     * @param string $message
     */
    private function silentLog(string $message):void
    {
        try{
            $this->getLogger()->log($message);
        }catch (Throwable $e){

        }

    }


}