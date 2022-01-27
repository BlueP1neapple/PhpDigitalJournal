<?php

namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Exception;

use Throwable;

/**
 * Исключение если значение не совпадает с набором значений
 */
class UnexpectedValueException extends \UnexpectedValueException implements ExceptionInterface
{
}
