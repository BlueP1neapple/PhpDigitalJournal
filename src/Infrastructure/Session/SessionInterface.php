<?php

namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Session;

interface SessionInterface
{

    /**
     * Проверяет есть ли в сессии данные по указаному ключу
     *
     * @param string $key - имя ключа в сессии
     * @return bool
     */
    public function has(string $key):bool;

    /**
     * Возвращает данные из сессии по ключу
     *
     * @param string $key - имя ключа в сиссии
     * @return mixed
     */
    public function get(string $key);

    /**
     * Устанавливает данные в сессию
     *
     * @param string $key - имя ключа в сессии
     * @param mixed $value - значение сохраняемое в сессию
     * @return $this
     */
    public function set(string $key, $value):self;

}