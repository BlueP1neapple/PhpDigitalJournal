<?php

namespace JoJoBizzareCoders\DigitalJournal\Config;

    use JoJoBizzareCoders\DigitalJournal\Infrastructure\HttpApplication\AppConfig as BaseConfig;

    /**
     * Конфиг приложения
     */
class AppConfig extends BaseConfig
{
    /**
     * возвращает uri формы аутентификации
     *
     * @var string
     */
    private string $loginUri;


    /**
     *  Возвращает uri формы аутентификации
     *
     * @return string
     */
    public function getLoginUri(): string
    {
        return $this->loginUri;
    }


    /**
     * Устанавливает uri формы аутентификации
     *
     * @param string $loginUri
     *
     * @return AppConfig
     */
    protected function setLoginUri(string $loginUri): AppConfig
    {
        $this->loginUri = $loginUri;
        return $this;
    }

}
