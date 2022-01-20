<?php

namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Logger;

final class LogLevel
{
    /**
     * Система полностью не работает
     */
    public const EMERGENCY = 'emergency';
    /**
     * Действие требует безотлагательного вмешательства
     */
    public const ALERT = 'alert';
    /**
     * Критические состояния
     */
    public const CRITICAL = 'critical';
    /**
     * Это уже у нас ошибка, не требующие вмешательства
     */
    public const ERROR = 'error';
    /**
     * Исключительные случаи, но не ошибки
     */
    public const WARNING = 'warning';
    /**
     * Существенные события
     */
    public const NOTICE = 'notice';
    /**
     * Интересные события
     */
    public const INFO = 'info';
    /**
     * Подробная информация о отладке
     */
    public const DEBUG = 'debug';

}