<?php
    namespace EfTech\BookLibrary\Infrastructure\DI;

    // TODO реализовать Чейнроутер, Дефолтный роутер, РегРексРоутер и контроллер фактори
    /**
     * нтерфейс контейнеров используемых для внедрения зависимостей
     */
    interface ContainerInterface
    {
        /**
         * Возвращает сервис по заданному имени
         *
         * @param string $serviceName
         * @return mixed
         */
        public function get(string $serviceName);
    }