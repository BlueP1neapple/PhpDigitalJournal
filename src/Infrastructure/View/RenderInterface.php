<?php

    namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\View;

    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\HttpResponse;

    /**
     * Интерфейс рендеров
     */
    interface RenderInterface
    {
        /**
         * Метод отвечающий за рендеринг ифнормации
         *
         * @param HttpResponse $httpResponse - объект http ответа
         * @return void
         */
        public function render(HttpResponse $httpResponse):void;
    }