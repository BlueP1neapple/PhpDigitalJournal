<?php

namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Exception;

use Throwable;

/**
 * Создаётся исключение если не соответствуют облости данных
 */
class DomainException extends \DomainException implements ExceptionInterface
{
}
