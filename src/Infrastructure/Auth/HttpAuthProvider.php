<?php

namespace JoJoBizzareCoders\DigitalJournal\Infrastructure\Auth;

use JoJoBizzareCoders\DigitalJournal\Entity\AbstractUserClass;
use JoJoBizzareCoders\DigitalJournal\Entity\ParentRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\StudentRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\TeacherRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Exception\RuntimeException;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\HttpResponse;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerResponseFactory;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Session\SessionInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Uri\Uri;

class HttpAuthProvider
{
    /**
     * Конста для хранения сессии пользователя
     */
    private const USER_ID = 'user_id';

    /**
     * Репозиторий родителей
     *
     * @var ParentRepositoryInterface
     */
    private ParentRepositoryInterface $parentRepository;

    /**
     * Репа учеников
     *
     * @var StudentRepositoryInterface
     */
    private StudentRepositoryInterface $studentRepository;

    /**
     * Репозиторий учителей
     *
     * @var TeacherRepositoryInterface
     */
    private TeacherRepositoryInterface $teacherRepository;

    /**
     * Контейнер для работы с сессиями
     *
     * @var SessionInterface
     */
    private SessionInterface $session;

    /**
     * Юри для логина
     *
     * @var Uri
     */
    private Uri $loginUri;


    /**
     * @param ParentRepositoryInterface $parentRepository
     * @param StudentRepositoryInterface $studentRepository
     * @param TeacherRepositoryInterface $teacherRepository
     * @param SessionInterface $session
     * @param Uri $loginUri
     */
    public function __construct(
        ParentRepositoryInterface $parentRepository,
        StudentRepositoryInterface $studentRepository,
        TeacherRepositoryInterface $teacherRepository,
        SessionInterface $session,
        Uri $loginUri
    ) {
        $this->parentRepository = $parentRepository;
        $this->studentRepository = $studentRepository;
        $this->teacherRepository = $teacherRepository;
        $this->session = $session;
        $this->loginUri = $loginUri;
    }


    /**
     * Проводит аутентификацию
     *
     * @param string $login
     * @param string $password
     * @return bool
     */
    public function auth(string $login, string $password):bool
    {
        $isAuth = false;
        $user = $this->findUserByLogin($login);

        if (null !== $user && password_verify($password, $user->getPassword())) {
            $this->session->set(self::USER_ID, $login);
            $isAuth = true;
        }
        return $isAuth;

    }

    private function findUserByLogin(string $login): AbstractUserClass
    {
        $currentUser = [];
        $userParent = $this->parentRepository->findUserByLogin($login);
        $userTeacher = $this->teacherRepository->findUserByLogin($login);
        $userStudent = $this->studentRepository->findUserByLogin($login);

        if(null !== $userParent){
            $currentUser[] = $userParent;
        }
        if (null !== $userTeacher){
            $currentUser[] = $userTeacher;
        }
        if(null !== $userStudent){
            $currentUser[] = $userStudent;
        }

        if(count($currentUser) > 1){
            throw new RuntimeException
            ('Найдено несколько пользователей с одинаковым логином но в разных репозиториях');
        }
        return $currentUser[0];

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
     * @return HttpResponse - Http ответ приводящий к открытию формы аутенитифакции
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