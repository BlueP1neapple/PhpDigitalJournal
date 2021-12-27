<?php

    namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\View;

    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\HttpResponse;

    /**
     * Рендеринг заглушка
     */
    class NullRender implements RenderInterface
    {

        /**
         * @inheritDoc
         */
        public function render(HttpResponse $httpResponse): void
        {
        }
    }