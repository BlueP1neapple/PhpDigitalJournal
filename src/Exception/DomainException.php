<?php

namespace JoJoBizzareCoders\DigitalJournal\Exception;

use JoJoBizzareCoders\DigitalJournal\Infrastructure\Exception as BaseException;

/**
 * Исключение создаёться, если значение не соотвествует определённой допустимой области данных
 */
class DomainException extends BaseException\DomainException implements ExceptionInterface
{
}
