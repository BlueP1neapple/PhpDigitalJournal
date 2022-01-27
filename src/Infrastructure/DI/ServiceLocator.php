<?php

    namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\DI;

    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Exception\RuntimeException;

    /**
     * Сервис Локатор
     */
    class ServiceLocator implements ContainerInterface
    {
        //Свойтсво
        /**
         * Экземпляры зарегестрированныз сервисов.
         * - ключ это имя сервиса(совпадает с именем класса или интерфейса)
         * - значение сам сервис(обычно объект)
         *
         * @var array
         */
        private array $instances;

        //Методы

        /**
         * Конструктор локатора сервисов
         *
         * @param array $instances - массив экземпляров зарегестрированных сервисов
         */
        public function __construct(array $instances)
        {
            $this->instances = $instances;
        }


        /**
         * @inheritDoc
         */
        public function get(string $serviceName)
        {
            if (false === array_key_exists($serviceName, $this->instances)) {
                throw new RuntimeException('Отсутствует сервис с именем ' . $serviceName);
            }
            return $this->instances[$serviceName];
        }

    }