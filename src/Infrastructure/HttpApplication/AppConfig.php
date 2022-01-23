<?php

namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\HttpApplication;

class AppConfig implements AppConfigInterface
{
    /**
     * Скрывать сообщения по ошибкам
     *
     * @var bool
     */
    private bool $hideErrorMessage;
    /**
     * Возвращает флаг указывающий, что нужно скрыватиь сообщения по ошибкам
     *
     * @return bool
     */
    public function isHideErrorMessage(): bool
    {
        return $this->hideErrorMessage;
    }

    /**
     * Устанавливает флаг указывающий, что нужно скрыватиь сообщения по ошибкам
     *
     * @param bool $hideErrorMessage
     * @uses AppConfig::setHideErrorMessage()
     * @return AppConfig
     */
    private function setHideErrorMessage(bool $hideErrorMessage): AppConfig
    {
        $this->hideErrorMessage = $hideErrorMessage;
        return $this;
    }

    /**
     * Создаёт конфиг приложения из массива
     *
     * @param array $config
     * @return AppConfigInterface
     */
    public static function createFromArray(array $config): AppConfigInterface
    {
        $appConfig = new static();
        foreach ($config as $key => $value) {
            if (property_exists($appConfig, $key)) {
                $setter = 'set' . ucfirst($key);
                $appConfig->{$setter}($value);
            }
        }
        return $appConfig;
    }
}
