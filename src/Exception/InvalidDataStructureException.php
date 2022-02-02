<?php

namespace JoJoBizzareCoders\DigitalJournal\Exception;

use JoJoBizzareCoders\DigitalJournal\Infrastructure\Exception as BaseException;

/**
 * Исключение выбрасываеться в случае если данные с короторыми работает приложение некорректные
 */
class InvalidDataStructureException extends BaseException\InvalidDataStructureException
{
}
