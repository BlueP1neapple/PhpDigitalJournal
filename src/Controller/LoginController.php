<?php

namespace JoJoBizzareCoders\DigitalJournal\Controller;

use JoJoBizzareCoders\DigitalJournal\Exception\RuntimeException;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Auth\HttpAuthProvider;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Controller\ControllerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\HttpResponse;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerRequest;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Http\ServerResponseFactory;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Uri\Uri;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\ViewTemplate\ViewTemplateInterface;
use Throwable;

class LoginController implements ControllerInterface
{

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
     */
    public function __construct(ViewTemplateInterface $viewTemplate, HttpAuthProvider $httpAuthProvider)
    {
        $this->viewTemplate = $viewTemplate;
        $this->httpAuthProvider = $httpAuthProvider;
    }


    /**
     * @inheritDoc
     */
    public function __invoke(ServerRequest $serverRequest): HttpResponse
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
     * @return HttpResponse
     */
    private function buildErrorResponse(Throwable $e):HttpResponse
    {
        $httpCode = 500;
        $context = [
            'errors' => [
                $e->getMessage()
            ]
        ];
        $html = $this->viewTemplate->render(
            __DIR__ . '/../../templates/errors.phtml',
            $context
        );
        return ServerResponseFactory::createHtmlResponse($httpCode, $html);
    }

    /**
     * Процесс аунтификации
     *
     * @param ServerRequest $serverRequest
     * @return HttpResponse
     */
    private function doLogin(ServerRequest $serverRequest):HttpResponse
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
                    ? Uri::createFromString($queryParams['redirect'])
                    :Uri::createFromString('/');
                $response = ServerResponseFactory::redirect($redirect);
            }else{
                $context['errMsg'] = 'Логин и пароль не подходят';
            }
        }
        if (null === $response){
            $html = $this->viewTemplate->render(__DIR__ . '/../../templates/login.phtml', $context);
            $response = ServerResponseFactory::createHtmlResponse(200, $html);
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