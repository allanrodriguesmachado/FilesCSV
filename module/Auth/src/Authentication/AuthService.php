<?php

namespace Auth\Authentication;

use Exception;
use Laminas\Authentication\Adapter\AbstractAdapter;
use Laminas\Authentication\AuthenticationService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Session\AbstractContainer;
use Laminas\Stdlib\RequestInterface;

class AuthService extends AbstractActionController
{
    private AbstractAdapter $adapter;

    private AuthenticationService $auth;

    private array $config;

    private AbstractContainer $session;

    public function __construct(AbstractAdapter $adapter, AuthenticationService $auth, AbstractContainer $ass, array $config)
    {
        $this->adapter = $adapter;
        $this->auth = $auth;
        $this->config = $config;
        $this->session = $ass;
    }

    public function authenticate(RequestInterface $request)
    {
        if (!$request->isPost()) {
            throw new Exception('Usuário ou Senha inválido(s) ou não informado(s)');
        }

        $username = filter_var($request->getPost()->username, FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_var($request->getPost()->password, FILTER_SANITIZE_SPECIAL_CHARS);

        if (empty($username) && empty($password)) {
            throw new Exception("Usuário ou Senha inválido(s) ou não informado(s)");
        }

        $this->adapter->setUsername($username);
        $this->adapter->setPassword($password);

        if (!$this->auth->authenticate($this->adapter)->isValid()) {
            throw new Exception('Usuário ou Senha inválido(s) ou não informado(s)');
        }

        $this->session->offsetSet('identity', $this->auth->getIdentity());

        return $this->auth->getIdentity();
    }

    /**
     * @throws Exception
     */
    public function logout(RequestInterface $request)
    {
        if (null == $this->auth->getIdentity()) {
            throw new Exception('O usuário não está logado');
        }
        $this->auth->clearIdentity();
    }
}
