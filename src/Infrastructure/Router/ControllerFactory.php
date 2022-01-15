<?php

namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Router;

use JoJoBizzareCoders\DigitalJournal\Infrastructure\Controller\ControllerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DI\ContainerInterface;

/**
 * Фабрика контроллеров
 */
final class ControllerFactory
{
    // Свойство
    /**
     * DI контейнер
     * @var ContainerInterface
     */
    private ContainerInterface $diContainer;

    //Методы
    /**
     *
     * @param ContainerInterface $diContainer
     */
    public function __construct(ContainerInterface $diContainer)
    {
        $this->diContainer = $diContainer;
    }

    /**
     * Создаёт контроллер
     *
     * @param string $controllerClassName имя класса создаваемого контроллера
     * @return ControllerInterface
     */
    public function create(string $controllerClassName):ControllerInterface
    {
        return $this->diContainer->get($controllerClassName);
    }

}
