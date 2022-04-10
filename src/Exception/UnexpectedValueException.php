<?php

namespace JoJoBizzareCoders\DigitalJournal\Exception;

use JoJoBizzareCoders\DigitalJournal\Infrastructure\Exception as BaseException;

/**
 * Выбрасывает исключение, если значение не совпадает с набором значений
 */
class UnexpectedValueException extends BaseException\UnexpectedValueException implements ExceptionInterface
{
}
