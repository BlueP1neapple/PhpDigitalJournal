<?php

namespace JoJoBizzareCoders\DigitalJournal\Controller;

use JoJoBizzareCoders\DigitalJournal\Exception\RuntimeException;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Auth\HttpAuthProvider;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Controller\ControllerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerResponseFactory;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\ViewTemplate\ViewTemplateInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriFactoryInterface;
use Throwable;

class LoginController implements ControllerInterface
{

    /**
     * Фабрика для создания uri
     *
     * @var UriFactoryInterface
     */
    private UriFactoryInterface $uriFactory;

    /**
     * Фабрика для создания http ответов
     *
     * @var ServerResponseFactory
     */
    private ServerResponseFactory $serverResponseFactory;

    /**
     * Шаблонизатор
     *
     * @var ViewTemplateInterface
     */
    private ViewTemplateInterface $viewTemplate;

    /**
     * Поставщик услуг аунтификации
     *
     * @var HttpAuthProvider
     */
    private HttpAuthProvider $httpAuthProvider;

    /**
     * @param ViewTemplateInterface $viewTemplate - Шаблонизатор
     * @param HttpAuthProvider $httpAuthProvider - Поставщик услуг аунтификации
     * @param ServerResponseFactory $serverResponseFactory
     * @param UriFactoryInterface $uriFactory
     */
    public function __construct(
        ViewTemplateInterface $viewTemplate,
        HttpAuthProvider $httpAuthProvider,
        ServerResponseFactory $serverResponseFactory,
        UriFactoryInterface $uriFactory
    ) {
        $this->viewTemplate = $viewTemplate;
        $this->httpAuthProvider = $httpAuthProvider;
        $this->serverResponseFactory = $serverResponseFactory;
        $this->uriFactory = $uriFactory;
    }


    /**
     * @inheritDoc
     */
    public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
    {
        try {
            $response= $this->doLogin($serverRequest);
        } catch (Throwable $e){
            $response = $this->buildErrorResponse($e);
        }
        return $response;
    }

    /**
     * Создание http ответа для ошибки
     *
     * @param Throwable $e
     * @return ResponseInterface
     */
    private function buildErrorResponse(Throwable $e):ResponseInterface
    {
        $httpCode = 500;
        $context = [
            'errors' => [
                $e->getMessage()
            ]
        ];
        $html = $this->viewTemplate->render(
            __DIR__ . 'errors.twig',
            $context
        );
        return $this->serverResponseFactory->createHtmlResponse($httpCode, $html);
    }

    /**
     * Процесс аунтификации
     *
     * @param ServerRequestInterface $serverRequest
     * @return ResponseInterface
     */
    private function doLogin(ServerRequestInterface $serverRequest): ResponseInterface
    {
        $response = null;
        $context = [];
        if('POST' === $serverRequest->getMethod()){
            $authData = [];
            parse_str($serverRequest->getBody(),$authData);
            $this->validateAuthData($authData);
            if($this->isAuth($authData['login'],$authData['password'])){
                $queryParams = $serverRequest->getQueryParams();
                $redirect = array_key_exists('redirect',$queryParams)
                    ? $this->uriFactory->createUri($queryParams['redirect'])
                    :$this->uriFactory->createUri('/');
                $response = $this->serverResponseFactory->redirect($redirect);
            }else{
                $context['errMsg'] = 'Логин и пароль не подходят';
            }
        }
        if (null === $response){
            $html = $this->viewTemplate->render(__DIR__ . 'login.twig', $context);
            $response = $this->serverResponseFactory->createHtmlResponse(200, $html);
        }
        return $response;
    }

    /**
     * логика валидации форм унтификации
     *
     * @param array $authData - данные форм аунтификации
     * @return void
     */
    private function validateAuthData(array $authData):void
    {
        if(false === array_key_exists('login',$authData)){
            throw new RuntimeException('Отсутсвует логин');
        }
        if(false === is_string($authData['login'])){
            throw new RuntimeException('Логин имеет не верный формат');
        }
        if(false === array_key_exists('password',$authData)){
            throw new RuntimeException('Отсутсвует пароль');
        }
        if(false === is_string($authData['password'])){
            throw new RuntimeException('Пароль имеет не верный формат');
        }
    }

    /**
     * проведение аунтификации пользователя
     *
     * @param string $login - логин пользователя
     * @param string $password - пароль пользователя
     * @return bool
     */
    private function isAuth(string $login, string $password):bool
    {
        return $this->httpAuthProvider->auth($login,$password);
    }


}