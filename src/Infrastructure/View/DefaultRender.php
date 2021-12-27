<?php

    namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\View;

    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\HttpResponse;

    /**
     * Рендер по умолчанию
     */
    class DefaultRender implements RenderInterface
    {

        /**
         * @inheritDoc
         */
        public function render(HttpResponse $httpResponse): void
        {
            foreach ($httpResponse->getHeaders() as $headerName=>$headerValue){
                header("$headerName: $headerValue");
            }
            http_response_code($httpResponse->getStatusCode());
            echo $httpResponse->getBody();
            exit();
        }
    }