<?php

    namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Console;

    use JoJoBizzareCoders\DigitalJournal\Exception\RuntimeException;
    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Console\Output\EchoOutput;
    use JoJoBizzareCoders\DigitalJournal\Infrastructure\Console\Output\OutputInterface;
    use JoJoBizzareCoders\DigitalJournal\Infrastructure\DI\ContainerInterface;
    use Throwable;

    /**
     * ядро консольного приложения
     */
    class AppConsole
    {
        /**
         * Ключом являеться имя команды. а значение являяется класс команды или данные типа каллабл
         *
         * @var CommandInterface[]
         */
        private array $commands;

        /**
         * Компонент отвечающий за вывод данных в консоль
         *
         * @var OutputInterface|null
         */
        private ?OutputInterface $output = null;

        /**
         * Контейнер зависимостей
         *
         * @var ContainerInterface|null
         */
        private ?ContainerInterface $diContainer = null;

        /**
         * Фабрика реализующая создание рендера
         *
         * @var callable
         */
        private $outputFactory;

        /**
         * Фабрика по созданию ди контейнера
         *
         * @var callable
         */
        private $diContainerFactory;


        /**
         * Конструктор Консольное приложение
         *
         * @param array $commands - Ключом являеться имя команды. а значение являяется класс команды или данные типа каллабл
         * @param callable $outputFactory - Фабрика реализующая создание рендера
         * @param callable $diContainerFactory - Фабрика по созданию ди контейнера
         */
        public function __construct(array $commands, callable $outputFactory, callable $diContainerFactory)
        {
            $this->commands = $commands;
            $this->outputFactory = $outputFactory;
            $this->diContainerFactory = $diContainerFactory;
            $this->initErrorHandling();
        }

        /**
         * Возвращает  Компонент отвечающий за вывод данных в консоль
         *
         * @return OutputInterface
         */
        private function getOutput(): OutputInterface
        {
            if (null === $this->output) {
                $this->output = ($this->outputFactory)($this->getDiContainer());
            }
            return $this->output;
        }

        /**
         * Возвращает  Контейнер зависимостей
         *
         * @return ContainerInterface
         */
        private function getDiContainer(): ContainerInterface
        {
            if (null === $this->diContainer) {
                $this->diContainer = ($this->diContainerFactory)();
            }
            return $this->diContainer;
        }

        /**
         * Инициация обработки ошибок
         *
         * @return void
         */
        private function initErrorHandling(): void
        {
            set_error_handler(static function (int $errNom, string $errStr/*, string $errFile, int $errLine*/) {
                throw new RuntimeException($errStr, $errNom);
            });
        }

        /**
         *
         *
         * @param string|null $commandName
         * @param array|null $params
         * @return void
         */
        public function dispatch(string $commandName = null, array $params = null): void
        {
            $output = null;
            try {
                $output = $this->getOutput();
                $commandName = $commandName ?? $this->getCommandName();
                if (null === $commandName) {
                    throw new RuntimeException('Command name must be specified');
                }
                if (false === array_key_exists($commandName, $this->commands)) {
                    throw new RuntimeException("Unknown command: '$commandName'");
                }
                if (false === is_string($this->commands[$commandName])
                    || false === is_subclass_of($this->commands[$commandName], CommandInterface::class, true)) {
                    throw new RuntimeException("There is no valid handler for command '$commandName'");
                }
                $command = $this->getDiContainer()->get($this->commands[$commandName]);
                $params = $params ?? $this->getCommandParams($this->commands[$commandName]);
                $command($params);
            } catch (Throwable $e) {
                $output = $output ?? new EchoOutput();
                $output->print("ERROR: {$e->getMessage()}\n");
            }
        }

        /**
         * Возвращает имя команды
         *
         * @return string|null
         */
        private function getCommandName(): ?string
        {
            $option = getopt('', ['command:']);
            $command = null;
            if (is_array($option) && array_key_exists('command', $option) && is_string($option['command'])) {
                $command = $option['command'];
            }
            return $command;
        }

        /**
         * Возвращает параметры для команды
         *
         * @param string $commandName
         * @return array
         */
        private function getCommandParams(string $commandName): array
        {
            $longOptions = call_user_func("$commandName::getLongOptions");
            $shortOptions = call_user_func("$commandName::getShortOptions");
            $options = getopt($shortOptions, $longOptions);
            return is_array($options) ? $options : [];
        }
    }