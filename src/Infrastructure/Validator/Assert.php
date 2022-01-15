<?php

    namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Validator;

    /**
     * Коллекции методов реализующих разнообразные проверки в приложении
     */
    final class Assert
    {
        /**
         * Проверяет что заданные элементы массива являються строками
         *
         * @param array $listItemsToCheck - список элементов для проверки. Ключ имя проверяемого элемента.
         * Значение - текст ошибки
         * @param array $dataForValidation - Валидируемые данные
         * @return string|null - текст ошибки или null если ошибки нет
         */
        public static function arrayElementsIsString (array $listItemsToCheck, array $dataForValidation): ?string
        {
            $result = null;
            foreach ($listItemsToCheck as $paramName => $errorMsg) {
                if (array_key_exists($paramName, $dataForValidation)
                    && false === is_string($dataForValidation[$paramName])) {
                    $result = $errorMsg;
                    break;
                }
            }
            return $result;
        }
    }