<?php

namespace JoJoBizzareCoders\DigitalJournal\ConsoleCommand;

use JoJoBizzareCoders\DigitalJournal\Infrastructure\Console\CommandInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Console\Output\OutputInterface;

class HashStr implements CommandInterface
{

    /**
     * Компонент отвечающий за вывод данных через консоль
     *
     * @var OutputInterface
     */
    private OutputInterface $output;



    /**
     * @param OutputInterface $output - Компонент отвечающий за вывод данных через консоль
     */
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @inheritDoc
     */
    public static function getShortOptions(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public static function getLongOptions(): array
    {
        return [
            'data:'
        ];
    }

    /**
     * @inheritDoc
     */
    public function __invoke(array $params): void
    {
        if (false === array_key_exists('data', $params)) {
            $msg = 'Data for hashing is not specified';
        } elseif (false === is_string($params['data'])) {
            $msg = 'Hash data is not in the correct format';
        }else{
            $msg = password_hash($params['data'],PASSWORD_DEFAULT);
        }
        $this->output->print($msg);
    }

}