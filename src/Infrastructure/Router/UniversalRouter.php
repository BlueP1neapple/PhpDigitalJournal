<?php

    namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Router;

    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Controller\ControllerInterface;
    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerRequest;

    /**
     * Роутер работающий с любым запросом
     */
    class UniversalRouter implements RouterInterface
    {
        /**
         * Сопоставление http метода с действием
         */
        private const URL_METHOD_TO_ACTION = [
            'GET' => 'Get',
            'POST' => 'Create',
            'PUT' => 'Update',
            'DELETE' => 'Delete'
        ];

        /**
         * Паттерн определяющий подходящий урл
         */
        private const URL_PATTERN = '/^\/(?<___RESOURCE_NAME___>[a-zA-Z][a-zA-Z0-9\-]*)(\/(?<___RESOURCE_ID___>\d+))?(\/(?<___SUB_ACTION___>[a-zA-Z][a-zA-Z0-9\-]*))?\/?$/';

        /**
         * Пространство имён в котором распологаються контроллеры приложения
         *
         * @var string
         */
        private string $controllerNs;

        /**
         * Фабрика по созданию контроллеров
         *
         * @var ControllerFactory
         */
        private ControllerFactory $controllerFactory;

        //Методы

        /**
         * Конструтор универсального роутера
         *
         * @param string $controllerNs - Пространство имён в котором распологаються контроллеры приложения
         * @param ControllerFactory $controllerFactory - Фабрика по созданию контроллеров
         */
        public function __construct(string $controllerNs, ControllerFactory $controllerFactory)
        {
            $this->controllerNs = trim($controllerNs,'\\').'\\';
            $this->controllerFactory = $controllerFactory;
        }

        /**
         * @inheritDoc
         */
        public function getDispatcher(ServerRequest $serverRequest): ?callable
        {
            $dispatcher = null;
            $urlPath = $serverRequest->getUri()->getPath();
            $method = $serverRequest->getMethod();
            $matches = [];
            if (array_key_exists($method, self::URL_METHOD_TO_ACTION)
                && 1 === preg_match(self::URL_PATTERN, $urlPath, $matches)) {
                $action = self::URL_METHOD_TO_ACTION[$method];
                $resource = ucfirst($matches['___RESOURCE_NAME___']);
                $subAction = array_key_exists('___SUB_ACTION___', $matches) ? ucfirst(
                    $matches['___SUB_ACTION___']
                ) : '';
                $attr = [];
                if ('POST' === $method) {
                    $suffix = 'Controller';
                } elseif (array_key_exists('___RESOURCE_ID___', $matches)) {
                    $suffix = 'Controller';
                    $attr['id'] = $matches['___RESOURCE_ID___'];
                } else {
                    $suffix = 'CollectionController';
                }
                $className = $action . $subAction . $resource . $suffix;
                $fullClassName = $this->controllerNs . $className;
                if (class_exists($fullClassName)
                    && is_subclass_of($fullClassName, ControllerInterface::class, true)) {
                    $dispatcher = $this->controllerFactory->create($fullClassName);
                    $serverRequest->setAttributes($attr);
                }
            }
            return $dispatcher;
        }
    }