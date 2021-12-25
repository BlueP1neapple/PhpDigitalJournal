<?php
namespace JoJoBizzareCoders\DigitalJournal\Infrastructure;


use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\HttpResponse;

/**
 *Отображает результат клиенту
 *
 * @param HttpResponse $response
 *
 * @return void
 */
function render(HttpResponse $response): void
{
    foreach ($response->getHeaders() as $headerName=>$headerValue){
        header("$headerName: $headerValue");
    }
    http_response_code($response->getStatusCode());
    echo $response->getBody();
    exit();
}