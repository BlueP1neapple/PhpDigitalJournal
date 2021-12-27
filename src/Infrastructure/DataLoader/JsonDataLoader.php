<?php

    namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\DataLoader;

    use JsonException;

    /**
     * Загрузка данных из json файла
     */
    class JsonDataLoader implements DataLoaderInterface
    {

        /**
         * @inheritDoc
         */
        public function LoadDate(string $sourceName): array
        {
            $content = file_get_contents($sourceName);
            return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        }
    }