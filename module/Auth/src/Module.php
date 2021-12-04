<?php

namespace Auth;

use Auth\Authentication\AuthService;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Authentication\Adapter\AbstractAdapter as AuthAdapter;
use Laminas\Authentication\AuthenticationService;
use Laminas\Session\AbstractContainer;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig(): array
    {
        return [
            'factories' => [
                'Session' => function (ServiceManager $sm): AbstractContainer {
                    $session = $sm->get('config')['session'] ?? [];
                    return new $session['class']($session['config']['name']);
                },
                'Service\AuthAdapter' => function (ServiceManager $sm): AuthAdapter {
                    $auth = $sm->get('config')['auth'] ?? [''];
                    return new $auth['class']($auth['config']);
                },
                'Service\AuthService' => function (ServiceManager $sm): AuthService {
                    $aa = $sm->get('Service\AuthAdapter');
                    $as = $sm->get('Service\AuthenticationService');
                    $session = $sm->get('Session');
                    return new AuthService($aa, $as, $session, $sm->get('config'));
                },
            ],
            'invokables' => [
                'Service\AuthenticationService' => AuthenticationService::class,
            ],
        ];
    }
}
