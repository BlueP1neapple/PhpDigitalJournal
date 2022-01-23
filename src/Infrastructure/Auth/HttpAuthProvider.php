<?php

namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Auth;

use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\HttpResponse;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerResponseFactory;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Session\SessionInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Uri\Uri;

class HttpAuthProvider
{
    /**
     * Ключ по которому в сессии храняться id пользователя если пользователь аунтефицирован
     */
    private const USER_ID = 'user_id';

    /**
     * хранилище данных о пользователе
     *
     * @var UserDataStorageInterface
     */
    private UserDataStorageInterface $userDataStorage;

    /**
     * Контейнер для работы с сессиями
     *
     * @var SessionInterface
     */
    private SessionInterface $session;

    /**
     * Ури для открытия формы аунтификации
     *
     * @var Uri
     */
    private Uri $loginUri;

    /**
     * @param UserDataStorageInterface $userDataStorage - хранилище данных о пользователе
     * @param SessionInterface $session - Контейнер для работы с сессиями
     * @param Uri $loginUri - Ури для открытия формы аунтификации
     */
    public function __construct(UserDataStorageInterface $userDataStorage, SessionInterface $session, Uri $loginUri)
    {
        $this->userDataStorage = $userDataStorage;
        $this->session = $session;
        $this->loginUri = $loginUri;
    }


    /**
     * Проводит аунтификацию
     *
     * @param string $login - логин пользователя
     * @param string $password - пароль пользователя
     * @return bool - определяет прошла ли аунтификация успешно или нет
     */
    public function auth(string $login, string $password):bool
    {
        $isAuth = false;
        $user = $this->userDataStorage->findUserByLogin($login);
        if (null !== $user && password_verify($password,$user->getPassword())){
            $this->session->set(self::USER_ID,$login);
            $isAuth = true;
        }
        return $isAuth;
    }

    /**
     * Проверяет что пользователь аунтефицирован
     *
     * @return bool
     */
    public function isAuth(): bool
    {
        return $this->session->has(self::USER_ID);
    }

    /**
     * Возвращает локацию логига
     *
     * @return Uri
     */
    private function getLoginUri(): Uri
    {
        return $this->loginUri;
    }

    /**
     * Запускает процесс аунтефикации
     *
     * @param Uri $successUri - адресс на который нужно перейти после успешного ввода логина и пароля
     * @return HttpResponse - шттп ответ приводящий к открытию формы антефикации
     */
    public function doAuth(Uri $successUri): HttpResponse
    {
        $loginUri=$this->getLoginUri();

        $loginQueryStr = $loginUri->getQuery();
        $LoginQuery = [];
        parse_str($loginQueryStr,$LoginQuery);
        $LoginQuery['redirect'] = (string)$successUri;
        $uri = new Uri(
            $loginUri->getSchema(),
            $loginUri->getUserInfo(),
            $loginUri->getHost(),
            $loginUri->getPort(),
            $loginUri->getPath(),
            http_build_query($LoginQuery),
            $loginUri->getFragment()
        );

        return ServerResponseFactory::redirect($uri);
    }


}