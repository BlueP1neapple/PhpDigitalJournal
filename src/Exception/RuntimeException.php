<?php

namespace JoJoBizzareCoders\DigitalJournal\Exception;

use JoJoBizzareCoders\DigitalJournal\Infrastructure\Exception as BaseException;

/**
 * Исключеник бросаеться в результате ошибок которые возникли во время выполнения
 */
class RuntimeException extends BaseException\RuntimeException implements ExceptionInterface
{
}
