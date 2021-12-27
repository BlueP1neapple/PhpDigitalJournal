<?php

namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Router;

use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerRequest;

/**
 * Цепочка роутеров
 */
class ChainRouters implements RouterInterface
{
    //Свойства
    /**
     * Цепочка роутеров
     * @var RouterInterface[]
     */
    private array $routers;

    //Методы
    /**
     * @param RouterInterface ...$routers
     */
    public function __construct(RouterInterface ...$routers)
    {
        $this->routers = $routers;
    }


    /**
     * @inheritDoc
     */
    public function getDispatcher(ServerRequest $serverRequest): ?callable
    {
        $dispatcher = null;

        foreach ($this->routers as $router){
            $currentDispatcher = $router->getDispatcher($serverRequest);
            if(is_callable($currentDispatcher)){
                $dispatcher = $currentDispatcher;
                break;
            }
        }
        return $dispatcher;
    }
}