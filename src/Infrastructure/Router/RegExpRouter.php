<?php

namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Router;

use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerRequest;

class RegExpRouter implements RouterInterface
{
    /**
     * Ассоциотивный массив в котором сопоставленны регулярки и обработчики
     * @var array
     */
    private array $handlers;

    /**
     * DI контейнер
     * @var ControllerFactory
     */
    private ControllerFactory $controllerFactory;

    /**
     * @param array $handlers Ассоциотивный массив в котором сопоставленны регулярки и обработчики
     * @param ControllerFactory $controllerFactory  DI контейнер
     */
    public function __construct(array $handlers, ControllerFactory $controllerFactory)
    {
        $this->handlers = $handlers;
        $this->controllerFactory = $controllerFactory;
    }


    /**
     * @inheritDoc
     */
    public function getDispatcher(ServerRequest $serverRequest): ?callable
    {
        $urlPath = $serverRequest->getUri()->getPath();
        $dispatcher = null;

        foreach ($this->handlers as $pattern => $curentDispatcher){
            $matches = [];
            if(1 === preg_match($pattern, $urlPath, $matches)){
                if(is_callable($curentDispatcher)){
                    $dispatcher = $curentDispatcher;
                } elseif (is_string($curentDispatcher) &&
                    is_subclass_of($curentDispatcher,
                        ControllerInterface::class, true)){
                    $dispatcher = $this->controllerFactory->create($curentDispatcher);
                }
                if(null !==$dispatcher){
                    $serverRequestAttributes = $this->buildServerRequestAttributes($matches);
                    $serverRequest->setAttributes($serverRequestAttributes);
                    break;
                }

            }

        }
        return $dispatcher;
    }

    /**
     * Получение атребутов серверного запроса
     * @param array $matches
     * @return array
     */
    private function buildServerRequestAttributes(array $matches):array
    {
        $attributes = [];
        foreach ($matches as $key => $value){
            if(0 === strpos($key, '___')
                && '___' === substr($key, -3)
                && strlen($key) > 6){
                $attributes[$this->buildAttrName($key)] = $value;
            }
        }
        return $attributes;
    }

    private function buildAttrName(string $groupName):string
    {
        $clearNameAttr = strtolower(substr($groupName, 3, -3));

        $parts = explode('_', $clearNameAttr);
        $ucParts = array_map('ucfirst', $parts);

        return lcfirst(implode('', $ucParts));

    }
}