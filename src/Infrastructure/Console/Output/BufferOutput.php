<?php
namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Console\Output;
/**
 * Вывод данных в буфер. Класс для теста
 */
final class BufferOutput implements OutputInterface
{
    /**
     * Хранит результаты выводимых в консоль
     * @var array
     */
    private array $buffer = [];
    /**
     * @inheritDoc
     */
    public function print(string $text): void
    {
        $this->buffer[] = $text;
    }

    /**
     * @return array
     */
    public function getBuffer(): array
    {
        return $this->buffer;
    }


}
