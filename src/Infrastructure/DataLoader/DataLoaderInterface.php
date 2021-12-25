<?php

    namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\DataLoader;

    use JsonException;

    /**
     * Интерфейс зугрузчика данных
     */
    interface DataLoaderInterface
    {
        /**
         * Загрузка данных из файла
         *
         * @param string $sourceName - путь до загружаемого файла
         * @return array
         * @throws JsonException
         */
        public function LoadDate(string $sourceName): array;
    }