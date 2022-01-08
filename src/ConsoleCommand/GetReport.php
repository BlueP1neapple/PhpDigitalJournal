<?php

namespace JoJoBizzareCoders\DigitalJournal\ConsoleCommand;

use JoJoBizzareCoders\DigitalJournal\Infrastructure\Console\CommandInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Console\Output\OutputInterface;

class GetReport implements CommandInterface
{
    /**
     * @var OutputInterface
     */
    private OutputInterface $output;

    /**
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }


    /**
     * @inheritDoc
     */
    public  static function getShortOption(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public static function getLongOption(): array
    {
        return [
            'surname:',
            'id:'
        ];
    }

    /**
     * @inheritDoc
     */
    public function __invoke(array $prams): void
    {
        $this->output->print('GetReport');
    }
}