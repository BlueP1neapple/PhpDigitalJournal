<?php

    namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Router;

    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Controller\ControllerInterface;
    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerRequest;

    /**
     * Роутер работающий с универсальными регулярными выражениями
     */
    class RegExpRouter implements RouterInterface
    {
        /**
         * Ассоциативный массив в котором сопаставлены регулярные выражения и обработчики
         *
         * @var array
         */
        private array $handlers;

        /**
         * Фабрика по созданию контроллеров
         *
         * @var ControllerFactory
         */
        private ControllerFactory $controllerFactory;

        /**
         * Конструктор роутера работающего с универсальными регулярными выражениями
         *
         * @param array $handlers - Ассоциативный массив в котором сопаставлены регулярные выражения и обработчики
         * @param ControllerFactory $controllerFactory - Фабрика по созданию контроллеров
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
            foreach ($this->handlers as $pattern => $currentDispatcher) {
                $matches = [];
                if (1 === preg_match($pattern, $urlPath, $matches)) {
                    if(is_callable($currentDispatcher)){
                        $dispatcher = $currentDispatcher;
                    }elseif (is_string($currentDispatcher)
                        && is_subclass_of($currentDispatcher, ControllerInterface::class, true)) {
                        $dispatcher = $this->controllerFactory->create($currentDispatcher);
                    }
                    if (null !== $dispatcher) {
                        $serverRequestAttributes = $this->buildServerRequestAttributes($matches);
                        $serverRequest->setAttributes($serverRequestAttributes);
                        break;
                    }
                }
            }

            return $dispatcher;
        }

        /**
         * Создание атрибутов серверного запроса
         *
         * @param array $matches - массив содержащий атрибуты из урл
         * @return array
         */
        private function buildServerRequestAttributes(array $matches): array
        {
            $attributes = [];
            foreach ($matches as $key => $value) {
                if (0 === strpos($key, '___') && '___' === substr($key, -3) && strlen($key) > 6) {
                    $attributes[$this->buildAttrName($key)] = $value;
                }
            }
            return $attributes;
        }

        /**
         * Создание имени атрибута в формате камелкейс
         *
         * @param string $groupName - имя группы атрибута
         * @return string
         */
        private function buildAttrName(string $groupName): string
        {
            $cleanAttrName = strtolower(substr($groupName, 3, -3));
            $parts = explode('_', $cleanAttrName);
            $ucParts = array_map('ucfirst', $parts);
            return lcfirst(implode('', $ucParts));
        }
    }