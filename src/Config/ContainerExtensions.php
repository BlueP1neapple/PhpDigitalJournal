<?php

namespace JoJoBizzareCoders\DigitalJournal\Config;

use JoJoBizzareCoders\DigitalJournal\Infrastructure\Router\SymfonyDi\DiRouterExt;

final class ContainerExtensions
{
    /**
     * Набор расширений для http приложения
     *
     * @return array
     */
    public static function httpAppContainerExtensions(): array
    {
        return [
            new DiRouterExt(),
           // new DiLoggerExt(),
           // new DiHttpExt()
        ];
    }

    /**
     * Расширения для консоли
     *
     * @return array
     */
    public static function consoleContainerExtensions(): array
    {
        return [
            new DiRouterExt(),
           // new DiLoggerExt(),
           // new DiHttpExt()
        ];
    }
}
