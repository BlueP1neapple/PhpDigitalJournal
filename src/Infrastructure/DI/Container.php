<?php

    namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\DI;

    use JoJoBizzareCoders\DigitalJournal\Exception\DomainException;
    use JoJoBizzareCoders\DigitalJournal\Exception\RuntimeException;
    use Throwable;

    class Container implements ContainerInterface
    {
        //Свойства
        /**
         * Инстансы зарегистрированных сервисов
         * - ключ это имя сервиса(совпадает с именем класса или интерфейса)
         * - значение сам сервис(обычно объект)
         *
         * @var array
         */
        private array $instances;

        /**
         * Фабрики создания сервисов
         *
         * @var callable[]
         */
        private array $factories;

        /**
         * Конфиги для создания сервисов
         *
         * @var array
         */
        private array $services;

        //Методы

        /**
         * @param array $instance
         * @param callable[] $factories
         * @param array $Services
         */
        public function __construct(array $instance = [], array $factories = [], array $Services = [])
        {
            $this->instances = $instance;
            $this->factories = $factories;
            $this->services = $Services;
        }

        /**
         * Создаються контейнеры из массива
         *
         * @param array $diConfig - конфиг контейнера
         * @return static
         */
        public static function createFromArray(array $diConfig):self
        {
            $instances = array_key_exists('instances', $diConfig) ? $diConfig['instances'] : [];
            $factories = array_key_exists('factories', $diConfig) ? $diConfig['factories'] : [];
            $services = array_key_exists('services', $diConfig) ? $diConfig['services'] : [];
            return new self($instances, $factories, $services);
        }

        private function createService(string $serviceName)
        {
            $className=$serviceName;
            if(array_key_exists('class',$this->services[$serviceName])){
                $className=$this->services[$serviceName]['class'];
            }
            if(false===is_string($className)){
                throw new DomainException('Имя создаваемого класса, дожно быть строкой');
            }
            $args=[];
            if(array_key_exists('args',$this->services[$serviceName])){
                $args=$this->services[$serviceName]['args'];
            }
            if(false===is_array($args)){
                throw new DomainException('Аргументы должны быть определены массивом');
            }
            $resolvedArgs = [];
            foreach ($args as $arg) {
                $resolvedArgs[] = $this->get($arg);
            }
            try{
                $instances = new $className(...$resolvedArgs);
            }catch(Throwable $e){
                throw new RuntimeException(
                    'Ошибка создания сервиса: '.$serviceName
                );
            }
            $this->instances[$serviceName]=$instances;
            return $instances;
        }

        /**
         * @inheritDoc
         */
        public function get(string $serviceName)
        {
            if (array_key_exists($serviceName, $this->instances)) {
                $service = $this->instances[$serviceName];
            } elseif (array_key_exists($serviceName, $this->services)) {
                $service = $this->createService($serviceName);
            } elseif (array_key_exists($serviceName, $this->factories)) {
                $service = ($this->factories[$serviceName])($this);
                $this->instances[$serviceName] = $service;
            } else {
                throw new RuntimeException('Нет данных для создания сервиса ' . $serviceName);
            }
            return $service;
        }
    }