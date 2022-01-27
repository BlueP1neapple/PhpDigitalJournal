<?php

namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Session;

use JoJoBizzareCoders\DigitalJournal\Infrastructure\Exception\RuntimeException;

class SessionNative implements SessionInterface
{

    /**
     *  Данные сессии
     *
     * @var array
     */
    private array $session;

    /**
     * @param array $session - Данные сессии
     */
    public function __construct(array &$session)
    {
        $this->session = &$session;
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->session);
    }

    /**
     * @inheritDoc
     */
    public function get(string $key)
    {
        if (false === $this->has($key)) {
            throw new RuntimeException('В сессии отсутсвуют значение для ключа' . $key);
        }
        return $this->session[$key];
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, $value): SessionInterface
    {
        $this->session[$key] = $value;
        return $this;
    }


    /**
     *  Создаёт сессию
     *
     * @return SessionNative
     */
    public static function create(): SessionNative
    {
        $sessionStatus = session_status();
        if (PHP_SESSION_DISABLED === $sessionStatus) {
            throw new RuntimeException('Сессии отключены');
        }
        if (PHP_SESSION_NONE === $sessionStatus) {
            session_start();
        }
        return new SessionNative($_SESSION);
    }

}