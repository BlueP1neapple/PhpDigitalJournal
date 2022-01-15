<?php

    namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\DI;

    use http\Params;
    use JoJoBizzareCoders\DigitalJournal\Exception\RuntimeException;

    /**
     * Менеджер сервисов
     */
    class ServiceManager implements ContainerInterface
    {
        //Своства
        /**
         * Инстансы зарегистрированных сервисов
         * - ключ это имя сервиса(совпадает с именем класса или интерфейса)
         * - значение сам сервис(обычно объект)
         *
         * @var array
         */
        public array $instances;

        /**
         * Фабрики создания сервисов
         *
         * @var callable[]
         */
        public array $factories;

        //Методы

        /**
         * Конструктор менеджера сервисов
         *
         * @param array $instances - массив экземпляров зарегестрированных сервисов
         * @param callable[] $factories - массив фабрик создающие сервисы
         */
        public function __construct(array $instances, array $factories)
        {
            $this->instances = $instances;
            $this->factories = $factories;
        }

        /**
         * Регистрация фабрик
         *
         * @param callable ...$factories - регистрируемые фабрики
         * @return void
         */
        public function registerFactory(callable ...$factories): void
        {
            $this->factories = $factories;
        }

        /**
         * @inheritDoc
         */
        public function get(string $serviceName)
        {
            if (array_key_exists($serviceName, $this->instances)) {
                $service = $this->instances[$serviceName];
            } elseif (array_key_exists($serviceName, $this->factories)) {
                $service = ($this->factories[$serviceName])($this);
                $this->instances[$serviceName] = $service;
            } else {
                throw new RuntimeException('Неудалось создать сервис ' . $serviceName);
            }
            return $service;
        }
    }