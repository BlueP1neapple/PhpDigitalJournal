<?php

use JoJoBizzareCoders\DigitalJournal\Infrastructure\Console\CommandInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Console\Output\EchoOutPut;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Console\Output\OutputInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DI\ContainerInterface;
use JoJoBizzareCoders\DigitalJournal\Exception;

class AppConsole
{
    /**
     * @var array
     */
    private array $commands;

    /**
     * Вывод в консоль
     *
     * @var OutputInterface|null
     */
    private ?OutputInterface $output = null;

    /**
     * di контейнер
     * @var ContainerInterface|null
     */
    private ?ContainerInterface $diContainer = null;
    /**
     * создание рендера
     * @var callable
     */
    private $outPutFactory;

    /**
     * Фабрика по созданию di контейнера
     * @var callable
     *
     */
    private $diConteinerFactory;

    /**
     * @param array $commands
     * @param callable $outPutFactory
     * @param callable $diConteinerFactory
     */
    public function __construct(array $commands, callable $outPutFactory, callable $diConteinerFactory)
    {
        $this->commands = $commands;
        $this->outPutFactory = $outPutFactory;
        $this->diConteinerFactory = $diConteinerFactory;
        $this->initiateErrorHandling();
    }

    private function initiateErrorHandling(): void
    {
        set_error_handler(static function (int $errNo, string $errStr/*, $err0, $err1*/) {
            throw new Exception\UnexpectedValueException($errStr);
        });
    }

    /**
     * @return OutputInterface
     */
    private function getOutput(): OutputInterface
    {
        if(null === $this->output){
            $this->output = ($this->outPutFactory)($this->getDiContainer());
        }
        return $this->output;
    }

    /**
     * @return ContainerInterface
     */
    private function getDiContainer(): ContainerInterface
    {
        if(null === $this->diContainer){
            $this->diContainer = ($this->diConteinerFactory)();
        }
        return $this->diContainer;
    }

    public function dispatch(string $commandName = null, array $prams = null):void
    {
        $output = null;
        try {
            $output = $this->getOutput();

            $commandName = $commandName ?? $this->getCommandName();
            if(null === $commandName){
                throw new Exception\RuntimeException('Command name must specified');
            }

            if(false === array_key_exists($commandName, $this->commands)){
                throw new Exception\RuntimeException("Unknown command: '$commandName'");
            }

            if(false === is_string($this->commands[$commandName])
                || false === is_subclass_of($this->commands[$commandName], CommandInterface::class, true)){
                throw new Exception\RuntimeException("Invalled handler for command: '$commandName'");
            }
            $command = $this->getDiContainer()->get($this->commands[$commandName]);

            $prams = $prams ?? $this->getCommandParams($this->commands[$commandName]);

            $command($prams);

        }catch (Throwable $e){
            $output = $output ?? new EchoOutPut();
            $output->print("Error: {$e->getMessage()}\n");
        }
    }

    /**
     * Возвращает имя команды
     * @return string|null
     */
    private function getCommandName():?string
    {
        $option = getopt('', ['command:']);
        $command = null;
        if(is_array($option) && array_key_exists('command', $option)&& is_string($option['command'])){
            $command = $option['command'];
        }
        return $command;
    }

    private function getCommandParams(string $commandName)
    {
        $longOptions = call_user_func("$commandName::getLongOption");
        $shortOptions = call_user_func("$commandName::getShortOption");

        $options = getopt($shortOptions, $longOptions);

        return is_array($options) ? $options : [];
    }

}