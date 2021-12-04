<?php

namespace Auth\Controller\Factory;

use Auth\Controller\AuthController;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class AuthControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): AuthController
    {
        return new AuthController($container->get('Service\AuthService'));
    }
}
