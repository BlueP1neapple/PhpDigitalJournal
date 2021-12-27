<?php

    namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\Exception;

    use JoJoBizzareCoders\DigitalJournal\Exception\RuntimeException;

    /**
     * Исключение выбрасываеться в случае если не удалось создать объект http запроса
     */
    class ErrorHttpRequestException extends RuntimeException
    {

    }